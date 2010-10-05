<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class users_model extends Model
{

  function users_model()
  {
    parent::Model();
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
  
  function add_user()
  {
    
  }
  
  function update_user()
  {
    
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