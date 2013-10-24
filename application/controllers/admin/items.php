<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include("admin_controller.php");

class Items extends Admin_Controller
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
		$this->load->model('films_model');
		$this->load->model('groups_model');
	    $this->load->model('lists_model');
	    $this->load->model('users_model');
	    $this->load->model('tag_model');
	}

    /**
     Associated article brower 
     Used in article items view
	*/
    function item_articles_browser() {
		$is_ajax = true;
        $item_id = $this->input->post('item_id');
        $item_type = $this->input->post('item_type');

		if( !$item_id) {
            return false;
        }

        switch ($item_type) {
        	case 'article':
		        $products = $this->articles_model->get_associated_articles( $item_id );
        	break;
        	case 'film':
		        $products = $this->films_model->get_associated_articles( $item_id );
		    break;
        }
        
		$view_data = array(
			'article_id' => $item_id,
			'errors' => '',
            'files' => $products
			);

		$this->load->view('admin/items/item_articles_browser', $view_data );
    }

    /**
     Associated products brower 
     Used in article items view
	*/
    function item_products_browser() {
		$is_ajax = true;
        $item_id = $this->input->post('item_id');
        $item_type = $this->input->post('item_type');

		if( !$item_id ) {
            return false;
        }

        switch ($item_type) {
        	case 'article':
		        $products = $this->articles_model->get_products( $item_id );
        	break;
        	case 'film':
		        $products = $this->films_model->get_products( $item_id );
		    break;
		}
       
		$view_data = array(
			'item_id' => $item_id,
			'errors' => '',
            'files' => $products
			);

		$this->load->view('admin/products/article_products_browser', $view_data );
    }

	/**
    	Browser callback for article items association view
    */
    function item_films_browser() {
        $item_id = $this->input->post('item_id');
        $item_type = $this->input->post('item_type');

		if( !$item_id ) {
            return false;
        }

        switch ($item_type) {
        	case 'article':
		        $events = $this->articles_model->get_films( $item_id );
        	break;
        	case 'film':
		        $events = $this->films_model->get_associated_films( $item_id );
		    break;
		}



        $view_data = array(
			'item_id' => $item_id,
			'errors' => '',
            'files' => $events
			);

		$this->load->view('admin/items/item_films_browser', $view_data );
    }

/**
    	Browser callback for article items association view
    */
    function item_events_browser() {
        $item_id = $this->input->post('item_id');
        $item_type = $this->input->post('item_type');

		if( !$item_id ) {
            return false;
        }

        switch ($item_type) {
        	case 'article':
		        $events = $this->articles_model->get_events( $item_id );
        	break;
        	case 'film':
		        $events = $this->films_model->get_events( $item_id );
		    break;
		}

        $view_data = array(
			'item_id' => $item_id,
			'errors' => '',
            'files' => $events
			);

		$this->load->view('admin/items/item_events_browser', $view_data );
    }


    /**
    Callback
    Add an article association to an article 
    */
    function addarticle()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to add article' );
        $article_id = $this->input->post('article_id');
        $item_id 	= $this->input->post('item_id');
        $item_type 	= $this->input->post('item_type');

        if (!$article_id || !$item_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($article_id) && is_numeric($item_id)) {
        	switch ($item_type) {
        		case 'article':
		            $result = $this->articles_model->add_associated_article($item_id, $article_id);
		           	break;
		        case 'film':
		        	$result = $this->films_model->add_associated_article($item_id, $article_id);
		        	break;
	        }
	        $ret['ok'] = $result;
	        if ($result === true) {
	            $ret['msg'] = 'Item added';
	        }
	    }

        echo json_encode($ret);
    }

    /** 
    Callback
    Remove an article association to an article 
    */
    function removearticle()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to remove film' );
        $item_id 	= $this->input->post('item_id');
        $associated_article_id = $this->input->post('associated_article_id');
        $item_type 	= $this->input->post('item_type');

        if (!$item_id || !$associated_article_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($associated_article_id)) {
        	switch( $item_type ) {
        		case 'article':
		            $result = $this->articles_model->remove_associated_article($item_id, $associated_article_id);
        		break;
        		case 'film':
		            $result = $this->films_model->remove_associated_article($item_id, $associated_article_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Article removed';
            }
        }

        echo json_encode($ret);
    }
    /**
    Callback
    Add a film association to an article 
    */
    function addfilm()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to add film' );
        $item_id 	= $this->input->post('item_id');
        $film_id 	= $this->input->post('film_id');
        $item_type 	= $this->input->post('item_type');

        if (!$item_id || !$film_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($film_id)) {
        	switch( $item_type ) {
        		case 'article':
		            $result = $this->articles_model->add_film($item_id, $film_id);
        		break;
        		case 'film':
		            $result = $this->films_model->add_associated_film($item_id, $film_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Film added';
            }
        }

        echo json_encode($ret);
    }

    /** 
    Callback
    Remove a film association to an article 
    */
    function removefilm()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to remove film' );
        $item_id 	= $this->input->post('item_id');
        $film_id 	= $this->input->post('film_id');
        $item_type 	= $this->input->post('item_type');

        if (!$item_id || !$film_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($film_id)) {
        	switch( $item_type ) {
        		case 'article':
		            $result = $this->articles_model->remove_film($item_id, $film_id);
        		break;
        		case 'film':
		            $result = $this->films_model->remove_associated_film($item_id, $film_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Film removed';
            }
        }

        echo json_encode($ret);
    }


    /* Callback
     * Add an item association to an article */
    function additem()
    {
        $ret = array('ok' => false, 'msg' => 'Unable to add item' );
        $item_id 	= $this->input->post('item_id');
        $product_id = $this->input->post('product_id');
        $item_type	= $this->input->post('item_type');

        if (!$item_id || !$product_id ) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($product_id)) {
        	switch($item_type) {
        		case 'article':
		            $result = $this->articles_model->add_product($item_id, $product_id);
        		break;
        		case 'film':
		            $result = $this->films_model->add_product($item_id, $product_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Product added';
            }
        }

        echo json_encode($ret);
    }
   
    /**
     * Callback
     * Remove an item association to an article / film
     */
    function removeitem()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to remove item' );
        $item_id 	= $this->input->post('item_id');
        $product_id = $this->input->post('product_id');
        $item_type 	= $this->input->post('item_type');

        if (!$item_id || !$product_id ) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($product_id)) {
        	switch($item_type) {
        		case 'article':
		            $result = $this->articles_model->remove_product($item_id, $product_id);
        		break;
        		case 'film':
		            $result = $this->films_model->remove_product($item_id, $product_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Product removed';
            }
        }

        echo json_encode($ret);
    }

    /**
    Callback
    Add an event association to an article / film
    */
    function addevent()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to add event' );
        $item_id 	= $this->input->post('item_id');
        $event_id 	= $this->input->post('event_id');
        $item_type	= $this->input->post('item_type');

        if (!$item_id || !$event_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($event_id)) {
        	switch($item_type) {
        		case 'article':
		            $result = $this->articles_model->add_event($item_id, $event_id);
        		break;
        		case 'film':
		            $result = $this->films_model->add_event($item_id, $event_id);
        		break;
        	}

            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Event added';
            }
        }

        echo json_encode($ret);
    }

    
    /** 
    Callback
    Remove an event association to an article 
    */
    function removeevent()
    {
        $ret 		= array('ok' => false, 'msg' => 'Unable to remove event' );
        $item_id 	= $this->input->post('item_id');
        $event_id 	= $this->input->post('event_id');
        $item_type	= $this->input->post('item_type');

        if (!$item_id || !$event_id) {
            $ret['msg'] = 'Missing required variable';
            echo json_encode($ret);
            exit;
        }

        if (is_numeric($item_id) && is_numeric($event_id)) {
        	switch($item_type) {
        		case 'article':
		            $result = $this->articles_model->remove_event($item_id, $event_id);
        		break;
        		case 'film':
		            $result = $this->films_model->remove_event($item_id, $event_id);
        		break;
        	}
            $ret['ok'] = $result;
            if ($result === true) {
                $ret['msg'] = 'Event removed';
            }
        }

        echo json_encode($ret);
    }
}
