<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('twitteroauth/twitteroauth.php');

class Twitter {
    public function __construct($params = null)
    {
        $config['twitter_consumer_key'] = 'cpQmRfPTwnsTKZAa2gHug';
        $config['twitter_consumer_secret'] = 'FqjXLiIDTXzbjOMCG9mXzyq5hkLz0M3TiIO0oVAW4';
        $config['twitter_access_token'] = '50873008-jwwAimUGqFp9eeaa2c3nWTEyfUT6CZWh0lAufaVY7';
        $config['twitter_access_secret'] = 'lK9w0GNOuOn1CG61aLfMzVsk9BdDKYPEFi6sHAiFQw';

        $this->config = $config;
    }   
    
    public function getTweets($params) {

    $tmhOAuth = new twitteroauth($this->config['twitter_consumer_key'],
            $this->config['twitter_consumer_secret'],
            $this->config['twitter_access_token'],
            $this->config['twitter_access_secret']);

        if (!isset($params['screen_name'])) return false;
        if (!isset($params['count']) || !is_int($params['count'])) $params['count'] = 5;

        $response = $tmhOAuth->oAuthRequest('https://api.twitter.com/1.1/statuses/user_timeline.json', 
        'GET', array('screen_name' => $params['screen_name'], 'count'=>$params['count']));

       try {
           $response = json_decode($response);
       } catch (Exception $e) {
            log_message('error', 'Unable to process twitter feed: ' . $e->getMessage());
       }

       if (isset($response->errors)) {
            log_message('error', 'Twitter error when searching for feed from ' . $params['screen_name'] . ': ' . $response->errors['message']);
            return false;
       }

       return $response;
    }    
    
}

?>
