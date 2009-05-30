<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Pages extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
		$this->load->library('pagination');
		$this->load->helper('url');		
		$this->load->helper('form');	
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
		
		$pg_data = array(
			'title' => 'Welcome',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/pages/pages_list', $data, true),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
	function add()
	{
		if( $this->input->post('save')) {
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
						
		$pg_data = array(
			'title' => 'Welcome',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/pages/pages_add', $data, true),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
		
	}

	function edit()
	{
		if( $this->input->post('save') ) {
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
		
		$this->db->where('id', $this->uri->segment(4));
		$data['page'] = $this->db->get('pages')->row();
		$data['titles'] = $this->pages_model->getPageTitles();
		$data['parent_select'] = $this->mk_nested_select($data['titles'], $data['page']->parent_id );
		$data['page_types'] = $this->mk_types_select( $data['page']->page_type );
						
		$pg_data = array(
			'title' => 'Welcome',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/pages/pages_edit', $data, true),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
		
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