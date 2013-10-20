<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Tinymce extends CI_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Generates an image list for the tinyMCE image insert window 
	 */
	function imagelist($path,$id)
	{
		$this->load->model('media_model');

		if ($path && $id) $path = '/' . $path . '/' . $id;

		$images = $this->media_model->get_media_for_path($path);

		foreach ($images as $it) {
			$obj = new stdClass();
			$obj->title = $it['title'];
			$obj->value = '/media/' . $it['uuid'];
			$output[] = $obj;
		}

		echo json_encode($output);
	}

	function browse()
		{
			$stags = array();
			for( $i = 4; $i <= $this->uri->total_segments(); $i++ ) {
			$stags[] = $this->uri->segment($i);
		}

		if( count($stags) > 0  ) {
			$path = '/' . $stags[0] . '/' . $stags[1];
			$results = $this->media_model->get_media_for_path( $path );
			$view_data = array(
				'items' => $results,
				'page' => 1,
				'stags' => $stags,
				'errors' => $path,
				'prev_page' => '',
				'next_page' => ''
			);
			$this->load->view('admin/tinymce/filebrowser', $view_data );
		} else {
			echo 'Invalid path';
		}
	}
	
}
