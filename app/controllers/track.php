<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * Click tracker
 */

class Track extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('ads_model');
		$this->load->library('user_agent');
		$this->load->helper('url');
	}
	
	function _remap( )
	{
		$id = $this->uri->segment(2);
		
		if( !$id ) {
			redirect('/');
		}
		
		$ad = $this->ads_model->get_ad((int)$id);
		if( $ad ) {
			// we have an ad
			$ad = $ad->row();
			// we have a url
			if( strlen(trim($ad->url))) {
				// we have a referrer
				if( $this->agent->referrer()) {
					// and the referrer is us
					if( base_url() == $this->agent->referrer() ) {
						// register a click
						$this->ads_model->register_click( (int)$id );
					}
				}
				// send them along
				redirect($ad->url);				
			}
		}
		// hmmm
		redirect('/');
	}
	
}