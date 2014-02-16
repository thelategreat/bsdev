<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";
$route['404_override'] = 'error404';

if (file_exists(APPPATH . '/config/dynamic_routes.php')) {
	include(APPPATH . '/config/dynamic_routes.php');
} else {
	log_message('error', 'Unable to load dynamic routes');
}

/* Dynamic routes defined in section editor */
/* Apparently the database class isn't available at this point even though it worked locally
   so we're going to have to write out the routes to an include file on save 

require_once( BASEPATH .'database/DB'. EXT );
$db =& DB();
$db->where('route IS NOT NULL');
$query = $db->get( 'group_tree' );
$result = $query->result();

foreach( $result as $row )
{
    $route[ $row->route]                 = '/section/view/' . $row->id;
    $route[ $row->route . '/:any']                 = '/section/view/' . $row->id;
}
*/

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */