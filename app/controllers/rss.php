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
	}
	
	function index()
	{
		header("Content-Type: application/xml; charset=ISO-8859-1");
		echo $this->gen_feed();		
	}
	
	private function gen_feed()
	{
		$s = '<?xml version="1.0" encoding="UTF-8" ?>';
		$s .= '<rss version="2.0">';
		$s .= '  <channel>';
		$s .= '    <title>Bookshelf RSS</title>';
		$s .= '    <link>'.base_url().'</link>';
		$s .= '    <description>The Bookshelf</description>';
		$s .= '    <language>English</language>';
		
		$res = $this->articles_model->get_published_article_list( 0, 20 );
		
		foreach( $res->result() as $article ) {
			$s .= '		<item>';
			$s .= '      <title>' . htmlspecialchars($article->title) . '</title>';
			$s .= '      <link>'.base_url().'/article/view/' . $article->id . '</link>';
			$s .= '      <description>'.$article->excerpt.'</description>';
			$s .= '		</item>';			
		}
		
		$s .= '  </channel>';
		$s .= '</rss>';
		return $s;
	}
}