<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Pages extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');
		$this->load->helper('media');	
		$this->load->helper('pages');	
		$this->load->model( 'pages_model' );	
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{		
		$this->load->helper('pages');
		
		$data['pages'] = $this->db->get('pages');
		$data['titles'] = $this->pages_model->getPageTitles();
		
		$this->gen_page('Admin - Pages', 'admin/pages/pages_list', $data );
	}
	
	function add()
	{
		if( $this->input->post('save')) {
			unset($_POST['save']);
			$this->pages_model->add_page( $_POST );
			redirect('/admin/pages');
		}

		if( $this->input->post('cancel')) {
			redirect('/admin/pages');			
		}

		$data['pages'] = $this->db->get('pages');
		$data['titles'] = $this->pages_model->getPageTitles();
		$data['parent_select'] = $this->mk_nested_select($data['titles']);
		$data['page_types'] = $this->mk_types_select( 'page' );
						
		$this->gen_page('Admin - Pages', 'admin/pages/pages_add', $data );		
	}

	function edit()
	{
		if( $this->input->post('save') ) {
			unset($_POST["save"]);
			$this->input->post('title');
			$this->db->where('id', $this->uri->segment(4));
			if( !isset($_POST['active'])) {
				$_POST['active'] = 0;
			} else {
				$_POST['active'] = 1;
			}
			$this->db->update('pages', $_POST);
			redirect('/admin/pages');
		}
		
		if( $this->input->post('cancel')) {
			redirect('/admin/pages');			
		}

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}
		
		$id = $this->uri->segment(4);
		$this->db->where('id', $id );
		$data['page'] = $this->db->get('pages')->row();
		$data['titles'] = $this->pages_model->getPageTitles();
		$data['parent_select'] = $this->mk_nested_select($data['titles'], $data['page']->parent_id );
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
		if( $this->uri->segment(4) ) {
			$this->db->where('id', $this->uri->segment(4));
			$this->db->delete('pages');
		}
		redirect('/admin/pages');
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
		redirect('/admin/pages');
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

	private function mk_nested_select( $data, $selected = 0, $offset = 0 )
	{
		$s = '';
		$spcs = '';
		for( $i = 0; $i < $offset; $i++ ) {
			$spcs .= '&nbsp;';
		}
		foreach( $data as $page ): 
			$s .= '<option value="' . $page->id . '"';
			if( $page->id == $selected ) {
				$s .= " selected ";
			}
			$s .= '>' . $spcs . $page->title . '</option>';
			if( count($page->children) ) {
				$s .= $this->mk_nested_select( $page->children, $selected, $offset + 4 );
			} 
		endforeach;
		
		return $s;
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */