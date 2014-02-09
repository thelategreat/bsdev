<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Groups extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$this->url = '/admin/groups';
		$this->load->model('groups_model');
    // HACK
    $this->load->model('articles_model');
	}

	function index()
	{		
		$this->load->helper('tree');
		
		$data['tree'] = $this->groups_model->get_group_tree();
		// don't show root item
		$data['tree'] = $data['tree'][0]->children;
		
		
    $this->gen_page('Admin - Groups', 'admin/groups/groups_list', $data );
	}
	
	function add()
	{
		if( $this->input->post('cancel')) {
			redirect($this->url);
		}
		
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
				
		if( $this->form_validation->run()) {
			$data = array();
			$data['parent_id'] = $this->input->post('parent_id');
			$data['name'] = $this->input->post('name');
			$data['deletable'] = "1";
			$data['active'] = $this->input->post('active') ? "1" : "0";
			$this->groups_model->add( $data );
			redirect($this->url);
		}


		$data['tree'] = $this->groups_model->get_group_tree();
		// don't show root item
		$data['tree'] = $data['tree'][0]->children;
		$data['parent_select'] = $this->groups_model->mk_nested_select(0,0,false);
						
		$this->gen_page('Admin - Groups', 'admin/groups/group_add', $data );		
	}

	/**
	Edit an individual group
	@param Int group ID
	**/
	function edit($id = null)
	{				
		
		if( !$id ) {
			redirect($this->url);						
		}
		
		if( $this->input->post('cancel')) {
			redirect($this->url);			
		}

		$this->load->model('lists_model');	
		$this->load->model('list_positions_model');	
		$this->load->model('templates_model');	
		$this->load->model('groups_list_positions_model');	
				
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
				
		if( $this->form_validation->run()) {
			$data = array();
			$data['parent_id'] = $this->input->post('parent_id');
			$data['name'] = $this->input->post('name');
			$data['active'] = $this->input->post('active') ? "1" : "0";
			$this->groups_model->update( $id, $data );
			
			$this->groups_list_positions_model->delete_all_group_lists($id);
			$lists = $this->input->post('lists');
			foreach ($lists as $listkey=>$listval) {
				$this->groups_list_positions_model->insert_group_list($id, $listkey, $listval);
			}
			redirect($this->url);
		}
		
		
		$group = $this->groups_model->get( $id );
		
		if( !$group ) {
			redirect($this->url);						
		}
		
		
		/* The avaiable lists */
		$lists_dropdown = array();
		$lists_dropdown[0] = 'No List';
		foreach ($this->lists_model->get_lists()->result_array() as $l) {
			$lists_dropdown[ $l['id'] ] = $l['name'];
		}
		$data['lists_dropdown'] = $lists_dropdown;
		
		/* The available positions (top, left, etc) */
		$data['list_positions'] = $this->list_positions_model->get_list_positions()->result();
		
		/* The lists assigned to this group with their positions */
		$group_lists = $this->groups_list_positions_model->get_group_lists($id)->result();

		/* Get the list of available templates - templates control which list positions are visible */
		$templates 	= $this->templates_model->get_templates();
		$data['templates'] = $templates;

		$data['group_lists'] = array();
		foreach ($group_lists as $g) {
			$data['group_lists'][$g->list_positions_id] = $g->lists_id;
		}

		$data['group'] = $group;
		$data['tree'] = $this->groups_model->get_group_tree();
		// don't show root item
		$data['tree'] = $data['tree'][0]->children;
		$data['parent_select'] = $this->groups_model->mk_nested_select($data['group']->parent_id, 0, false );

                //var_dump($data['list_positions']);exit();
		$page = $this->load->view('admin/groups/group_edit', $data, true );
						
		$this->gen_page( 'Admin - Groups', $page );
		
	}

	function rm()
	{
    if( $this->uri->segment(4) ) {
      // HACK
      // we check to make sure there aren't any articles assigned to this
      $ret = $this->articles_model->get_articles_by_group( $this->uri->segment(4));

      if( count($ret) == 0 ) {
			  $this->groups_model->rm( $this->uri->segment(4) );
      }  
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
				$this->groups_model->move_up( $id );
				break;
				case 'down':
				$this->groups_model->move_down( $id );
				break;
			}
 		}
		redirect($this->url);
	}


	private function mk_nested_select( $data, $selected = 0, $offset = 0 )
	{
		$s = '';
		$spcs = '';
		for( $i = 0; $i < $offset; $i++ ) {
			$spcs .= '&nbsp;';
		}
		foreach( $data as $item ): 
			$s .= '<option value="' . $item->id . '"';
			if( $item->id == $selected ) {
				$s .= " selected ";
			}
			$s .= '>' . $spcs . $item->name . '</option>';
			if( count($item->children) ) {
				$s .= $this->mk_nested_select( $item->children, $selected, $offset + 4 );
			} 
		endforeach;
		
		return $s;
	}
}
