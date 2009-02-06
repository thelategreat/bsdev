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

include_once( "dbmodel.php");
include_once( "dbgenerator.php");
include_once( "importer.php" );

/**
 * Migration class to keep database schema in sync.
 *
 * @package default
 * @author J Knight
 **/
class Migrator
{
  // table name used to track which migration version we are at
  public $migration_table_name = 'talon_schema_info';
  
  // field name used to track which migration version we are at
  public $migration_field_name = 'migration';
  
  // the last migration we ran
  private $last_migration = 0;
  
  // the path to the migration files
  private $path;
  
  /**
   * CTOR
   *
   * @param $dbconn a database connection
   * @param $path the path to the migration files
   * @return void
   * @author J Knight
   **/
  public function __construct( $dbconn, $path )
  {
    $this->dbconn = $dbconn;
    $this->init( $path );
  }
  
  /**
   * Initialize the counter table and read the migration files into an
   * array.
   *
   * @return void
   * @author J Knight
   **/
  private function init( $path )
  {
    $this->dbinfo = $this->dbconn->getDatabaseInfo();
    $this->dbinfo->getTables();
//    print_r( $this->dbinfo);
    if( !$this->dbinfo->hasTable($this->migration_table_name)) {
      $q = "CREATE TABLE $this->migration_table_name ($this->migration_field_name integer NOT NULL)";
      $this->dbconn->executeUpdate($q);
      $this->dbconn->executeUpdate("INSERT INTO $this->migration_table_name ($this->migration_field_name) VALUES (0)");
      $this->last_migration = 0;
    } 
    $q = "SELECT $this->migration_field_name FROM $this->migration_table_name";
    $res = $this->dbconn->executeQuery($q);
    if( $res->next()) {
      $row = $res->getRow();
      $this->last_migration = $row[$this->migration_field_name];
      echo "-- Database at: " . $this->last_migration . "\n";
    }    
    $res->close();
    
    $this->directory = $path;
    // read in the files
    $dir = dir( $path );
    $this->files = array();
    while(($file = $dir->read()) !== false) {
      if( substr($file, strrpos($file, '.') + 1) == 'xml' ) {
        $this->files[] = $path . DIRECTORY_SEPARATOR . $file;
      }
    }
    $dir->close();
  }
  
  /**
   * Set the migration back to 0. Does not remove any tables.
   *
   * TODO run reverse migrate instead of this? 
   *
   * @return void
   * @author J Knight
   **/
  public function clear()
  {
    $this->dbconn->executeQuery("DROP TABLE $this->migration_table_name");
    $this->init( $this->directory );
  }
    
  /**
   * Runs the migration backward or forward to latest or indicted version
   *
   * @return void
   * @author J Knight
   **/
  public function migrate( $dbtype, $version = null )
  {
    $schema = '';
    $op = "migrate";
    asort($this->files);
    
    $dbgen = new DbGenerator( $dbtype, $this->dbconn);
    
    if( $version !== null ) {
      $this_migration = intval($version);
      if( $this_migration < $this->last_migration ) {
        arsort( $this->files );
        $op = "undo";
      }
      elseif( $this_migration == $this->last_migration ) {
        die( "-- Database is already at version $this->last_migration.\n");
      }
    }

    foreach($this->files as $fname ) {
      $parts = explode('_',basename($fname));
      $thisver = intval($parts[0]);
      try {
        if( $op == "undo" ) {
          if( $thisver > $this_migration ) {
            echo "-- Reversing $fname\n";
            $dbgen->undo( $fname );
            $thisver -= 1;
            $q = "UPDATE $this->migration_table_name SET $this->migration_field_name = $thisver";
            $this->dbconn->executeUpdate($q);
          }
        } else {
          if( $thisver > $this->last_migration ) {
            echo "-- Migrating $fname\n";
            $schema .= $dbgen->generate( $fname );
            $q = "UPDATE $this->migration_table_name SET $this->migration_field_name = $thisver";
            $this->dbconn->executeUpdate($q);
          } else {
            echo "-- Skipping $fname\n";
          }
        }
      } catch( Exeception $e ) {
        echo $e->getMessage();
      }
    }    
  }
  
  public function load_data( $fname )
  {
    if( !file_exists( $fname )) return;
    if( is_dir( $fname )) {
      $dir = dir( $fname );
      while(($file = $dir->read()) !== false) {
        if( $file[0] == '.' ) continue;
        $this->load_data( $fname . DIRECTORY_SEPARATOR . $file );
      }        
    }
    
    if( is_dir( $fname )) return;
    
    echo "-- loading data: " . $fname . "\n";
    $tmp = explode(".", basename($fname));
    $filetype = $tmp[1];
    $tmp2 = explode("_", $tmp[0]);
    // take the number off the front and put it back together
    array_shift($tmp2);
    $tablename = join('_', $tmp2);
    $importer = new TalonImporter();
    
        
    switch( $filetype ) {
      case "csv":
      $cdata = $importer->importCsv( $fname );
      $dbm = new TalonDbModel();
      $dbm->set_table( $tablename );
      //$cols = $dbm->get_columns( );
      $cols = array_shift( $cdata );
      foreach( $cdata as $row ) {
        $data = array();
        for( $i = 0; $i < count($cols); $i++ ) {
          $data[$cols[$i]] = $row[$i];
        }
        $dbm->add( $data );
      }
      break;
      case "txt":
      break;
      case "xml":
      break;
      default:
      echo "Unkown file type: " . $filetype;
    }
  }
  
  public function save_data( $fname )
  {
    
  }
}

?>