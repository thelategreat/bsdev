<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/* crud */

class Users_model extends Model
{
    /**
     * Set your return type values can be any of the methods in
     * http://codeigniter.com/user_guide/database/results.html
     * 
     * Set the return to NULL to return the actual query object
     * This can be overridden by passing a third variable to
     * the READ method.
     */
    private $return = 'result';	
	
	function Users_model()
	{
		parent::Model();
	}
	
    public function create($table, $data = array(), $sql_modifiers = array())
    {
      foreach($sql_modifiers as $modifier)
      {
        if(!isset($modifier[2]))
        {
          $this->db->$modifier[0]($modifier[1]);
        } else {
          if(!isset($modifier[3]))
          {
            $this->db->$modifier[0]($modifier[1], $modifier[2]);
          } else {
            $this->db->$modifier[0]($modifier[1], $modifier[2], $modifier[3]);
          }
        }
      }
      $query = $this->db->insert($table, $data);
      return;
    }
	
	function read( $table, $sql_modifiers = array(), $return='default' )
	{
      if($return == 'default')
        $return = $this->return;

      foreach($sql_modifiers as $modifier)
      {
        if(!isset($modifier[2]))
        {
          $this->db->$modifier[0]($modifier[1]);
        } else {
          if(!isset($modifier[3]))
          {
            $this->db->$modifier[0]($modifier[1], $modifier[2]);
          } else {
            $this->db->$modifier[0]($modifier[1], $modifier[2], $modifier[3]);
          }
        }
      }
      $query = $this->db->get($table);
      if($return == NULL)
        return $query;
      else
        return $query->{$return}();		
	}
	
    public function update($table, $data = array(), $sql_modifiers = array())
    {
      foreach($sql_modifiers as $modifier)
      {
        if(!isset($modifier[2]))
        {
          $this->db->$modifier[0]($modifier[1]);
        } else {
          if(!isset($modifier[3]))
          {
            $this->db->$modifier[0]($modifier[1], $modifier[2]);
          } else {
            $this->db->$modifier[0]($modifier[1], $modifier[2], $modifier[3]);
          }
        }
      }
      $query = $this->db->update($table, $data);
    }
	
    public function delete($table, $sql_modifiers = array())
    {
      foreach($sql_modifiers as $modifier)
      {
        if(!isset($modifier[2]))
        {
          $this->db->$modifier[0]($modifier[1]);
        } else {
          if(!isset($modifier[3]))
          {
            $this->db->$modifier[0]($modifier[1], $modifier[2]);
          } else {
            $this->db->$modifier[0]($modifier[1], $modifier[2], $modifier[3]);
          }
        }
      }
      $query = $this->db->delete($table);
    }

    public function execute_sql_file($file_name)
    {
      $file = file($file_name);
      foreach($file as $query)
      {
        $result = $this->db->query($query);
      }
      return;
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