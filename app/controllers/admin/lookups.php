<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Lookups extends Admin_Controller 
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
		
		// table_name => (title, foreign_field, checks = ('table.field',...))
		$this->lookup = array(
			'event_categories' => array('Event Categories','category', array('events.category')),
			'event_audience' => array('Event Audience','audience', array('events.audience')),
			'article_categories' => array('Article Categories','category', array('articles.category')),
			'article_groups' => array('Article Groups','group', array('articles.group')),
			'article_statuses' => array('Article Status','status', array('articles.status'))
		);
	}
	
	function index()
	{
			$view_data = array( 
				'lookups' => $this->lookup
				);

			$this->gen_page('Admin - Lookups', 'admin/lookups', $view_data );
	}
	
	function get_items()
	{
		$table = $this->input->post('item');
		
		if( $table ) {
			$res = $this->db->get($table);
			$s = '<table>';
			foreach( $res->result_array() as $row ) {
				$val = $row[$this->lookup[$table][1]]; 
				$s .= '<tr>';
				$s .= '<td class="inplace-edit" id="item_'.$row['id'].'">' . $val . '</td>';
				$s .= '<td><a href="#" onclick="remove_item('.$row['id'].')" title="delete: '. $val . '"><img src="/img/admin/cross.png" /></a>';
				$s .= '</tr>';
			}
			$s .= '</table>';
			echo $s;			
		} else {
			echo '??';
		}		
	}
	
	function edititem()
	{
		$table = $this->input->post('item');
		$id = $this->input->post('id');
		$val = $this->input->post('value');

		$res = array('ok' => false, 'msg' => 'Not saved.');
				
		if( $table && $id && $val ) {
			$tmp = explode('_', $id );
			$id = $tmp[1];
			$field = $this->lookup[$table][1];
			
			$query = $this->db->update_string($table, array($field=>$val),"id = $id");
			echo $query;
			$this->db->query($query);
			if( $this->db->affected_rows() == 1 ) {
				$res['ok'] = true;
				$res['msg'] = 'Ok';
			}
		}
	}
	
	function additem()
	{
		$table = $this->input->post('item');
		$value = $this->input->post('value');
		
		$res = array('ok' => false, 'msg' => 'Not added.');
		
		if( $table && $value ) {
			$field = $this->lookup[$table][1];
			
			// check for dupes
			$isdupe = false;
			$field = $this->lookup[$table][1];
			$result = $this->db->query("SELECT id FROM $table WHERE $field = '$value'");
			if( $result->num_rows() > 0 ) {
				$isdupe = true;
			}
			
			if( !$isdupe ) {
				$this->db->query($this->db->insert_string($table, array($field=>$value)));
				if( $this->db->affected_rows() == 1 ) {
					$res['id'] = $this->db->insert_id();
					$res['value'] = $value;
					$res['ok'] = true;
					$res['msg'] = 'Ok';
				}
			} else {
				$res['msg'] .= ' Duplicate.';
			}
		}
		
		echo json_encode( $res );
	}
	
	function rmitem()
	{
		$table = $this->input->post('item');
		$id = $this->input->post('id');

		if( !$table ) {
			$table = $this->uri->segment(4);
		}
		if( !$id ) {
			$id = $this->uri->segment(5);
		}

		$res = array('ok' => false, 'msg' => 'Delete item failed');
		
		if( $table && $id ) {
			// check for references, first
			$checkok = true;
			$checks = $this->lookup[$table][2];
			foreach( $checks as $check ) {
				$tmp = explode('.', $check);
				$query = "SELECT count(*) as cnt FROM $tmp[0] WHERE $check = $id";
				#echo $query;
				$result = $this->db->query($query)->row();
				if( $result->cnt > 0 ) {
					$checkok = false;
					break;
				}
			}
						
			if( $checkok ) {
				$this->db->delete( $table, array('id' => $id ));
				if( $this->db->affected_rows() > 0 ) {
					$res['ok'] = true;
					$res['msg'] = 'Ok';
				}
			} else {
				$res['msg'] = 'Item is referenced elsewhere, unable to delete';
			}
		}
		
		echo json_encode( $res );		
	}
	
}		