<?php
/* Call this MY_Exceptions.php
*/

class MY_Exceptions extends CI_Exceptions 
{
  
    function My_Exceptions()
    {
        parent::CI_Exceptions();
				$this->mailto = "jim@j2mfk.com";
				$this->mailfrom = array('error-donotreply@somethere.com', 'Somewhere.com Error');
    }

    function log_exception($severity, $message, $filepath, $line)

    {   
        $CI =& get_instance();
        $severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

    		log_message('error', 'Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line, TRUE);

        if($CI->config->item('base_url') == 'http://www.production-domain.com/') { 
          $CI->load->library('email');
          
          $uri = $CI->uri->uri_string();
          
          $CI->email->from( $this->mailfromp[0], $this->mailfrom[1]);
          $CI->email->to($this->mailto);

          $CI->email->subject('APP Error [severity: '.$severity.']');
          $CI->email->message('Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line."\n"."From URL: ".$uri);

          $CI->email->send();
        }
    }
}
?>