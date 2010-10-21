<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'abstract_tree_model.php';

class Groups_model extends abstract_tree_model
{

  function __construct()
  {
    parent::__construct('group_tree');
  }

	function get_group_tree( $parent = 0, $recurse = true )
	{		
		return $this->_get_tree('id, parent_id, deletable, name');
	}
}