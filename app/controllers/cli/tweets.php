<?php

/**
 * This is the twitter processor
 * It is meant to be run from CLI only
 * call from cli or cron with:
 * $> php index.php cli tweets process
 */


class Tweets extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    // prevent this from running on the web
    if(!$this->input->is_cli_request()) {
      exit();
    }

    $this->load->library('twitter');
    $this->load->model('tweets_model');
    $this->load->config('twitter');
  }

  public function process()
  {
    echo "getting tweets...\n";
    $params = array("screen_name"=>$this->config->item('twitter_screen_name'),
                    "count"=>$this->config->item('twitter_count'),
                    "user_id"=>$this->config->item('twitter_user_id')); 
        
    $tweets = $this->twitter->getTweets($params);
    if ($tweets) {
      $this->tweets_model->save($params, $tweets);            
    } else {
      echo 'Error <br/>';
    }        
 
    echo "done!\n";
  }
}

?>


