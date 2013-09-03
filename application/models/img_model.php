<?php
class Img_model extends CI_Model
{

  public $path          = "";
  public $img           = "";
  public $img_name      = "";
  public $img_ext       = "";
  private $img_prop     = array();
  private $default      = "/images/image_not_found.jpg";
  
  private $res_prop = array(
    "image_library"  => 'GD2',//
    "create_thumb"   => FALSE,
    "maintain_ratio" => TRUE,
    "dynamic_output" => FALSE
  );
  
  public function __construct()
  {
    parent::__construct();
    $this->load->library('image_lib');
    $this->load->helper('file');
    $this->path = BASEPATH."../public/"; // Images are all held in the public directory
    $this->cache_folder = $this->path . 'img_cache'; // Cached images are in public/img_cache/ with a subdir structure created automatically
  }
  
  /**
    Sets the image on the object
    First takes the path and replaces -- to /, then looks for image
    then looks for image with jpg extension before failing.
    */
  public function set_img($img) //-- as slash..  "/images/foo.jpg = images--foo.jpg"
  {
    $img = str_replace("--", "/", $img);
    $this->img_pre = $img; 

    if (strlen($this->img_ext) == 0) {
      $this->img_ext  = '.jpg';
    }

    if(file_exists($this->path . $img)) {
      $this->img = $this->path . $img;
    } elseif(file_exists($this->path . $img . $this->img_ext)) {
      $this->img = $this->path . $img . $this->img_ext;
    } else {
      $this->img = $this->path . $this->default;
    }

    $this->img_prop = getimagesize($this->img);
  }
  
  /**
    Sets the size of the image based on specified dimensions
    */
  public function set_size($width=0, $height=0, $master = "s")
  {
    //$this->res_prop['master_dim'] = $master;
	$this->res_prop['maintain_ratio'] = TRUE;
    // sEt Wdith
    if($width < $this->img_prop[0] && $width > 0) {
      $this->res_prop['width'] = $width;
    } else {
      $this->res_prop['width'] = $this->img_prop[0]-1;
    }
    //Set height

    if($height < $this->img_prop[1] && $height > 0) {
      $this->res_prop['height'] = $height;
    } else {
      $this->res_prop['height'] = $this->img_prop[1]-1;
    }

  }
  
	public function set_square($square = FALSE)
	{
		if($square == "s")
		{
			$this->res_prop['x_axis'] = $this->res_prop['height'];
			$this->res_prop['maintain_ratio'] = TRUE;
		}
	
	}

  /** 
    Gets the image from the cache or, if the cache doesn't exist, makes a cache entry, resizes the image
    and stores the resized cached image before passing the cache path to show_img
  */
  public function get_img($raw = TRUE)
  {
    $this->res_prop['source_image'] = $this->img;
    $cache_image = $this->cache_folder . '/' . $this->img_pre . '/'.$this->img_name.'_'.$this->res_prop['width'].'x'.$this->res_prop['height'].$this->img_ext;

    if(file_exists($cache_image))
    {
        $this->show_img($cache_image);
    }
    else
    {
      if(!is_dir($this->path.$this->img_pre.$this->cache_folder))
      {
        @mkdir($this->cache_folder . '/' . $this->img_pre, 0777, true);
      }
      
      $this->res_prop['new_image'] = $cache_image;
      $this->image_lib->initialize($this->res_prop);
      $this->image_lib->resize();
      
      
      $this->show_img($cache_image);
    }
    
  }
  
  /**
    Generates the actual image with the appropriate headers to make the browser render it properly
    @param Image path
  */
  public function show_img($path)
  {
    $data = read_file($path);
    header("Content-Disposition: filename=".$this->img_name."_".$this->res_prop['width']."x".$this->res_prop['height'].".".$this->img_ext.";");
    $stuff = get_mime_by_extension($path);
    header("Content-Type: {$stuff}");
    header('Content-Transfer-Encoding: binary');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
    echo $data;
  }
}