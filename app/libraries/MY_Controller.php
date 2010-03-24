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
			'sidebar_nav' => $this->get_sidebar_nav(),
			'sidebar' => $this->get_sidebar(),
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
		
		$res = $this->media_model->get_media_for_path('/pages/1', 'top');		
		$data['top_feature'] = count($res) ? '/media/' . $res[0]['url'] : '/media/logos/no_image.jpg';		
		
		$res = $this->media_model->get_media_for_path('/pages/1', 'left');		
		$data['left_feature'] = count($res) ? '/media/' . $res[0]['url'] : '/media/logos/no_image.jpg';		
		
		$res = $this->media_model->get_media_for_path('/pages/1', 'mid');		
		$data['mid_feature'] = count($res) ? '/media/' . $res[0]['url'] : '/media/logos/no_image.jpg';		
		
		$res = $this->media_model->get_media_for_path('/pages/1', 'right');		
		$data['right_feature'] = count($res) ? '/media/' . $res[0]['url'] : '/media/logos/no_image.jpg';		

		//$data['mid_feature'] = '/i/features/featured_middle.jpg';		
		
		return $data;			
	}

	/**
 	 *
 	 */
	protected function get_cal_date()
	{
		$today = time();
		$when = $this->uri->segment(3);
		
		if($this->uri->segment(1) == 'events' ) {
			switch( $this->uri->segment(2)) {
				case 'details':
				$event = $this->event_model->get_event($when);
				if( $event->num_rows()) {
					$today = strtotime($event->row()->dt_start);
				}
				break;
				default:
				if( $when ) {
					if( $when == 'today') {
						$today = time();
					}				
					else if( $when == 'tomorrow') {
						$today = strtotime("+1 days");
					}				
					else if(strlen($when) > 3 ) {
						$mo = substr($when,0,3);
						$da = substr($when,3);
						$today = strtotime($da . " " . $mo);
					}
				}						
			}
		}
		return $today;
	}
	
	/**
 	 *
 	 */
	protected function get_sidebar()
	{		
		$today = $this->get_cal_date();
		$filter = array('day' => date('d',$today), 'month' => date('m',$today), 'year' => date('Y',$today), 'view' => 'day');
		$items = $this->event_model->get_events( $filter );

		return $this->load->view('home/sidebar', array('events'=>$items), true );
	}
	
	/**
 	 *
 	 */
	protected function get_sidebar_nav()
	{
		$today = $this->get_cal_date();
		$nextday = strtotime("+2 days");
		$when = date('Md', $today);
		
		$sb_nav = array(
			'dates' => array('Today','Tomorrow',date('M',$today) . ' ' . date('d',$today)),
			'when' => $when,
			'today' => date('M',$today) . date('d',$today) );
		
		return $this->load->view('events/sidebar_nav', $sb_nav, true );
	}
	
}