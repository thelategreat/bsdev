<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * A simple web scraper class
 *
 * @package default
 * @author J Knight
 **/
class Spider
{
	var $curl;
	var $html;
	var $binary;
	var $url;
	
	/**
	 * ctor
	 *
	 * @return void
	 **/
	function __construct()
	{
		$this->reset();
	}
	
	/**
	 * Reset all vars
	 *
	 * @return void
	 **/
	function reset()
	{
		$this->html = '';
		$this->binary = 0;
		$this->url = '';
	}
	
	/**
	 * Fetch a url
	 *
	 * @param $url the url to fetch
	 * @param $var an assoc array of get or post vars
	 * @param $method the request method
	 * @return void
	 **/
	function fetch( $url, $vars = array(), $method = 'get' )
	{
		$this->url = $url;
		
		$query_str = '';
		if( count($vars)) {
			foreach( $vars as $k => $v ) {
				$query_str .= urlencode($k) . "=" . urlencode($v) . "&";
			}
			$query_str = substr( $query_str, 0, -1 );
		}
		
		if( strtolower($method) == 'get' ) {
			$url .= "?" . $query_str;
		}
		
		$this->curl = curl_init();
		if( strtolower($method) == 'post') {
			curl_setopt($this->curl, CURLOPT_POST, 1 );
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query_str );
		}
		curl_setopt ($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($this->curl, CURLOPT_URL, $this->url); 
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true); 
		// set $this->binary to 1 to grab images and such
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, $this->binary); 
		$this->html = curl_exec($this->curl); 
		curl_close ($this->curl); 
	}
	
	/**
	 * Cheezy tag grabber
	 *
	 * @return void
	 **/
	function get_tags( $start_tag, $end_tag )
	{
		preg_match_all("($start_tag.*$end_tag)siU", $this->html, $matches);
		return $matches[0];
	}
}

?>