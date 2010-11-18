<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Database extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
    $this->tables();
	}

  function tables()
  {
    $db_data = array(
      'tables' => $this->db->list_tables(),
      'tabs' => $this->tabs->gen_tabs(array('Tables','Views','Routines','SQL'), 'Tables', '/admin/database')
    );

    $this->gen_page('Admin - Database', 'admin/database/tables', $db_data );
  }

  function views()
  {
    $db_data = array(
      'tables' => $this->db->list_tables(),
      'tabs' => $this->tabs->gen_tabs(array('Tables','Views','Routines','SQL'), 'Views', '/admin/database')
    );

    $this->gen_page('Admin - Database', 'admin/database/views', $db_data );
  }

  function routines()
  {
    $db_data = array(
      'tables' => $this->db->list_tables(),
      'tabs' => $this->tabs->gen_tabs(array('Tables','Views','Routines','SQL'), 'Routines', '/admin/database')
    );

    $this->gen_page('Admin - Database', 'admin/database/routines', $db_data );
  }

  function sql()
  {
    $db_data = array(
      'tables' => $this->db->list_tables(),
      'tabs' => $this->tabs->gen_tabs(array('Tables','Views','Routines','SQL'), 'SQL', '/admin/database')
    );

    $this->gen_page('Admin - Database', 'admin/database/sql', $db_data );
  }

}
