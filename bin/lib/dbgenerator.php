<?php
/*
  This file is part of the Talon Toolkit
  Copyright (C) 2008  J. Knight <jim@talonedge.com> and
  contributors.

  Talon is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Talon is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* TODO
*  [x] create_table(name, options)
*  [x] drop_table(name)
*  [ ] rename_table(old_name, new_name)
*  [x] add_column(table_name, column_name, type, options)
*  [ ] rename_column(table_name, column_name, new_column_name)
*  [ ] change_column(table_name, column_name, type, options)
*  [ ] remove_column(table_name, column_name)
*  [ ] add_index(table_name, column_name, index_type)
*  [ ] remove_index(table_name, column_name)
*/


/**
 * Database Generator for migrations
 *
 * XML is somewhat modelled after Propel format
 * http://propel.phpdb.org/trac/
 * NOTE: It is not exactly the same
 *
 * TODO
 * - php defaults to sqlite2 (os x) which is pretty brain dead. A lot of the
 *   ifs in here can go when sqlite3
 * 
 * @package default
 * @author J Knight
 **/
class DbGenerator
{
  // valid column type for the xml, mapped to the specific db type
  private $valid_column_types = array( 
    'binary' => array("map" =>
      array( "mysql" => "blob",
             "sqlite" => "blob",
             "pdosqlite" => "blob",
             "pgsql" => "bytea")), 
    'boolean' => array("map" =>
      array( "mysql" => "tinyint(1)",
             "sqlite" => "boolean",
             "pdosqlite" => "boolean",
             "pgsql" => "boolean")), 
    'date' => array("map" =>
      array( "mysql" => "date",
             "sqlite" => "date",
             "pdosqlite" => "date",
             "pgsql" => "date")), 
    'datetime' => array("map" =>
      array( "mysql" => "timestamp",
             "sqlite" => "datetime",
             "pdosqlite" => "datetime",
             "pgsql" => "timestamp")),
    'decimal' => array("map" =>
      array( "mysql" => "decimal",
             "sqlite" => "real",
             "pdosqlite" => "real",
             "pgsql" => "decimal")), 
    'float'   => array("map" =>
      array( "mysql" => "float",
             "sqlite" => "real",
             "pdosqlite" => "real",
             "pgsql" => "float")),
    'integer'   => array("map" =>
      array( "mysql" => "int(11)",
             "sqlite" => "integer",
             "pdosqlite" => "integer",
             "pgsql" => "integer")),
    'char'  => array("map" =>
      array( "mysql" => "char",
             "sqlite" => "varchar",
             "pdosqlite" => "varchar",
             "pgsql" => "char")),
    'varchar' => array("map" =>
      array( "mysql" => "varchar",
             "sqlite" => "varchar",
             "pdosqlite" => "varchar",
             "pgsql" => "varchar")),
    'text'  => array("map" =>
      array( "mysql" => "text",
             "sqlite" => "clob",
             "pdosqlite" => "clob"
             ))
    );
  
  /**
   * CTOR
   *
   * @param $dbtype string database connection type
   * @param $dbconn Connection a connection object
   * @return void
   * @author J Knight
   **/
  function __construct( $dbtype, $dbconn = NULL )
  {
    $this->dbconn = $dbconn;
    $this->dbtype = $dbtype;
  }
  
  /**
   * Generate the database schema
   *
   * If $this->dbconn is not null then statements are run agains the 
   * connection
   *
   * @param $xmlfile the migration file
   * @return text of the database calls
   * @author J Knight
   **/
  function generate( $xmlfile )
  {
    $doc = simplexml_load_file( $xmlfile );
    
    $schema = '';
        
    foreach( $doc->database as $db ) {
      $schema .= "--\n";
      $schema .= "-- Database: " .  $db['name'] . "\n";
      $schema .= "--\n";
      $schema .= $this->gen_database( $db );
      $schema .= "-- db " . $db['name'] . "\n";
      foreach( $db->user as $user ) {
        switch( $this->dbtype ) {
        case "mysql":
          $schema .= "GRANT ALL ON " . $db['name'] . ".* TO " . $user['name'] . "@localhost IDENTIFIED BY '" . $user['passwd'] . "';\n";
          break;
        }
      }    
      $schema .= "\n";
    }
    
    $schema .= "--\n";
    $schema .= "-- Tables:\n";
    $schema .= "--\n";
    foreach( $doc->table as $table ) {
      $s = $this->gen_table( $table );
      $schema .= "$s\n\n";      
    }    
    
    foreach( $doc->record as $rec ) {
      $s = $this->gen_record( $rec );
      $schema .= "$s\n";            
    }
    
    foreach( $doc->add_column as $add ) {
      $s = $this->gen_add_column( $add );
      $schema .= "$s;\n";
    }
    
    return $schema;
  }
    
  function gen_add_column( $add )
  {
    $s = $this->gen_column( $add );
    $s = "ALTER TABLE " . $add['table'] . " ADD COLUMN " . $s;
    if( $this->dbconn ) {
      $this->dbconn->executeQuery( $s  );        
    }
    return $s;
  }

    
  /**
   * Run the undo parts from $xmlfile
   *
   * @param $xmlfile the migration file
   * @return void
   * @author J Knight
   **/
  function undo( $xmlfile )
  {
    $doc = simplexml_load_file( $xmlfile );
    $schema = '';
    foreach( $doc->undo as $undo ) {
      foreach( $undo as $op ) {
        $schema .= $this->gen_drop( $op );
      }
    }
    return $schema;
  }
        
  function gen_drop( $elem )
  {
    $s = '';
    if(isset($elem['table'])) {
      $s .= "DROP TABLE " . $elem['table'];
      if( $this->dbconn ) {
        $this->dbconn->executeQuery( $s );
      }
    }
    return $s . ";\n";
  }
    
  /**
   * Generate a record
   *
   * @return void
   * @author J Knight
   **/
  function gen_record( $rec )
  {
    $s = 'INSERT INTO ' . $rec['table'];
    $cols = '';
    $vals = '';
    foreach( $rec->column as $col ) {
      $cols .= $col['name'] . ", ";
      $vals .= "'" . $col['value'] . "', ";
    }
    $s .= " (" . substr($cols, 0, -2) . ") VALUES (" . substr($vals,0,-2) . ")";
    if( $this->dbconn ) {
      $this->dbconn->executeQuery( $s );
    }
    return $s .= ";\n";
  }
    
  /**
   * Generate a table schema
   *
   * @return void
   * @author J Knight
   **/
  function gen_table( $table )
  {
    $tname = $table['name'];
    $pkeys = array();
    // table
    if( $this->dbconn ) {
      $info = $this->dbconn->getDatabaseInfo();
      $info->getTables();
      if( $info->hasTable( $tname )) {
        $q = "DROP TABLE " . $tname;
        $this->dbconn->executeQuery( $q  );        
      }
    }
    
    $s = "CREATE TABLE " . $tname  . " (\n";
    foreach( $table->column as $col ) {
      $s .= " " . $this->gen_column( $col ) . ",\n";
    }
    if( $this->dbtype != "sqlite") {
      // primary keys
      if( count($pkeys) > 0) {
        $s .= "  PRIMARY KEY (";
        foreach( $pkeys as $pkey ) {
          $s .= "$pkey, ";
        }
        $s = substr( $s, 0, -2 );
        $s .= "),\n";
      }
    
      // foreign keys
      foreach( $table->foreign_key as $cfk ) {
        foreach( $cfk->reference as $ref ) {
          $s .= "  FOREIGN KEY (" . $ref["local"] . ") REFERENCES ";
          $s .= $cfk['table'] . "(" . $ref['foreign'] . "),\n";
        }
      }
    }
    
    $s = substr($s, 0, -2 ) . "\n)";
    
    if( $this->dbconn ) {
      $this->dbconn->executeQuery( $s );
    }
    $s .= ";\n";
    
    // indexes
    $count = 0;
    foreach( $table->index as $cndx ) {
      $count++;
      $si = "CREATE";
      if( isset($cndx['unique'])) {
        $si .= " UNIQUE";
      }
      $si .= " INDEX " . $tname . "_" . $count . " ON " . $tname . " (";
      foreach( $cndx->index_column as $ndx ) {
        $si .= $ndx['name'] . ", ";
      }
      $si = substr( $si, 0, -2) . ")";
      if( $this->dbconn ) {
        $this->dbconn->executeQuery( $si );
      }
      $s .=  $si . ";\n";
    }
    return $s;
  }
  
  function gen_column( $col )
  {
    $s = '';
    $s .= $col['name'] . " ";
    if( !in_array( $col['type'], array_keys($this->valid_column_types))) {
      throw new Exception( 'Unknown column type: ' . $tname . "->" . $col['name'] . ' ' . $col['type']);
    }
    $s .= $this->valid_column_types["".$col['type']]['map'][$this->dbtype];
    if( isset($col['size'])) {
      $s .= '(' . $col['size'] . ')';
    }
    if( isset($col['required'])) {
      $s .= " NOT NULL";
    }
    if( isset($col['primarykey'])) {
      $pkeys[] = $col['name'];
    }
    if( isset($col['autoinc'])) {
      if( $this->dbtype == "mysql") {
        $s .= " AUTO_INCREMENT";
      }
    }
    if( isset($col['default'])) {
      //if( $this->dbtype != "sqlite") {
        $s .= " DEFAULT " . $col['default'];
      //}
    }
    //$s .= ",\n";    
    return $s;
  }
  
  /**
   * Generate a create database call
   *
   * @return void
   * @author J Knight
   **/
  function gen_database( $db, $dbconn = NULL )
  {
    $s = '';    
    /*
    if( $drop ) {
      $s .= "DROP DATABASE " . $db['name'] . " IF EXISTS;\n";
    }
    */
    $charset = 'utf8';
    $collate = 'utf8_unicode_ci';
    
    if( isset($db['charset'])) {
      $charset = $db['charset'];
    }
    if( isset($db['collate'])) {
      $collate = $db['collate'];
    }
    
    $s .= "CREATE DATABASE " . $db['name'] . " CHARSET $charset COLLATE $collate;\n";    
    return $s;
  }
  
}

?>