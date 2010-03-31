<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Articles extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin','editor'));
		$this->load->helper('url','form');
		$this->load->library('tabs');
		$this->load->library('form_validation');
		$this->load->model('articles_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$articles = $this->articles_model->get_article_list();
		
		$view_data = array( 'articles' => $articles );
		
		$pg_data = array(
			'title' => 'Admin - Articles',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/articles/article_list', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
	function add()
	{
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			if( $this->form_validation->run()) {
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				$this->db->set('tags', $this->input->post('tags'));
				$this->db->set('author', $this->session->userdata('logged_user'));
				$this->db->set('created_on', "NOW()", false);
				$this->db->insert("articles");
				redirect("/admin/articles");
			}
		}	
			
		if( $this->input->post("cancel")) {
			redirect("/admin/articles");			
		}
		
		$view_data = array(
			'category_select' => $this->articles_model->category_select(),
			);
		
		$pg_data = array(
			'title' => 'Admin - Articles',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/articles/article_add', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
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
		$article_id = $this->uri->segment(4);

		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			if( $this->form_validation->run()) {
				$this->db->where('id', $article_id);
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('status', $this->input->post('status'), false);
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				$this->db->set('tags', $this->input->post('tags'));
				$this->db->update("articles");
				redirect("/admin/articles");
			}
		}	
			
		if( $this->input->post("cancel")) {
			redirect("/admin/articles");			
		}

		
		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();
		
		$view_data = array( 
			'article' => $article, 
			'slot' => 'general',
			'category_select' => $this->articles_model->category_select( $article->category ),
			'status_select' => $this->articles_model->status_select( $article->status ),
			'tabs' => $this->tabs->gen_tabs(array('Article','Media'), 'Article', '/admin/articles/edit/' . $article_id)
		);
		
		$pg_data = array(
			'title' => 'Admin - Articles',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/articles/article_edit', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
	}

	function edit_media()
	{
		$article_id = $this->uri->segment(4);
		
		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();
				
		$view_data = array( 
			'title' => "Media for article: $article->title",
			'article' => $article, 
			'slot' => 'general',
			'path' => '/articles/' . $article->id,
			'next' => "/admin/articles/edit/$article->id/media",
			'tabs' => $this->tabs->gen_tabs(array('Article','Media'), 'Media', '/admin/articles/edit/' . $article->id)
		);
		
		$pg_data = array(
			'title' => 'Admin - Articles',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/media/media_tab', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
		
	}
}
