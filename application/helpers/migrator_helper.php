<?php if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * DBGenerator
 *
 * Takes an xml file and generates database specific SQL statements,
 * allowing a somewhat abstract way to deal with SQL.
 *
 * e.g.
 * <migration>
 *  ...
 * </migration>
 *
 * @package default
 * @author J Knight
 **/
class DBGenerator
{
  	// valid column type for the xml, mapped to the specific db type
	// only need add an entry if the type is not the same as the key
	// CI drivers = mssql, mysql, mysqli, oci8, odbc, postgre, sqlite
	private $valid_column_types = array( 
		'binary' => array(
			"map" =>
				array( 	"mysql" => "blob",
						"mysqli" => "blob",
						"sqlite" => "blob",
						"postgre" => "bytea")),
				 
		'boolean' => array(
			"map" =>
				array( 	"mysql" => "tinyint(1)",
						"mysqli" => "tinyint(1)")), 
				
		'date' => array(
			"map" => 
				array( )),
			 
		'datetime' => array(
			"map" =>
				array( 	"mysql" => "timestamp",
						"mysqli" => "timestamp",
						"postgre" => "timestamp")),
				
		'decimal' => array(
			"map" =>
				array( 	"sqlite" => "real")), 
				
		'float'   => array(
			"map" =>
				array( 	"sqlite" => "real")),
				
		'integer'   => array(
			"map" =>
				array( 	"mysql" => "int(11)",
						"mysqli" => "int(11)")),
						
		'char'  => array(
			"map" =>
				array( "sqlite" => "varchar")),
				
		'varchar' => array(
			"map" => 
				array()),
				
		'text'  => array(
			"map" =>
				array( "sqlite" => "clob"))
	  );

	// CI database connection
	var $DBO = null;

	/**
	 * CTOR
	 *
	 * @param $dbtype the name of the driver
	 * @param $dbo the CI database object
	 * @return void
	 **/
	function __construct( $dbtype, $dbo = null )
	{
		if( $dbo ) {
			$this->DBO = $dbo;
		}
		$this->dbtype = $dbtype;
		$this->pkeys = array();
	}
	
	/**
	 * Load an xml file and generates SQL
	 *
	 * If $this->DBO is not null, the generated statements are run against
	 * the database.
	 *
	 * @param $xmlfile the path to an xml migration file
	 * @return the generated SQL
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

		// add column
		foreach( $doc->add_column as $add ) {
			$s = $this->gen_add_column( $add );
			$schema .= "$s;\n";
		}

		// rename 
		foreach( $doc->rename as $ren ) {
			$s = $this->gen_rename( $ren );
			$schema .= "$s;\n";
		}

		// drop
		foreach( $doc->drop as $drop ) {
			$s = $this->gen_drop( $drop );
			$schema .= "$s;\n";
		}
		
		// execute sql
		foreach( $doc->execute as $exec ) {
			if( isset($exec['if-type'])) {
				if( $this->dbtype == $exec['if-type']) {
					$s = $exec['query'];					
				}
			} else {
				$s = $exec['query'];
			}
			$schema .= "$s;\n";            
		}

		// add records
		foreach( $doc->record as $rec ) {
			$s = $this->gen_record( $rec );
			$schema .= "$s;\n";            
		}

		return $schema;
	}
	
	/**
	 * Generate a drop column|table|database statement
	 *
	 * @param $elem an XMLElement
	 * @return SQL string
	 **/
	function gen_drop( $drop ) 
	{
		$s = '';
		if( isset( $drop['column'])) {
			$s .= "ALTER TABLE " . $drop['table'] . " DROP COLUMN " . $drop['column'];
			if( $this->dbtype == "postgre") {
				$s .= " CASCADE";
			}
		} elseif( isset($drop['table'])) {
			$s .= "DROP TABLE " . $drop['table'];
			if( $this->dbtype == "postgre") {
				$s .= " CASCADE";
			}			
		} elseif( isset($drop['database'])) {
			$s .= "DROP DATABASE " . $drop['database'];
 		}
		return $s;
	}
	
	/**
	 * Generate a rename column statement
	 *
	 * @return SQL string
	 **/
	function gen_rename( $ren )
	{
		$s = '';
		if( isset( $ren['column'])) {
			if( $this->dbtype == "mysql" || $this->dbtype == "mysqli" && $this->DBO ) {
				// mysql has a nice idiotic way of doing this. 
				// you have to give it info it already has
				$s .= "ALTER TABLE " . $ren['table'] . " CHANGE " . $ren['column'] . " ";
				$s .= $ren['to'];
			
				$res = $this->DBO->query('DESCRIBE ' . $ren['table']);
				foreach( $res->result() as $row ) {
					if( $row->Field == $ren['column']) {
						$s .= " " . $row->Type;
					}
				}
			} elseif( $this->dbtype == "postgre" ) {
				$s = "ALTER TABLE " . $ren['table'] . " RENAME COLUMN " . $ren['column'] . " TO " . $ren['to'];			
			} else {
				$s = '-- RENAME COLUMN not supported for ' . $this->dbtype;
			}
		} elseif( isset($ren['table'])) {
			if( $this->dbtype == "mysql" || $this->dbtype == "mysqli") {
				$s .= "RENAME TABLE " . $ren['table'] . " TO " . $ren['to'];
			} elseif( $this->dbtype == "postgre" ) {
				$s .= "ALTER TABLE " . $ren['table'] . " RENAME TO " . $ren['to'];
			} else {
				$s = '-- RENAME table not supported for ' . $this->dbtype;				
			}
		} elseif( isset($ren['database'])) {
			if( $this->dbtype == "mysql" || $this->dbtype == "mysqli") {
				$s .= "RENAME DATABASE " . $ren['database'] . " TO " . $ren['to'];
			} elseif( $this->dbtype == "postgre" ) {
				$s .= "ALTER DATABASE " . $ren['database'] . " RENAME TO " . $ren['to'];
			} else {
				$s = '-- RENAME database not supported for ' . $this->dbtype;				
			}			
		}
		
		return $s;
	}
	
	/**
	 * Generate a column add statement
	 *
	 * @param $add an XMLElement
	 * @return SQL string
	 **/
	function gen_add_column( $add )
	{
		$s = $this->gen_column( $add );
		$s = "ALTER TABLE " . $add['table'] . " ADD COLUMN " . $s;
		if( $this->DBO ) {
			$this->DBO->query( $s );
		}
		return $s;
	}

    
	/**
	 * Run the undo parts from $xmlfile
	 *
	 * @param $xmlfile the migration file
	 * @return void
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
            
	/**
	 * Generate an insert statement
	 *
	 * @param $rec an XMLElement
	 * @return SQL string
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
		if( $this->DBO ) {
			$this->DBO->query( $s );
		}
		return $s .= ";\n";
	}
    
	/**
	 * Generate a table schema
	 *
	 * @param $table an XMLElement
	 * @return SQL string
	 **/
	function gen_table( $table )
	{
		$tname = $table['name'];
		$this->pkeys = array();
		// table
		if( $this->DBO ) {	
			if( $this->DBO->table_exists( $tname )) {
				$q = "DROP TABLE " . $tname;
				if( $this->dbtype == "postgre") {
					// pg will complain if there are FK contraints
					$q .= " CASCADE";
				}
				$this->DBO->query( $q );
			}
		}
  
		$s = "CREATE TABLE " . $tname  . " (\n";
		foreach( $table->column as $col ) {
			$s .= " " . $this->gen_column( $col ) . ",\n";
		}

		// primary keys
		if( count($this->pkeys) > 0) {
			$s .= "  PRIMARY KEY (";
			foreach( $this->pkeys as $pkey ) {
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
  
		$s = substr($s, 0, -2 ) . "\n)";
  
		if( $this->DBO ) {
			$this->DBO->query( $s );
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
			if($this->DBO ) {
				$this->DBO->query( $si );
			}
			$s .=  $si . ";\n";
		}
		return $s;
	}
  
	/**
	 * Generate a column definition statement
	 *
	 * @param $col an XMLElement
	 * @return SQL string
	 **/
	function gen_column( $col )
	{
		$s = '';
		$s .= $col['name'] . " ";
		if( !in_array( $col['type'], array_keys($this->valid_column_types))) {
			throw new Exception( 'Unknown column type: ' . $col['name'] . ' ' . $col['type']);
		}
		
		if( isset($col['autoinc']) && $this->dbtype == 'postgre') {
			// postgres has a funky auto increment scheme
			$s .= "SERIAL";
		} else {
			// see if it's mapped
			if( isset($this->valid_column_types["".$col['type']]['map'][$this->dbtype])) {
				$s .= $this->valid_column_types["".$col['type']]['map'][$this->dbtype];
			} else {
				$s .= $col['type'];
			}
		}
		
		if( isset($col['size'])) {
			$s .= '(' . $col['size'] . ')';
		}
		if( isset($col['required'])) {
			$s .= " NOT NULL";
		}
		if( isset($col['primarykey'])) {
			if( !in_array( $col['name'], $this->pkeys)) {
			  		$this->pkeys[] = $col['name'];
			}
		}
		if( isset($col['autoinc'])) {
			if( $this->dbtype == "mysql") {
				$s .= " AUTO_INCREMENT";
				if( !in_array( $col['name'], $this->pkeys)) {
					$this->pkeys[] = $col['name'];
				}
			}
		}
		if( isset($col['default'])) {
			$s .= " DEFAULT " . $col['default'];
		}
		return $s;
	}

	/**
	 * Generate a database create statement
	 *
	 * @param $db an XMLElement
	 * @return SQL string
	 **/
	function gen_database( $db )
	{
		$s = '';    

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

/**
 * DBMigrator
 *
 * @package default
 * @author J Knight
 **/
class DBMigrator
{
	// the name of the table used to track the current migration state
	var $migration_table_name = 'db_migration_info';
	// the name of the field in the table
	var $migration_field_name = 'migration';
	
	// for debugguing (temporary)
	var $verbose = true;
	
	/**
	 * CTOR
	 *
	 * @param $path the path to the location of the migration files
	 * @param $dbo a CI database object, already connected
	 * @return void
	 **/
	function __construct( $path, $dbo )
	{
		$this->DBO = $dbo;				
		$this->init( $path );
	}
	
	/**
	 * Initialize
	 *
	 * @param $path the path to the location of the migration files
	 * @return void
	 **/
	private function init( $path )
	{
		$this->path = $path;
		
		// make sure the migration table exists and create it if not
		if( ! $this->DBO->table_exists($this->migration_table_name)) {
			$this->DBO->query("CREATE TABLE $this->migration_table_name ($this->migration_field_name INTEGER)");
			
			$this->DBO->set($this->migration_field_name, 0, false);
			$this->DBO->insert($this->migration_table_name);
			$this->last_migration = 0;
		}
		
		// get the last migration
		$row = $this->DBO->get($this->migration_table_name)->row_array();
		$this->last_migration = $row[$this->migration_field_name];
		
		// collect all the relevant files in the given path
		$this->files = array();
		if( file_exists($this->path)) {
			$dir = dir( $path );
			while( ($file = $dir->read()) !== false ) {
				if( substr( $file, strrpos($file, '.') + 1) == 'xml') {
					$this->files[] = str_replace('//', '/', $path . DIRECTORY_SEPARATOR . $file);
				}
			}
			$dir->close();
		}
	}
	
	/**
	 * Reset the migration back to zero and re-initialize
	 *
	 * @return void
	 **/
	public function reset( )
	{
		if( $this->DBO->table_exists($this->migration_table_name)) {
			$this->DBO->set($this->migration_field_name, 0, false);
			$this->DBO->update($this->migration_table_name);	
		}	
		$this->DBO->close();
		$this->init($this->path);
	}
	
	public function dump()
	{
		$s = "<migration>\n";
		foreach( $this->DBO->list_tables() as $table ) {
			if( $table == "db_migration_info")
				continue;
			$s .= '  <table name="' . $table . "\">\n";
			$res = $this->DBO->query('SHOW COLUMNS FROM ' . $table );
			foreach( $res->result() as $col ) {
				$s .= '    <field name="' . $col->Field . '"'; 
				if( preg_match('/(.*)\((\d+)\)/', $col->Type, $matches )) {
					$s .= ' type="' . $matches[1] . '"';
					$s .= ' size="' . $matches[2] . '"';
				} else
					$s .= ' type="' . $col->Type . '"';				
				if( $col->Null == 'NO') {
					$s .= ' required="true"';
				}
				if( $col->Key == 'PRI') {
					$s .= ' primarykey="true"';
				}
				if( $col->Extra == 'auto_increment' ) {
					$s .= ' autoinc="true"';
				}
				$s .= '>' . "\n";
			}
			$res = $this->DBO->query('SHOW INDEXES FROM ' . $table );
			foreach( $res->result() as $ndx ) {
				if( $ndx->Key_name == 'PRIMARY' )
					continue;
				$s .= '    <index ';
				if( $ndx->Non_unique == "0")
					$s .= ' unique="true"';
				$s .= ">\n";
				$s .= '      <index_column name="' . $ndx->Column_name . '" />' . "\n";
				$s .= '    </index>' . "\n";
			}
			$s .= '  </table>' . "\n";
		}
		$s .= '</migration>';
		print $s;
	}
	
	/**
	 * Run the migration
	 *
	 * @param $version run to this version. if null, runs to the latest
	 *                 migration file
	 * @return true on success
	 **/
	public function migrate( $version = null )
	{
		$schema = '';
		$op = 'migrate';
		
		if( count($this->files) == 0 ) {
			if( $this->verbose ) {
				echo "-- no migration files found";
			}			
			return false;
		}
		
		asort($this->files);
		
		$dbgen = new DBGenerator( $this->DBO->platform(), $this->DBO );
		
		// if a version is given, decide if this is an undo
		if( $version !== null ) {
			$this_migration = intval($version);
			if( $this_migration < $this->last_migration ) {
				arsort( $this->files );
				$op = 'undo';
			} elseif( $this_migration == $this->last_migration ) {
				if( $this->verbose )
					echo "-- database already at version $this->last_migration\n";
				return true;
			}
		} 
		
		// run though the file list and try and do what's right
		foreach( $this->files as $fname ) {
			$parts = explode('_', basename( $fname ));
			$thisver = intval($parts[0]);
			try {
				// -------
				// U N D O
				if( $op == 'undo' ) {
					if( $thisver > $this_migration ) {
						if( $this->verbose )
							echo "-- Reversing $fname\n";
						$outp = $dbgen->undo( $fname );
						if( $this->verbose )
							echo $outp;
						$thisver -= 1;
						$this->DBO->set($this->migration_field_name, $thisver, false);
						$this->DBO->update($this->migration_table_name);
					}
				} else {
					// -------------
					// M I G R A T E
					if( $thisver > $this->last_migration ) {
						if( $this->verbose )
							echo "-- Migrating $fname\n";
						$outp = $dbgen->generate( $fname );
						if( $this->verbose )
							echo $outp;
						$this->DBO->set($this->migration_field_name, $thisver, false);
						$this->DBO->update($this->migration_table_name);
					} else {
						if( $this->verbose )
							echo "-- Skipping $fname\n";
					}
				}
			} catch( Exception $e ) {
				if( $this->verbose )
					echo $e;
				return false;
			}
		}
		
		return true;
	}
}