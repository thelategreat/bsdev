<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Tabs
{
	function __construct()
	{
  
	}
  
	/**
 	 * Generate tabs for the view
	 *
	 * @return void
	 **/
	public function gen_tabs( $tabs, $selected, $url )
	{
		$s = '<div class="tabs"><ul>';
		foreach( $tabs as $tab ) {
			$s .= '<li><a href="' .  $url . '/' . str_replace(' ', '_', strtolower($tab)) . '"';
			if( strtolower($tab) == $selected) {
				$s .= ' class="selected"';
			}
			$s .= '>' . $tab . '</a></li>';
		}
		$s .= '</ul></div>';
		return $s;
	} 
}