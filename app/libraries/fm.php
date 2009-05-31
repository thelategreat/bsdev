<?php if (!defined('BASEPATH')) exit('No direct script access allowed');   
  
/**
 * File Manager class
 * *
 * @package default
 * @author J Knight
 **/
class Fm 
{  
  var $CI = null;

	public function __construct( $params )
	{
		$this->root_path = $params['root'];
    $this->CI =& get_instance();

		if( !file_exists( $this->root_path)) {
			if( !@mkdir( $this->root_path, 0777, true )) {
				show_error( "Error: FM root path does not exist and I can't create it!" );			
			}
		}
  
		if( !is_writable( $this->root_path)) {
			show_error( "Error: FM root path is not writable!" );
		}

	}
	
	/**
	 * Return a list of files for our root
	 *
	 * @param string $with_dirs 
	 * @return void
	 * @author J Knight
	 */
 	public function file_list( $with_dirs = false )
	{
		$files = array();
		$dir = opendir( $this->root_path );
		while(($fname = readdir($dir)) !== false ) {
			if( $fname[0] != '.' ) {								
				$finfo = array();
				if( is_dir( $this->root_path . '/' . $fname ) && $with_dirs ) {
					$finfo['url'] = '/' . $this->root_path . '/' . $fname;
					$finfo['fname'] = $fname;
					$finfo['ftype'] = '';
					$finfo['date'] = date("Y-m-d", filemtime($this->root_path . '/' . $fname));
					$finfo['size'] = $this->pretty_size(filesize( $this->root_path . '/' . $fname ) );
					$finfo['type'] = 'dir';					
				} else {
					$finfo['url'] = '/' . $this->root_path . '/' . $fname;
					$finfo['fname'] = $fname;
					$finfo['ftype'] = '';
					$finfo['date'] = date("Y-m-d", filemtime($this->root_path . '/' . $fname));
					$finfo['size'] = $this->pretty_size(filesize( $this->root_path . '/' . $fname ) );
					$finfo['type'] = 'file';					
				}				
				$files[] = $finfo;
			}
		}
		return $files;
	}

	/**
	 * make a number look human
	 *
	 * TODO: this should be in a helper
	 *
	 * @return void
	 **/
	private function pretty_size( $size )
	{
	  // i dont think php can handle a number beyond tera ;)
	  foreach(array('b','kb','mb','gb','tb','pb','eb','zb','yb') as $sz ) {
	    if( $size < 1024.0 ) {
	      return sprintf("%3.1f %s", $size, $sz );
	    }
	    $size /= 1024.0;
	  }
	}

		
}