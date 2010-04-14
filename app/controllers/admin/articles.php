<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Articles extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$articles = $this->articles_model->get_article_list();
		
		$view_data = array( 
			'articles' => $articles 
			);
		
		$this->gen_page('Admin - Articles', 'admin/articles/article_list', $view_data );
	}
	
	function add()
	{
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			$this->form_validation->set_rules('author','Author','trim|required');
			if( $this->form_validation->run()) {
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('publish_on', $this->input->post('publish_on'));
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				$this->db->set('tags', $this->input->post('tags'));
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
			'category_select' => $this->articles_model->category_select(),
			);
		
		$this->gen_page('Admin - Articles', 'admin/articles/article_add', $view_data );
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
			$this->form_validation->set_rules('author','Author','trim|required');
			if( $this->form_validation->run()) {
				$this->db->where('id', $article_id);
				$this->db->set('title', $this->input->post('title'));
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
		
		$this->gen_page('Admin - Articles', 'admin/articles/article_edit', $view_data );
	}

	function edit_media()
	{
		$article_id = $this->uri->segment(4);
		
		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();
				
		$view_data = array( 
			'title' => "Media for article: $article->title",
			'article' => $article, 
			'path' => '/articles/' . $article->id,
			'next' => "/admin/articles/edit/$article->id/media",
			'tabs' => $this->tabs->gen_tabs(array('Article','Media'), 'Media', '/admin/articles/edit/' . $article->id)
		);
		
		$this->gen_page('Admin - Articles', 'admin/media/media_tab', $view_data );		
	}
}
