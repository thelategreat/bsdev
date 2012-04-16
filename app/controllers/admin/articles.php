<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Articles extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
		$this->load->model('groups_model');
    $this->load->model('lists_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$page_size = $this->config->item('list_page_size');
		$page = 1;
		$query = '';
		
		// 4th seg is page number, if present
		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}
			
		// seg 5 and beyond are search terms
		$i = 5;
		while( $this->uri->segment($i) ) {
			// CI thing with _
			$query .= str_replace('_',' ',$this->uri->segment($i)) . ' ';
			$i++;
		}

		if( $this->input->post('q')) {
			$query = $this->input->post('q');
		}
		
		$articles = $this->articles_model->get_article_list( NULL, $page, $page_size, $query );
				
		$view_data = array( 
			'articles' => $articles,
			'pager' => mk_pager( $page, $page_size, $articles->num_rows(), '/admin/articles/index', $query ),
			'query' => $query
			);
		
		$this->gen_page('Admin - Articles', 'admin/articles/article_list', $view_data );
	}
	
	/**
	 *
	 */
	function add()
	{
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			$this->form_validation->set_rules('excerpt','Excerpt','trim|required');
			$this->form_validation->set_rules('author','User','trim|required');
			if( $this->form_validation->run()) {
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('group', $this->input->post('group'), false);
				//$this->db->set('display_priority', $this->input->post('display_priority'), false);
				//$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('category', 1, false);
				$this->db->set('publish_on', $this->input->post('publish_on'));
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				//$this->db->set('tags', $this->input->post('tags'));
				$this->db->set('author', $this->input->post('author'));
				$this->db->set('owner', $this->session->userdata('logged_user'));
				$this->db->set('created_on', "NOW()", false);
				$this->db->set('status', 1);
				$this->db->insert("articles");
				redirect("/admin/articles");
			}
		}	
			
		if( $this->input->post("cancel")) {
			redirect("/admin/articles");			
		}
		
		$view_data = array(
			'group_select' => '<select name="group" id="group-sel">' . $this->groups_model->mk_nested_select(0,0,false) . '</select>',
			'category_select' => $this->articles_model->category_select(),
			'priority_select' => $this->articles_model->priority_select()
			);
		
		$this->gen_page('Admin - Essays', 'admin/articles/article_add', $view_data );
	}

	function edit()
	{
		$tab = $this->uri->segment( 5 );
		if( $tab && $tab == "media") {
			$this->edit_media();			
		} else {
			$this->edit_article();
		}
	}

	function edit_article()
	{
		$article_id = (int)$this->uri->segment(4);
		
		if( !$article_id ) {
			redirect("/admin/articles");
		}

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}

		// ------------
		// U P D A T E
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			$this->form_validation->set_rules('author','User','trim|required');
			$this->form_validation->set_rules('excerpt','Excerpt','trim|required');
			if( $this->form_validation->run()) {
				$this->db->where('id', $article_id);
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('group', $this->input->post('group'), false);
				$this->db->set('display_priority', $this->input->post('display_priority'), false);
				$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('status', $this->input->post('status'), false);
				$this->db->set('publish_on', $this->input->post('publish_on'));
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('author', $this->input->post('author'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				$this->db->set('tags', $this->input->post('tags'));
				$this->db->update("articles");
				redirect("/admin/articles");
			}
		}	

		// ------------
		// D E L E T E
		if( $this->input->post("rm")) {
			$this->db->where('id', $article_id);
			$this->db->delete('articles');
			$this->db->where('path', "/articles/$article_id");
			$this->db->delete('media_map');
			redirect("/admin/articles");			
		}
			
		// -----------
		// C A N C E L
		if( $this->input->post("cancel")) {
			redirect("/admin/articles");			
		}

		
		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();
		if( !$article ) {
			redirect("/admin/articles");			
		}
		
		$view_data = array( 
			'article' => $article, 
			'slot' => 'general',
			//'group_select' => $this->articles_model->group_select( $article->group ),
			'group_select' => '<select name="group" id="group-sel">' . $this->groups_model->mk_nested_select($article->group,0,false) . '</select>',
			'category_select' => $this->articles_model->category_select( $article->category ),
			'status_select' => $this->articles_model->status_select( $article->status ),
      'priority_select' => $this->articles_model->priority_select( $article->display_priority ),	
      'lists_select' => $this->lists_model->lists_select(),    
			'tabs' => $this->tabs->gen_tabs(array('Essay','Media'), 'Essay', '/admin/articles/edit/' . $article_id)
		);
		
		$this->gen_page('Admin - Essays', 'admin/articles/article_edit', $view_data );
	}

	function edit_media()
	{
		$article_id = $this->uri->segment(4);
		
		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();
				
		$view_data = array( 
			'title' => "Media for essay: $article->title",
			'article' => $article, 
			'path' => '/articles/' . $article->id,
			'next' => "/admin/articles/edit/$article->id/media",
			'tabs' => $this->tabs->gen_tabs(array('Essay','Media'), 'Media', '/admin/articles/edit/' . $article->id)
		);
		
		$this->gen_page('Admin - Essays', 'admin/media/media_tab', $view_data );		
	}
	
	function addcat()
	{
		$ret = array('ok' => false, 'msg' => 'Unable to add category' );
		if( $this->input->post('cat')) {
			$cat = trim($this->input->post('cat'));
			if( strlen($cat)) {
				$this->db->set('category', $cat);
				$this->db->insert('article_categories');
				$ret['id'] = $this->db->insert_id();
				$ret['cat'] = $cat;
				$ret['ok'] = true;
				$ret['msg'] = '';
			}
		}
		echo json_encode( $ret );
	}

	function addgroup()
	{
		$ret = array('ok' => false, 'msg' => 'Unable to add group' );
		if( $this->input->post('group')) {
			$group = trim($this->input->post('group'));
			if( strlen($group)) {
				$this->db->set('group', $group);
				$this->db->insert('article_groups');
				$ret['id'] = $this->db->insert_id();
				$ret['group'] = $group;
				$ret['ok'] = true;
				$ret['msg'] = '';
			}
		}
		echo json_encode( $ret );
	}
	
}
