<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Ads extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('ads_model');
	}
	
	function index()
	{
		$page_size = $this->config->item('list_page_size');
		$page = 1;

		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}
		
		$query = 'search...';
		
		$ads = $this->ads_model->get_ad_list( NULL, $page, $page_size );
		
		// pagination
		$next_page = '';
		$prev_page = '';
		if( $page > 1 ) {
			$prev_page = "<a class='small' href='/admin/ads/index/".($page-1)."'>⇐ prev</a>";
		}
		if( $ads->num_rows() == $page_size ) {
			$next_page = "<a class='small' href='/admin/ads/index/".($page+1)."'>next ⇒</a>";
		}

		
		$view_data = array( 
			'ads' => $ads,
			'next_page' => $next_page,
			'prev_page' => $prev_page,
			'query' => $query
			);
		
		$this->gen_page('Admin - Ads', 'admin/ads/ad_list', $view_data );
		
	}
	
	function add()
	{
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('start_date','Start Date','trim|required');
			$this->form_validation->set_rules('end_date','End Date','trim|required');
			if( $this->form_validation->run()) {
				$this->db->set('title', $this->input->post('title'));
				//$this->db->set('group', $this->input->post('group'), false);
				//$this->db->set('category', $this->input->post('category'), false);
				$this->db->set('start_date', $this->input->post('start_date'));
				$this->db->set('end_date', $this->input->post('end_date'));
				$this->db->set('url', $this->input->post('url'));
				$this->db->set('clicks', 0, false);
				$this->db->set('owner', $this->session->userdata('logged_user'));
				$this->db->set('created_on', "NOW()", false);
				$this->db->insert("ads");
				redirect("/admin/ads");
			}
		}	
			
		if( $this->input->post("cancel")) {
			redirect("/admin/ads");			
		}
		
		$view_data = array(
			);
		
		$this->gen_page('Admin - Ads', 'admin/ads/ad_add', $view_data );		
	}
	
	function edit()
	{
		$tab = $this->uri->segment( 5 );
		if( $tab && $tab == "media") {
			$this->edit_media();			
		} else {
			$this->edit_ad();
		}
	}

	function edit_ad()
	{
		$ad_id = (int)$this->uri->segment(4);

		if( !$ad_id ) {
			redirect("/admin/ads");			
		}

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}

		// ------------
		// U P D A T E
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('start_date','Start Date','trim|required');
			$this->form_validation->set_rules('end_date','End Date','trim|required');
			if( $this->form_validation->run()) {
				$this->db->where('id', $ad_id);
				$this->db->set('title', $this->input->post('title'));
				$this->db->set('start_date', $this->input->post('start_date'));
				$this->db->set('end_date', $this->input->post('end_date'));
				$this->db->set('url', $this->input->post('url'));
				$this->db->update("ads");
				redirect("/admin/ads");
			}
		}	

		// ------------
		// D E L E T E
		if( $this->input->post("rm")) {
			$this->db->where('id', $ad_id);
			$this->db->delete('ads');
			$this->db->where('path', "/ads/$ad_id");
			$this->db->delete('media_map');
			redirect("/admin/ads");			
		}
			
		// -----------
		// C A N C E L
		if( $this->input->post("cancel")) {
			redirect("/admin/ads");			
		}

		
		$this->db->where( 'id', $ad_id );
		$ad = $this->db->get('ads')->row();
		
		$view_data = array( 
			'ad' => $ad, 
			'slot' => 'general',
			'tabs' => $this->tabs->gen_tabs(array('Ad','Media'), 'Ad', '/admin/ads/edit/' . $ad_id)
		);
		
		$this->gen_page('Admin - Ads', 'admin/ads/ad_edit', $view_data );
		
	}

	function edit_media()
	{
		$ad_id = (iint)$this->uri->segment(4);
		
		if( !$ad_id ) {
			redirect("/admin/ads");			
		}
		
		$this->db->where( 'id', $ad_id );
		$ad = $this->db->get('ads')->row();
		if( !$ad ) {
			redirect("/admin/ads");						
		}		
				
				
		$view_data = array( 
			'title' => "Media for Ad: $ad->title",
			'ad' => $ad, 
			'path' => '/ads/' . $ad->id,
			'next' => "/admin/ads/edit/$ad->id/media",
			'tabs' => $this->tabs->gen_tabs(array('Ad','Media'), 'Media', '/admin/ads/edit/' . $ad->id)
		);
		
		$this->gen_page('Admin - Ads', 'admin/media/media_tab', $view_data );		
	}

	
}
