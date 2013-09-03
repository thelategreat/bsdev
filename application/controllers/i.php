<?php

/**
 This is the image processor that resizes and caches resized images.  It's only function is size()
 */
class I extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model("img_model", "img");
		ini_set("memory_limit", "64M"); // adjust to your own needs
  }
  public function index()
  {
    redirect('/');
  }
  
/**
  Calling /i/size/o/filename/w/100/h/100
  would call for the file named filename at a maximum height of 100 and max width of 100
  to pass forward slashes use --
  */
  public function size()
  { 
    $props = $this->uri->ruri_to_assoc();

    if (!isset($props['o'])) exit(0);
    $w = -1;
    $h = -1;
    $m = 0;
    if (isset($props['w'])) $w = $props['w'];
    if (isset($props['h'])) $h = $props['h'];
    if (isset($props['m'])) $m = $props['m'];

    $this->img->set_img($props['o']);
    $this->img->set_size($w, $h, $m);
    $this->img->set_square($m);
    $this->img->get_img();
  }
}