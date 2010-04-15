<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Perms extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		//$this->auth->restrict_role(array('admin'));
	}
	
	/**
	 *
	 */
	function index()
	{ 		
		$this->load->model('perms_model');
		
		$roles = $this->perms_model->get_roles();
		$routes = $this->perms_model->get_routes();
		
		$view_data = array(
			'roles' => $roles,
			'routes' => $routes
			);
		
		$content = $this->load->view('admin/users/perms_list', $view_data, true );
		
		$this->gen_page('Admin - Permissions', $content );
	}
	
	function add_route()
	{
		if( $this->input->post('route') && $this->input->post('desc')) {
			if( $this->db->query("select * from routes where route = " . $this->db->escape($this->input->post('route')))->num_rows()) {
				echo json_encode(array('ok' => false, 'message' => 'Route exists'));				
			} else {
				$uri = trim($this->input->post('route'));
				// get rid of the trailing slash if there is one
				if( substr($uri, -1) == '/' )
					$uri = substr($uri, 0, -1);				
				$this->db->set('route', $uri );
				$this->db->set('description', $this->input->post('desc'));
				$this->db->insert('routes');
				echo json_encode(array('ok' => true, 'message' => 'Route added'));
		  }
		} else { 
			echo json_encode(array('ok' => false, 'message' => 'No route'));				
		}
 	}
	
	function rm_route()
	{
		if( $this->input->post('route_id')) {
			$id = intval($this->input->post('route_id'));
			$this->db->query("DELETE FROM routes WHERE id = $id");
			$this->db->query("DELETE FROM route_role_map WHERE route_id = $id");
		}
	}
	
	function get_matrix()
	{
		$role_id = $this->input->post('role');
		
		$q =<<<ELF
			SELECT * FROM routes 
				LEFT JOIN route_role_map 
					ON routes.id = route_role_map.route_id AND role_id = $role_id 
				ORDER BY routes.route
ELF;
		
		$res = $this->db->query($q);
		$html = '<table>';
		$html .= '<tr><th style="width: 70%">route</th><th>allow</th><th/>';
		$i = 0;
		foreach( $res->result() as $row ) {
			$html .= '<tr' . (($i % 2) != 0 ? ' class="odd"' : "") . '>';
			$html .= '<td>';
			$html .= $row->description . ' <span style="color: #666" class="small">('.$row->route.')</span>';
			$html .= '</td>';
			$html .= '<td>';
			$html .= '<input title="foo" onclick="toggle_perm(\''.$role_id.':'.$row->id.'\', this)"';
  			$html .= ' type="checkbox" ' . ($role_id == $row->role_id && $row->allow ? " checked" : "") . '/>';
			$html .= '</td>';
			$html .= '<td>';
			$html .= '<a href="#" onclick="rm_route('.$row->id.')" title="remove route"><img src="/img/cross.png"/></a>';
			$html .= '</td>';
			$html .= '</tr>';
			$i++;
		}
		$html .= '</table>';
		echo $html;
	}
	
	function toggle_perm()
	{
		$role_id = intval($this->input->post('role_id'));
		$route_id = intval($this->input->post('route_id'));
		$allow = intval($this->input->post('allow'));
		
		$res = $this->db->query("SELECT * FROM route_role_map WHERE route_id = $route_id AND role_id = $role_id");
		if( $res->num_rows() ) {
			$this->db->query("UPDATE route_role_map SET allow = $allow WHERE route_id = $route_id AND role_id = $role_id");
		} else {
			$this->db->query("INSERT INTO route_role_map (route_id,role_id,allow) VALUES ($route_id,$role_id,$allow)");
		}
	}
	
}
