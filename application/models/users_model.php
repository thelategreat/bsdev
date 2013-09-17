<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class users_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_users( $filter = NULL, $role = NULL )
	{
		if( count($filter)) {
			foreach( $filter as $q ) {
				$this->db->or_like('username', $q );
				$this->db->or_like('firstname', $q );
				$this->db->or_like('lastname', $q );
			}
		}
		
		if( $role ) {
			$this->db->where( "users.role_id = (SELECT id FROM user_roles WHERE role = '$role')", NULL, FALSE);
		}
		
		$this->db->from('users');
		$this->db->join('user_roles', 'user_roles.id = users.role_id');
		$this->db->select('users.id, users.username, users.firstname, users.lastname, user_roles.role, users.email, users.active, users.last_login');
		$this->db->order_by( 'users.username' );
		return $this->db->get();
	}

  
  function get_users_old( $filter = NULL )
  {
    $query =<<<EOF
SELECT u.id, u.username, u.firstname, u.lastname, u.active, u.last_login, u.email, r.role as role 
  FROM users as u, user_roles as r 
  WHERE u.role_id = r.id  
  ORDER BY u.username    
EOF;

    return $this->db->query($query);
    
  }
  
  function get( $id )
  {
    
  }

  function get_username( $username )
  {
    $this->db->where('username', $username );
    return $this->db->get('users');
  }

  function get_user_by_id( $id)
  {
    $this->db->where('id', $id);
    $result = $this->db->get('users');
    if ($result) return $result->row();

    return false;
  }


  function add_user()
  {
    
  }
  
  function update_user()
  {
    
  }

  function user_select( $username = '', $user_id = 0, $role_id = 0 ) 
  {
    $s = '<select name="user" id="user-select">';
    $q = 'SELECT id, username, firstname, lastname FROM users';
    if( $role_id > 0 ) {
      $q .= ' WHERE role_id <> 3'; // . (int)$role_id;
    }
    $q .= ' ORDER BY lastname';
    $res = $this->db->query( $q );
    foreach( $res->result() as $row ) {
      $s .= '<option value="' . ($user_id > 0 ? $row->id : $row->username) . '"';
      if( $user_id > 0 && $row->id == $user_id ) {
        $s .= ' selected ';
      }
      if( $user_id == 0 && !strcmp($row->username, $username) ) {
        $s .= ' selected ';
      }
      $s .= '>' . $row->firstname . ' ' . $row->lastname . ' (' . $row->username . ')';
      $s .= '</option>';
    }
    $s .= '</select>';
    return $s;
  }

  function role_select( $role_id = '' )
  {
    $role_select = '<select name="role_id">';
    $roles = $this->db->get('user_roles');
    foreach( $roles->result() as $row ) {
      $role_select .= '<option value="' . $row->id . '"'; 
      if( $row->id == $role_id ) {
        $role_select .= ' selected ';
      }
      $role_select .= '>' . $row->role . '</option>';
    }
    $role_select .= '</select>';
    return $role_select;
  }
  
}
