<?php

/**
 * Base controller class for the site
 *
 * Out any common functionality in here
 *
 * @package default
 * @author J Knight
 */
class MY_Controller extends Controller
{
	/**
	 * CTOR
	 *
	 */
	function __construct() 
	{
		parent::__construct();	
	}
	
	protected function get_page_data( $title, $css_name )
	{
		$featured = $this->get_featured();
		
		$pg_data = array(
			'page_title' => $title,
			'css_name' => $css_name,
			'main_content_nav' => '<ul id="main_content_nav"><li></li></ul>',
			'style' => '/css/screen.css',
			'section' => '',
			'content' => '',
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'featured_top' => $this->load->view('home/featured_top', $featured, true),
			'featured_bottom' => $this->load->view('home/featured_bottom', $featured, true),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		
		return $pg_data;
	}
	
	/**
	 * We have these on every page
	 *
	 * @return void
	 */
	protected function get_featured()
	{
		$data = array();
		$res = $this->media_model->files_for_path('pages/1');		
		$data['top_feature'] = '/i/features/top_feature.jpg';		
		$data['left_feature'] = '/i/features/featured_left.jpg';		
		$data['mid_feature'] = '/i/features/featured_middle.jpg';		
		$data['right_feature'] = '/i/features/featured_right.jpg';	
		
		return $data;			
	}
	
}