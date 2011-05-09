<?php

/**
 * Base controller class for the site
 *
 * Out any common functionality in here
 *
 * @package default
 * @author J Knight
 */
class MY_Controller extends CI_Controller
{
	/**
	 * CTOR
	 *
	 */
	function __construct() 
	{
		parent::__construct();
	}
	
	protected function get_page_data( $title, $css_name, $section = 0 )
	{
    $this->load->model('pages_model');
    
    $section = (int)$section;
		$groups = $this->groups_model->get_menu_tree( $section );
    $pages = $this->pages_model->get_pages_tree( );

		$pg_data = array(
			'page_title' => $title,
			'css_name' => $css_name,
			'section' => $section,
			'groups' => $groups,
      'debug' => $groups,
			'main_content_nav' => $this->main_menu_pages( $section, $pages[0]->children ),
			'style' => '/css/screen.css',
			'content' => '',
			'cart' => $this->cart,
			'sidebar_nav' => $this->get_sidebar_nav(),
			'sidebar_left' => $this->get_sidebar('left', $groups, $section ),
			'sidebar_right' => $this->get_sidebar('right', $groups, $section ),
			'ad_footer' => $this->get_sidebar('ad_footer', $groups ),
			'footer' => $this->load->view('layouts/standard_footer', array('pages' => $pages), true )
		);
		
		return $pg_data;
	}

  // main menu if we are using pages for nav
  function main_menu_pages( $section, $pages, $home = true )
  {
    if( $home ) {
      $s = '<ul class="dropdown">';
      $s .= '<li><a href="/" ' . ($section == 0 ? ' class="selected"' : '') . '>Home</a></li>';
    } else {
      $s = '<ul>';
    }
    foreach( $pages as $page ):
			if( $page->page_type == 'link') {
				$s .= "<li><a href='" . $page->body . "'>$page->title</a>";
			} else {
      	$s .= "<li><a href='/page/view/$page->id'>$page->title</a>";
			}
      if( count($page->children) > 0 ) {
        $s .= $this->main_menu_pages( $section, $page->children, false );
      }
      $s .= "</li>";
    endforeach;
    $s .= '</ul>';
    return $s;
  }

  // main menu if we are using groups for nav
  function main_menu_groups( $section, $groups )
  {
    $s = '<ul>';
    $s .= '<li><a href="/" ' . ($section == 0 ? ' class="selected"' : '') . '>Home</a></li>';
    foreach( $groups as $group ):
      $s .= '<li><a href="/home/section/'. $group->id .'" '. ($section == $group->id ? ' class="selected"' : '') .'>'. $group->name .'</a></li>';
    endforeach;

    $s .= '</ul>';
    return $s;
  }

	function main_nav_arrows( $back = NULL, $fwd = NULL )
	{
		$s = '';
		if( $back )
			$s .= '<a id="left_arrow" href="'.$back.'">&laquo; Previous</a>';
		if( $fwd )
			$s .= '<a id="right_arrow" href="'.$fwd.'">Next &raquo;</a>';
		return $s;
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
	protected function get_sidebar( $which, $groups, $section = 0 )
	{		
		$today = $this->get_cal_date();
		$filter = array('day' => date('d',$today), 'month' => date('m',$today), 'year' => date('Y',$today), 'view' => 'day');
		$items = $this->event_model->get_events( $filter );
		
		if( $which == 'right' ) {
			$ads = $this->ads_model->get_ads_for_section( 'home' );
			return $this->load->view('home/sidebar_right', array('events'=>$items, 'ads' => $ads->result(), 'section' => $section), true );			
		} else if( $which == 'left' ) {
			$parents = $this->groups_model->get_parents( $section );
			return $this->load->view('home/sidebar_left', array('events'=>$items, 'groups' => $groups, 'section' => $section ), true );
		} else if( $which == 'ad_footer' ) {
			$ads = $this->ads_model->get_ads_for_section( 'home' );
			return $this->load->view('home/ad_footer', array('events'=>$items, 'ads' => $ads->result()), true );
		}
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