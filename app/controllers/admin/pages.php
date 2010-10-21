<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Pages extends Admin_Controller 
{
	/**
	 * CTORß
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');
		$this->load->helper('media');	
		$this->load->helper('tree');	
		$this->load->model( 'pages_model' );	
		
		$this->url = '/admin/pages';
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{		
		
		$data['titles'] = $this->pages_model->get_pages_tree();
		
		$this->gen_page('Admin - Pages', 'admin/pages/pages_list', $data );
	}
	
	function add()
	{
		if( $this->input->post('cancel')) {
			redirect($this->url);			
		}
		
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['parent_id'] = $this->input->post('parent_id');
			$data['page_type'] = $this->input->post('page_type');
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$this->pages_model->add( $data );
			redirect($this->url);
		}

		$data['pages'] = $this->db->get('pages');
		$data['titles'] = $this->pages_model->get_pages_tree();
		$data['parent_select'] = $this->pages_model->mk_nested_select(0,0,false);
		$data['page_types'] = $this->mk_types_select( 'page' );
						
		$this->gen_page('Admin - Pages', 'admin/pages/pages_add', $data );		
	}

	function edit()
	{
		
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect($this->url);						
		}
		
		if( $this->input->post('cancel')) {
			redirect($this->url);			
		}
		
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		
		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}
				
		if( $cur_tab == 'details' && $this->form_validation->run()) {
			$data = array();
			$data['parent_id'] = $this->input->post('parent_id');
			$data['page_type'] = $this->input->post('page_type');
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$this->pages_model->update( $id, $data );
			redirect($this->url);
		}

		$pg = $this->pages_model->get($id);
		
		if( !$pg ) {
			redirect($this->url);						
		}
		
		
		$data['page'] = $pg;
		$data['titles'] = $this->pages_model->get_pages_tree();
		$data['parent_select'] = $this->pages_model->mk_nested_select($data['page']->parent_id,0,false );
		$data['page_types'] = $this->mk_types_select( $data['page']->page_type );
		$data['tabs'] = $this->tabs->gen_tabs(array('Details','Media'), $cur_tab, '/admin/pages/edit/' . $id);
		$slot = 'general';
		if( $this->uri->segment(6)) {
			$slot = $this->uri->segment(6);
		}

		switch( $cur_tab ) {
			case 'media':
			$page = $data['page'];
			$view_data = array( 
				'title' => "Media for page: $page->title",
				'page' => $page, 
				'path' => '/pages/' . $page->id,
				'next' => "/admin/pages/edit/$page->id/media",
				'slots' => $page->slots,
				'slot' => $slot,
				'tabs' => $this->tabs->gen_tabs(array('Details','Media'), 'Media', '/admin/pages/edit/' . $page->id)
			);
			$page = $this->load->view('admin/media/media_tab', $view_data, true );
			break;
			default:
			$page = $this->load->view('admin/pages/pages_edit', $data, true );
		}
						
		$this->gen_page( 'Admin - Pages', $page );
	}

	function rm()
	{
		$id = (int)$this->uri->segment(4);
		
		if( $id ) {
			$this->pages_model->rm( $id );
		}
		redirect($this->url);
	}

	function sort()
	{
		$dir = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		if( $dir && $id ) {
			switch( $dir ) {
				case 'up':
				$this->pages_model->move_up( $id );
				break;
				case 'down':
				$this->pages_model->move_down( $id );
				break;
			}
 		}
		redirect($this->url);
	}

	function media()
	{
		$html = '';
		$id = $this->uri->segment(4);
		$slot = slugify($this->uri->segment(5));
		
		//$this->db->where('slot', $slot );
		//$this->db->where('path', "pages/$id");
		//$res = $this->db->get('media_map');
		$res = $this->media_model->get_media_for_path("pages/$id", $slot );
		
		$html = '';
		$html .= '<table class="media_table">';
		$html .= "<tr>";
		$html .= '<th style="width: 40%" colspan="2">file</th>';
		$html .= '<th>tags</th>';
		$html .= '<th>author</th>';
		$html .= '<th>date/size</th>';
		$html .= '<th>order</th>';
		$html .= '</tr>';
		foreach( $res as $row ) {
			$html .= '<tr>';
			$html .= '<td><img src="' . $row['url'] . '" width="100"/></td>';
			$html .= '<td />';
			$html .= '<td />';
			$html .= '<td />';
			$html .= '<td />';
			$html .= '<td>';
			$html .= '<a href="#"><img class="icon" src="/img/go-up.png" /><a/>';
			$html .= '&nbsp;-&nbsp;'; 
			$html .= '<a href="#"><img class="icon" src="/img/go-down.png" /></td></a>';
			$html .= '</td>';
			$html .= '</tr>';
		}
		$html .= "</table>";
		
	  header("Content-Type: text/html");
		//echo '<h1>' . $path . '</h1>';
		echo $html;
		
	}

	private function mk_types_select( $selected = null )
	{
		$types = array('page','link');
		$s = '<select name="page_type" onchange="change_type(this);">';
		foreach( $types as $type ) {
			$s .= '<option ' . ($type == $selected ? " selected" : "") . ">" . $type . '</option>';
		}
		$s .= '</select>';
		return $s;
	}
}
