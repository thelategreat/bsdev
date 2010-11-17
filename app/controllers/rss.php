<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * RSS 2.0
 */

class Rss extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->load->model('articles_model');
		$this->load->helper('url');
		$this->load->helper('rss');
	}
	
	function index()
	{
		echo $this->gen_feed();		
	}
	
	private function gen_feed()
	{
		$feed = new Feeder( Feeder::$RSS2 );
		$feed->setTitle('Bookshelf RSS');
		$feed->setLink(base_url());
		$feed->setDescription('The Bookshelf');

		$res = $this->articles_model->get_published_article_list( 0, 20 );
		
		foreach( $res->result() as $article ) {
			$item = $feed->addItem(
						$article->title, 
						base_url().'/article/view/'.$article->id, 
						'now2', 
						$article->excerpt);
		}
		
		$feed->generate();

	}	
}