<?php

/**
 * This is the message queue processor
 * It is meant to be run from CLI only
 * call from cron with:
 * $> php index.php cli mlprocess process
 */

class MlProcess extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    // prevent this from running on the web
    if(!$this->input->is_cli_request()) {
      exit();
    }
  }

  public function process()
  {
    echo "Oi";
  }
}
