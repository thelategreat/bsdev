<?php


class Mailer
{
	
	function __construct()
	{
	}
	
	function send( $to, $subject, $message, $test_mode = false )
	{
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
		
		if( $this->is_html ) {
			$boundary = md5(uniqid(time()));
		}
				
	}
}

?>