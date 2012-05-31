<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tweets extends CI_Controller {	
     
	/**
    * Show tweets for a user
    * 
    * @param mixed User ID or screen name of twitter user
    */
    public function show($user)
	{        
        $this->load->model('tweets_model');
                        
        $tweets = $this->tweets_model->load($user);        
		                        
        $data['user'] = $user;
        $data['tweets'] = $tweets;        
        $this->load->view('tweets', $data);
	}
    
    
    /**
    * Retrieve twitter feed for user, save to db
    * 
    * Configure in config/twitter.php
    * 
    */
    public function get()
    {
        $this->load->library('twitter');
        $this->load->model('tweets_model');    
                
        $xmlconfig = 'application/config/twitter.xml';
        
        /* Example of use with pre-defined configuation file */
        $this->load->config('twitter');
                        
        $params = array("screen_name"=>$this->config->item('twitter_screen_name'),
                    "count"=>$this->config->item('twitter_count'),
                    "user_id"=>$this->config->item('twitter_user_id')); 
        
        $tweets = $this->twitter->getTweets($params);
        if ($tweets) {
            $this->tweets_model->save($params, $tweets);            
            echo 'Saved <br/>';
        } else {
            echo 'Error <br/>';
        }        
        
                  
        /* Example of use with an XML configuration file */
        if (is_file($xmlconfig)) {
            try {                
                $xml = simplexml_load_file($xmlconfig); 
                foreach ($xml->user as $user) {
                    $params = array("screen_name" => $user->screen_name,
                                    "count" => $user->count,
                                    "user_id" => $user->user_id);
                    
                    $tweets = $this->twitter->getTweets($params);
                    if ($tweets) {
                        $this->tweets_model->save($params, $tweets);            
                        echo 'Saved <br/>';
                    } else {
                        echo 'Error <br/>';
                    }        
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "<br/>No XML config file found.";
        }
                
    }
    
}

/* End of file tweets.php */
/* Location: ./application/controllers/tweets.php */