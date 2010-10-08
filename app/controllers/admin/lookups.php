<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Lookups extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('ads_model');
		
		$this->lookup = array(
			'event_categories' => array('Event Categories','category'),
			'event_audience' => array('Event Audience','audience'),
			'article_categories' => array('Article Categories','category'),
			'article_groups' => array('Article Groups','group')
		);
	}
	
	function index()
	{
			$view_data = array( 
				'lookups' => $this->lookup
				);

			$this->gen_page('Admin - Lookups', 'admin/lookups', $view_data );
	}
	
	function get_items()
	{
		$table = $this->input->post('item');
		
		if( $table ) {
			$res = $this->db->get($table);
			$s = '<table>';
			foreach( $res->result_array() as $row ) {
				$s .= '<tr>';
				$s .= '<td>' . $row[$this->lookup[$table][1]] . '</td>';
				$s .= '</tr>';
			}
			$s .= '</table>';
			echo $s;
			
		} else {
			echo '??';
		}
		
	}
	
}		