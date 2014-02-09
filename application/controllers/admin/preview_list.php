<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("admin_controller.php");

class Preview_List extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct(){
            parent::__construct();
            $this->load->model('list_model');
		
	}

	function index($id=false,$type=false){
            $list = list_model::load($id,$type);
            foreach($list->items as &$item){
                $item->render();
                //echo $item->html;
            }
            
            $this->load->view('page/list', $list );
           
	}
	
	
}
