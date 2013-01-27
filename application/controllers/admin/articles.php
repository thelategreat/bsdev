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
    $this->load->model('users_model');
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

    $role = $this->session->userdata('logged_user_role');

		if( $this->input->post("save") || $this->input->post("saveaddm")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('body','Body','trim|required');
			$this->form_validation->set_rules('excerpt','Excerpt','trim|required');

      if( $this->form_validation->run()) {
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('group', $this->input->post('group'), false);
				//$this->db->set('display_priority', $this->input->post('display_priority'), false);
				//$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('category', 1, false);
				$this->db->set('publish_on', $this->input->post('publish_on'));
				$this->db->set('venue', $this->input->post('venue'), false);
				$this->db->set('body', $this->input->post('body'));
				$this->db->set('excerpt', $this->input->post('excerpt'));
				//$this->db->set('tags', $this->input->post('tags'));
				$this->db->set('author', $this->input->post('author'));
	      if( $role == 'admin' || $role == 'editor') {
          $this->db->set('owner', $this->input->post('user'));
        } else {
			    $this->db->set('owner', $this->session->userdata('logged_user'));
        }
        $this->db->set('created_on', "NOW()", false);
				$this->db->set('status', 1);
        $this->db->insert("articles");

        if( $this->input->post('save')) {
          redirect("/admin/articles");
        } else {
          redirect('/admin/articles/edit/' . $this->db->insert_id() . '/media');
        }
			}
		}

		if( $this->input->post("cancel")) {
			redirect("/admin/articles");
		}

    if( $role == 'admin' || $role == 'editor ') {
      $user_select = $this->users_model->user_select(
        $this->session->userdata('logged_user'), 0, 3 );
    } else {
      $user_select = $this->session->userdata('logged_user');
    }

		$view_data = array(
			'group_select' => '<select name="group" id="group-sel">' . $this->groups_model->mk_nested_select(0,0,false) . '</select>',
			'category_select' => $this->articles_model->category_select(),
			'priority_select' => $this->articles_model->priority_select(),
			'venue_select' => $this->articles_model->venue_select(),
      'user_select' => $user_select
			);

		$this->gen_page('Admin - Essays', 'admin/articles/article_add', $view_data );
	}

	function edit()
	{
		$tab = $this->uri->segment( 5 );
		switch ($tab) {
			case false:
			case "essay":
				$this->edit_article();
			break;
			case "media":
				$this->edit_media();
			break;
			case "items":
				$this->edit_items();
			break;
			default:
				log_message('error', 'Tab not found: ' . $tab);
				show_error('Operation not found: ' . $tab);
			break;

		}
	}

	function edit_items()
	{
		$article_id = (int)$this->uri->segment(4);
		if ( !$article_id ) {
			redirect("/admin/articles");
		}

		$this->db->where( 'id', $article_id );
		$article = $this->db->get('articles')->row();

		$view_data = array(
			'title' => "Items for essay: $article->title",
			'article' => $article,
			'path' => '/articles/' . $article->id,
			'next' => "/admin/articles/edit/$article->id/items",
			'tabs' => $this->tabs->gen_tabs(array('Essay','Media', 'Items'), 'Items', '/admin/articles/edit/' . $article->id)
		);

		$this->gen_page('Admin - Items', 'admin/items/items_index', $view_data );
	}

	function edit_article()
	{
		$article_id = (int)$this->uri->segment(4);

		if( !$article_id ) {
			redirect("/admin/articles");
		}

    $role = $this->session->userdata('logged_user_role');

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
			$this->form_validation->set_rules('excerpt','Excerpt','trim|required');

      if( $this->form_validation->run()) {
				$this->db->where('id', $article_id);
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('group', $this->input->post('group'), false);
				$this->db->set('display_priority', $this->input->post('display_priority'), false);
				$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('status', $this->input->post('status'), false);
				$this->db->set('venue', $this->input->post('venue'), false);
				$this->db->set('publish_on', $this->input->post('publish_on'));
        $this->db->set('body', $this->input->post('body'));
        if( $role == 'admin' || $role == 'editor') {
          $this->db->set('owner', $this->input->post('user'));
        }
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

    if( $role == 'admin' || $role == 'editor ') {
      $user_select = $this->users_model->user_select( $article->owner, 0, 3 );
    } else {
      $user = $this->users_model->get_username( $article->owner )->row();
      $user_select = $user->firstname . ' ' . $user->lastname;
    }

    	$associated_products = $this->articles_model->get_products( $article_id );
    	$associated_events 	 = $this->articles_model->get_events( $article_id );
    	$associated = array_merge($associated_products, $associated_events);
    	
		$view_data = array(
			'article' => $article,
			'slot' => 'general',
			//'group_select' => $this->articles_model->group_select( $article->group ),
			'group_select' 		=> '<select name="group" id="group-sel">' . $this->groups_model->mk_nested_select($article->group,0,false) . '</select>',
			'category_select' 	=> $this->articles_model->category_select( $article->category ),
			'status_select' 	=> $this->articles_model->status_select( $article->status ),
			'venue_select' 		=> $this->articles_model->venue_select( $article->venue ),
			'priority_select'	=> $this->articles_model->priority_select( $article->display_priority ),
			'lists_select' 		=> $this->lists_model->lists_select(),
			'user_select' 		=> $user_select,
			'associated' 		=> $associated,
			'tabs' 				=> $this->tabs->gen_tabs(array('Essay','Media', 'Items'), 'Essay', '/admin/articles/edit/' . $article_id)
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
			'tabs' => $this->tabs->gen_tabs(array('Essay','Media', 'Items'), 'Media', '/admin/articles/edit/' . $article->id)
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

    /* Callback
     * Add an event association to an article */
    function addevent()
    {
        $ret = array('ok' => false, 'msg' => 'Unable to add event' );
        $article_id = $this->input->post('article_id');
        $event_id = $this->input->post('event_id');

        if (!$article_id || !$event_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($article_id) && is_numeric($event_id)) {
            $result = $this->articles_model->add_event($article_id, $event_id);
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Event added';
            }
        }

        echo json_encode($ret);
    }
    
    /* Callback
     * Add an event association to an article */
    function removeevent()
    {
        $ret = array('ok' => false, 'msg' => 'Unable to remove event' );
        $article_id = $this->input->post('article_id');
        $event_id = $this->input->post('event_id');

        if (!$article_id || !$event_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($article_id) && is_numeric($event_id)) {
            $result = $this->articles_model->remove_event($article_id, $event_id);
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Event removed';
            }
        }

        echo json_encode($ret);
    }

    /* Callback
     * Add an item association to an article */
    function additem()
    {
        $ret = array('ok' => false, 'msg' => 'Unable to add item' );
        $article_id = $this->input->post('article_id');
        $product_id = $this->input->post('product_id');

        if (!$article_id || !$product_id ) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($article_id) && is_numeric($product_id)) {
            $result = $this->articles_model->add_product($article_id, $product_id);
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Product added';
            }
        }

        echo json_encode($ret);
    }
   
    /* Callback
     * Add an item association to an article */
    function removeitem()
    {
        $ret = array('ok' => false, 'msg' => 'Unable to remove item' );
        $article_id = $this->input->post('article_id');
        $product_id = $this->input->post('product_id');

        if (!$article_id || !$product_id ) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($article_id) && is_numeric($product_id)) {
            $result = $this->articles_model->remove_product($article_id, $product_id);
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Product removed';
            }
        }

        echo json_encode($ret);
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
