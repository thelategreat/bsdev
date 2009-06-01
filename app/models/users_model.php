<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class users_model extends Model
{

  function users_model()
  {
    parent::Model();
  }
  
  function get_users()
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