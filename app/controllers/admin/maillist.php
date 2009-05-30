<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/*
	An automated eNewsletter system will allow the Bookshelf to send regular email blasts 
	to your mailing list(s). Using categories and sub-categories, different eNewsletters 
	can be created for different groups of people, allowing you to focus the content to 
	more targeted audiences. A connection to the events calendar will allow you to feature 
	content from the events as part of each eNewsletter. Other content areas for feature 
	stories, photos and information will make up the remainder of the eNewsletter. 
	For staff, creating the eNewsletter will be as simple as cutting and pasting new content 
	or selecting existing events content from the CMS, and deploying the newsletter to one 
	or more subscriber list(s). A plain text version will also be automatically created and 
	sent alongside the graphically formatted version, for those unable to receive graphical 
	messages. eNewsletters can be scheduled to allow for email deployment at optimal 
	times. Subscription management (subscription and unsubscription) will be handled 
	through the user profile tool.
*/

class Maillist extends Controller {

	function Maillist()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
		$this->load->helper('url');
	}
	
	function index()
	{
		redirect("/admin/maillist/msg");
	}
	
	function msg()
	{
		$msgs = $this->db->get('ml_messages')->result();

		$content = array(
			'msgs' => $msgs
			);
			
		$pg_data = array(
			'title' => 'Admin - Mailing List Newsletters',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/msg_list', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	function msgadd()
	{
		$error_msg = "";

		if( $this->input->post("save")) {
			if( trim($this->input->post("subject")) != "" ) {
				unset($_POST["save"]);
				$this->db->insert('ml_messages', $_POST );
				redirect("/admin/maillist/msg");
			} else {
				$error_msg = '<p class="error">You must have a subject!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/msg");			
		}
		
		$msgs = $this->db->get('ml_messages')->result();

		$content = array(
			'msgs' => $msgs,
			'error_msg' => $error_msg
			);
			
		$pg_data = array(
			'title' => 'Admin - Mailing List Newsletters',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/msg_add', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	function msgedit()
	{
		$msg_id = $this->uri->segment(4);
		$error_msg = '';

		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			unset($_POST["save"]);
			$this->db->update('ml_messages', $_POST );
			redirect("/admin/maillist/msg");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/msg");			
		}

		$this->db->where( 'id', $msg_id );
		$msg = $this->db->get('ml_messages')->row();
		
		$content = array(
			'msg' => $msg,
			'error_msg' => $error_msg
			);
			
		$pg_data = array(
			'title' => 'Admin - Mailing List Newsletters',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/msg_edit', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	
	function lists()
	{
		$lists = $this->db->get('ml_list')->result();

		$content = array(
			'lists' => $lists
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Groups',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/list_list', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}

	function listadd()
	{
		$error_msg = '';

		if( $this->input->post("save")) {
			if( trim($this->input->post("name")) != "" ) {
				unset($_POST["save"]);
				$this->db->insert('ml_list', $_POST );
				redirect("/admin/maillist/lists");
			} else {
				$error_msg = '<p class="error">You must have a subject!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/lists");			
		}

		$content = array(
			'error_msg' => $error_msg
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Groups',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/list_add', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	function listedit()
	{
		$error_msg = '';
		$msg_id = $this->uri->segment(4);
		
		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			unset($_POST["save"]);
			$this->db->update('ml_list', $_POST );
			redirect("/admin/maillist/lists");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/lists");			
		}

		$this->db->where( 'id', $msg_id );
		$list = $this->db->get('ml_list')->row();
		
		$content = array(
			'list' => $list,
			'error_msg' => $error_msg
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Groups',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/list_edit', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}


	function subscr()
	{
		$users = $this->db->get('ml_subscr')->result();
		
		$content = array(
			'users' => $users
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Subscribers',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/subscr_list', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}

	function subscradd()
	{
		$error_msg = '';

		if( $this->input->post("save")) {
			if( trim($this->input->post("email")) != "" ) {
				unset($_POST["save"]);
				$this->db->insert('ml_subscr', $_POST );
				redirect("/admin/maillist/subscr");
			} else {
				$error_msg = '<p class="error">You must have an email!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/subscr");			
		}

		$content = array(
			'error_msg' => $error_msg
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Subscribers',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/subscr_add', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	function subscredit()
	{
		$error_msg = '';
		$msg_id = $this->uri->segment(4);
		
		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			unset($_POST["save"]);
			$this->db->update('ml_subscr', $_POST );
			redirect("/admin/maillist/subscr");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/subscr");			
		}

		$this->db->where( 'id', $msg_id );
		$list = $this->db->get('ml_subscr')->row();
		
		$content = array(
			'subscr' => $list,
			'error_msg' => $error_msg
			);
		
		$pg_data = array(
			'title' => 'Admin - Mailing List Subscribers',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/maillist/subscr_edit', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

}

