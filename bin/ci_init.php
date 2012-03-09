<?php
/**
* Initializes the CI environment without running anything so we can
* utilize the various libs in shell scripts.
 *
 * @date Fri Nov 27 10:01:27 EST 2009
 * @author J Knight
 */

error_reporting(E_ALL);
if( !isset($system_folder))
	$system_folder = "../system";
if( !isset($application_folder))
	$application_folder = "../app";

if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

require(BASEPATH.'core/CodeIgniter'.EXT);
require(BASEPATH.'core/Compat'.EXT);
require(APPPATH.'config/constants'.EXT);

$CFG =& load_class('Config');
$LANG =& load_class('Language');

require(BASEPATH.'core/Base5'.EXT);
$CI = new CI_Base();

include_once( $system_folder . '/database/DB.php' );


?>
