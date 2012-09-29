<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter {
    
    const TWITTER_API_URL  = 'https://api.twitter.com/1/statuses/user_timeline.rss?';
    
    public function __construct($params = null)
    {
        
    }   
    
    public function getTweets($params) {
        
        $screen_name = $params['screen_name'];
        $count       = $params['count'];
        $user_id     = $params['user_id'];
        
        if ((!$screen_name && !$user_id)
            || ($screen_name == null && $user_id == null)) 
        {
            return false;
        }
        
        $ctx = stream_context_create(array(
		    'http' => array(
		        'timeout' => 1
		        )
		    )
		);
        $url = self::TWITTER_API_URL . "screen_name={$screen_name}&count={$count}&user_id={$user_id}";        
        try {
            $feed = @file_get_contents($url, 0, $ctx);
            if (!$feed) return false;
            $xml = simplexml_load_string($feed);   
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        $tweets = array();
        foreach ($xml->channel->item as $item) {
            $tweet['title']         = (string)$item->title;
            $tweet['description']   = (string)$item->description;
            $tweet['pubDate']       = (string)$item->pubDate;
            $tweet['guid']          = (string)$item->guid;
            $tweet['link']          = (string)$item->link;
            
            $tweets[] = $tweet;
        }
        
        return $tweets;                
    }    
    
}

?>
