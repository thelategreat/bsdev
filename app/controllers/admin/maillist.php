<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/*
http://fireblast.sumeffect.com/

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

include("admin_controller.php");

class Maillist extends Admin_Controller {

	function Maillist()
	{
		parent::__construct();
		$this->load->helper('misc');
		$this->load->model('maillist_model');
	}
	
	function index()
	{
		redirect("/admin/maillist/msg");
	}
	
	// -----------------
	// T E M P L A T E S
	// -----------------
	function tmpl()
	{
		$tmpls = $this->maillist_model->get_templates()->result();

		$content = array(
			'tmpls' => $tmpls
			);
			
		$this->gen_page('Admin - Mailing List Templates', 'admin/maillist/tmpl_list', $content );
	}
	
	function tmpladd()
	{
		$error_msg = "";

		if( $this->input->post("save")) {
				unset($_POST["save"]);
				$this->maillist_model->add_template($_POST);
				redirect("/admin/maillist/tmpl");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/tmpl");			
		}
		
		$content = array(
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Mail List - Add Template', 'admin/maillist/tmpl_add', $content );		
	}
	
	function tmpledit()
	{
		$id = $this->uri->segment(4);
		$error_msg = '';

		if( $this->input->post("save")) {
			unset($_POST["save"]);			
			$this->form_validation->set_rules('name', 'Name', 'trim|required' );
			if( $this->form_validation->run() ) {			
				$this->maillist_model->update_template($_POST );
				redirect("/admin/maillist/tmpl");
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/tmpl");			
		}

		$tmpl = $this->maillist_model->get_template( $id )->row();
		
		$content = array(
			'tmpl' => $tmpl,
			'error_msg' => $error_msg
			);
			
		$this->gen_page('Admin - Mailing List - Edit Template',
			'admin/maillist/tmpl_edit', $content );
	}
	
	function tmplrm()
	{
		$id = $this->uri->segment(4);
		
		$this->db->where('id', $id );
		$this->db->delete('ml_templates');
		redirect('/admin/maillist/tmpl');
	}
	
	// ---------------
	// M E S S A G E S
	// ---------------
	
	function msg()
	{
		$msgs = $this->maillist_model->get_messages()->result();

		$content = array(
			'msgs' => $msgs
			);
		
		$this->gen_page('Admin - Mailing List Newsletters', 'admin/maillist/msg_list', $content );
	}

	function msgadd()
	{
		$error_msg = "";

		if( $this->input->post("save")) {
			if( trim($this->input->post("subject")) != "" ) {
				unset($_POST["save"]);
				$this->maillist_model->add_message($_POST);
				redirect("/admin/maillist/msg");
			} else {
				$error_msg = '<p class="error">You must have a subject!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/msg");			
		}
		
		$content = array(
			'ml_lists' => $this->maillist_model->get_mllist_select(),
			'error_msg' => $error_msg
			);
			
		$this->gen_page('Admin - Mailing List Newsletters', 'admin/maillist/msg_add', $content );
			
	}

	function msgedit()
	{
		$msg_id = $this->uri->segment(4);
		$error_msg = '';

		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			$send_on = $this->input->post("send_on");
			$send_at = $this->input->post("send_at");
			unset($_POST["save"]);
			unset($_POST["template"]);
			// FIXME this default date thing is messy
			if( $_POST["send_on"] == "" ) {
				$_POST["send_on"] = "0000-00-00";
				$_POST["send_at"] = "00";
			}
			$_POST["send_on"] = $_POST["send_on"] . " " . $_POST["send_at"] . ":00:00";
			unset($_POST["send_at"]);
			
			$this->form_validation->set_rules('subject', 'Subject', 'trim|required' );
			
			if( $this->form_validation->run() ) {			
				$this->maillist_model->update_message($_POST );
				redirect("/admin/maillist/msg");
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/msg");			
		}

		$msg = $this->maillist_model->get_message( $msg_id )->row();

		$dt = explode(' ', $msg->send_on );
		$send_at = '<option value="2">2:00 am</option>';
		$send_on = $dt[0];
		if( $send_on == '0000-00-00') {
			$send_on = '';
		}
		
		$content = array(
			'msg' => $msg,
			'ml_lists' => $this->maillist_model->get_mllist_select($msg->ml_list_id),
			'send_on' => $send_on,
			'send_at' => $send_at,
			'template_list' => $this->maillist_model->get_template_select(),
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Mailing List Newsletters', 'admin/maillist/msg_edit', $content );	
	}

	// ---------
	// L I S T S
	// ---------
	function lists()
	{
		$lists = $this->db->get('ml_list')->result();

		$content = array(
			'lists' => $lists
			);
		
		$this->gen_page('Admin - Mailing Lists', 'admin/maillist/list_list', $content );
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
		
		$this->gen_page('Admin - Mailing List Groups', 'admin/maillist/list_add', $content );
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
		
		$this->gen_page('Admin - Mailing List Groups', 'admin/maillist/list_edit', $content );
	}

	// ---------------------
	// S U B S C R I B E R S
	// ---------------------
	function subscr()
	{
		$query = '';
		
		if( $this->input->post("query")) {
			$query = $this->input->post('query');
			$this->db->like('email', $query);
			$this->db->or_like('fullname', $query);
		}
		
		$users = $this->db->get('ml_subscr')->result();
		
		$content = array(
			'users' => $users,
			'query' => $query
			);
		
		$this->gen_page('Admin - Mailing List Subscribers', 'admin/maillist/subscr_list', $content );
		
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
		
		$this->gen_page('Admin - Mailing List Subscribers', 'admin/maillist/subscr_add', $content );
	}

	function subscredit()
	{
		$error_msg = '';
		$msg_id = $this->uri->segment(4);
		
		if( $this->input->post("save")) {
			$id = $this->input->post('id');
			unset($_POST["save"]);
			unset($_POST["lists"]);
			
			// deal with the map
			// TODO: make this a function 'cos this sucks
			if( isset($_POST["subscriptions"])) {
				$subscr = $_POST["subscriptions"];
				unset($_POST["subscriptions"]);
				$this->db->where('subscr_id', $id );
				$this->db->delete('ml_subscr_list_map');
				foreach($subscr as $sub) {
					$this->db->insert('ml_subscr_list_map', array('subscr_id' => $id, 'list_id' => $sub));
				}
			}
			
			$this->db->where('id', $id);
			$this->db->update('ml_subscr', $_POST );
			redirect("/admin/maillist/subscr");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/maillist/subscr");			
		}
		
		$subs = $this->db->query("select ml_list.id, ml_list.name FROM ml_list, ml_subscr_list_map WHERE ml_subscr_list_map.list_id = ml_list.id AND ml_subscr_list_map.subscr_id = " . intval($msg_id));
		$subscriptions = array();
		foreach( $subs->result() as $row ) {
			$subscriptions[$row->id] = $row->name;			
		}

		$lists = $this->db->get('ml_list');
		$avail_lists = array();
		foreach( $lists->result() as $row ) {
			if( !isset($subscriptions[$row->id])) {
				$avail_lists[$row->id] = $row->name;
			}
		}
		
		$this->db->where( 'id', $msg_id );
		$list = $this->db->get('ml_subscr')->row();
		
		
		$content = array(
			'subscr' => $list,
			'lists' => $avail_lists,
			'subs' => $subscriptions,
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Mailing List Subscribers', 'admin/maillist/subscr_edit', $content );		
	}

	/* export the subscriber list */
	function subscrex()
	{
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="maillist.csv"');
		
		$ra = array();
		
		$res = $this->db->get("ml_subscr");
		foreach($res->result() as $row ) {
			$ra[] = array($row->email,$row->fullname);
		}
		
		ob_start();
		$f = fopen("php://output", "w") or show_error("Can't open output");
		foreach( $ra as $line ) {
			if( !fputcsv($f, $line)) {
				show_error("Can't write");
			}
		}
		fclose($f) or show_error("Can't close output");
		$str = ob_get_contents();
		ob_end_clean();
		
		echo $str;
	}

	/* import subscriber list */
	function subscrim()
	{
		$error_msg = '';
				
		if( $this->input->post('import')) {
			$tmp_file = tempnam('/tmp', 'FOO');
			if( move_uploaded_file($_FILES['userfile']['tmp_name'], $tmp_file) ) {
				$fp = fopen( $tmp_file, 'r' );
				$count = 0;
				$good = 0;
				$inserted = 0;
				$mapped = 0;
				if( $fp ) {
					while(($ra = fgetcsv($fp, 5000, ",")) !== FALSE ) {
						if( count($ra) > 1 and validEmail($ra[0]) ) {
							$good++;
							$res = $this->db->query("SELECT * FROM ml_subscr WHERE email = " . $this->db->escape($ra[0]));
							if( $res->num_rows() == 0 ) {
								$this->db->insert('ml_subscr', array('email' => $ra[0], 'fullname' => $ra[1]));
								$inserted++;
							}
							
							// list signup (mapping)
							if( $this->input->post("list")) {
								// the list id
								$list_id = $this->input->post("list");
								// get the subscr id
								$this->db->where('email', $ra[0] );
								$subscr = $this->db->get('ml_subscr')->row();
								// find this in the map
								$this->db->where('subscr_id', $subscr->id );
								$this->db->where('list_id', $list_id );
								$res = $this->db->get('ml_subscr_list_map');
								// hook it up if it's not already there
								if( $res->num_rows() == 0 ) {
									$mapped++;
									$this->db->insert('ml_subscr_list_map',array('subscr_id' => $subscr->id, 'list_id' => $list_id));
								}
							}
							
						} // if count($ra)
						$count++;
					}
					fclose( $fp );
				}
				unlink( $tmp_file );
				$error_msg = "<p class='success'>Processed $good out of $count lines. Inserted $inserted addresses and mapped $mapped addresses to a list.</p>";
			} else {
				$error_msg = '<p class="error">Unable to process file. Server error.</p>';
			}
		}
								
		$content = array(
			'lists' => $this->maillist_model->get_mllist_select(),
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Import Subscriber List', 'admin/maillist/subscr_import', $content );
	}


	function blacklist()
	{
		$error_msg = '';
		
		if( $this->input->post("email")) {
			$this->db->insert('ml_blacklist', array('email' => $this->input->post("email")));
		}
		
		if( $this->uri->segment(4) == "rm" ) {
			$id = $this->uri->segment(5);
			if( $id ) {
				$this->db->where("id", $id);
				$this->db->delete("ml_blacklist");
			}
		}
		
		$list = $this->db->get('ml_blacklist')->result();
		
		$content = array(
			'blacklist' => $list,
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Black List', 'admin/maillist/bl_list', $content );
		
	}

	function logs()
	{
		$error_msg = '';
				
		$content = array(
			'logs' => '',
			'error_msg' => $error_msg
			);
		
		$this->gen_page('Admin - Mail Logs', 'admin/maillist/logs_list', $content );		
	}


	function ajxtmpl()
	{
		$txt = '';
		$id = $this->input->post('id');
		if( $id ) {
			$this->db->where( 'id', $id );
			$row = $this->db->get('ml_templates')->row();
			if( $row )
				$txt = $row->tmpl_text;
		}
		
		header('Cache-control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		$res[] = array('tmpl' => $txt );
		echo json_encode($res);
		exit();
	}
}

