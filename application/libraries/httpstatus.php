<?php if (!defined('BASEPATH')) exit('No direct script access allowed');   
  
/**
 * HTTP Status class
 * 
 * Constructs an HTTP status response 
 *
 * @package default
 * @author RD
 **/
class HTTPStatus 
{  
	public function status( $code, $message )
	{
		$ret = new stdClass();
		$ret->code = $code;
		$ret->message = $message;	
		
		return $ret;
	}
		
}