<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class templates_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  /**
  	Load templates form the descriptor file
  **/
  function get_templates()
	{
		$this->load->model('list_positions_model');	

		$file = $this->config->item('template_file');

		$string = file_get_contents($file);
		$json = json_decode($string);

		$positions = $this->list_positions_model->get_list_positions()->result();

		foreach ($json as &$template) {
			foreach ($template->positions as &$list) {
				$pos = $this->_find_list_id($positions, $list->name);
				if ($pos !== false) {
					$list->id = $pos;
				}

			}
		}

		return $json;
	}

	private function _find_list_id($positions, $name) {
		foreach ($positions as $it) {
			if (trim(strtolower($it->name)) == trim(strtolower($name))) return $it->id;
		}
	}
}

