<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'abstract_tree_model.php';

class Pages_model extends abstract_tree_model
{

  function __construct()
  {
    parent::__construct('pages','title');
  }

	function get_pages_tree( $parent = 0, $recurse = true )
	{		
		return $this->_get_tree('id, title, active, parent_id, page_type, deletable');
	}
}