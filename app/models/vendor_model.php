<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_vendor( $id )
  {
    $this->db->where( 'id', $id );
    return $this->db->get('vendors')->row();
  }

  function get_vendors( $page = 1, $limit = NULL )
  {
    if( $limit ) {
      $this->db->limit( $limit, ($page - 1) * $limit );
    }

    return $this->db->get('vendors');
  }

  function add( $data )
  {
		$this->db->insert('vendors', $data );
  }

  function update( $id, $data )
  {
    $this->db->where('id', $id );
    $this->db->update('vendors', $data );
  }
}

