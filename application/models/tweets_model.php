<?php
    
class Tweets_model extends CI_Model {

    function __construct()
    {        
        parent::__construct();
    }
    
    /**
    * Save tweets to database
    * 
    * @param mixed User identification params
    * @param mixed Array of tweets
    * @return True on success or error message
    */
    function save($params, $tweets)
    {
        
        try {            
            if ($params['screen_name'] != '') {
                $sql = sprintf("SELECT * from tweets 
                        WHERE screen_name='%s'",                        
                        $params['screen_name']);
            } elseif ($params['user_id'] != '') {
                $sql = sprintf("SELECT * from tweets 
                        WHERE user_id='%s'",                        
                        $params['user_id']);
            } else {
                return false;
            }
                        
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $id = $row->id;
                }            
                
                $sql = sprintf("UPDATE tweets SET 
                        screen_name='%s',
                        user_id='%s',
                        time=NOW(),
                        tweets=%s",
                        $params['screen_name'],
                        $params['user_id'],
                        $this->db->escape(serialize($tweets)));
                $this->db->query($sql);                        
            } else {
                $sql = sprintf("INSERT INTO tweets 
                        (screen_name, user_id, time, tweets)
                        VALUES('%s', '%s', NOW(), %s)",
                        $params['screen_name'],
                        $params['user_id'],
                        $this->db->escape(serialize($tweets)));
                $this->db->query($sql);
            }
            return true;    
        } catch (Exception $e) {
            return $e->message;
        }
    }
    
    /**
    * Load tweets for a user
    * 
    * @param string User screen name or user ID
    * @return mixed Array of tweets
    */
    function load($user=null) {        
        $this->load->library('twitter');
        $sql = sprintf("SELECT * FROM tweets
                        WHERE user_id='%s'
                        OR screen_name='%s'",
                        $user,
                        $user);
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $result = $query->result();            

            if (strtotime($result[0]->time) < strtotime(date('Y-m-d H:i:s') . '-15 minutes')) {

				$params = array("screen_name"=>$user,
		                    "count"=>4,
		                    "user_id"=>''); 
		        
		        $tweets = $this->twitter->getTweets($params);

		        if ($tweets) {
		            $this->tweets_model->save($params, $tweets);
		            return $this->tweets_model->load($user);		     
		        }		       
            }
            return (unserialize($result[0]->tweets));
        } else {
            return false;
        }
    }
    
}

?>
