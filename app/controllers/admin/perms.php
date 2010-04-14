<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Perms extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin'));
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
		
		$pg_data = array(
			'title' => 'Admin',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $content
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
	function add_route()
	{
		if( $this->input->post('route') && $this->input->post('desc')) {
			if( $this->db->query("select * from routes where route = " . $this->db->escape($this->input->post('route')))->num_rows()) {
				echo json_encode(array('ok' => false, 'message' => 'Route exists'));				
			} else {
				$this->db->set('route', $this->input->post('route'));
				$this->db->set('description', $this->input->post('desc'));
				$this->db->insert('routes');
				echo json_encode(array('ok' => true, 'message' => 'Route added'));
		  }
		} else { 
			echo json_encode(array('ok' => false, 'message' => 'No route'));				
		}
 	}
	
	function get_matrix()
	{
		$role_id = $this->input->post('role');
		
		$res = $this->db->query("SELECT * FROM routes LEFT JOIN route_role_map ON routes.id = route_role_map.route_id AND role_id = $role_id ORDER BY routes.route");
		$html = '<table>';
		$html .= '<tr><th style="width: 70%">route</th><th>allow</th>';
		$i = 0;
		foreach( $res->result() as $row ) {
			$html .= '<tr' . (($i % 2) != 0 ? ' class="odd"' : "") . '>';
			$html .= '<td>';
			$html .= $row->description . ' <span style="color: #666" class="small">('.$row->route.')</span>';
			$html .= '</td>';
			$html .= '<td>';
			$html .= '<input onclick="toggle_perm(\''.$role_id.':'.$row->id.'\', this)"';
  			$html .= ' type="checkbox" ' . ($role_id == $row->role_id && $row->allow ? " checked" : "") . '/>';
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
