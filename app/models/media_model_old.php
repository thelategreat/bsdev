<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class media_model extends Model
{

  function media_model()
  {
    parent::Model();
  }

	
	function add_upload( $path, $section, $user )
	{
		$this->db->set('filepath', $path );
		$this->db->set('user', $user );
		$this->db->set('section', $section );
		$this->db->set('created_on', 'NOW()', false );
		$this->db->set('updated_on', 'NOW()', false );
		
		$this->db->insert('media');
		
	}

	function remove_upload( $path, $section )
	{
		$this->db->where('filepath', $path);
		$this->db->where('section', $section);
		$this->db->delete('media');
	}

	function files_for_section( $section )
	{
		$this->db->select('filepath, user');
		$this->db->where('section', $section);		
		return $this->db->get('media');
	}
}