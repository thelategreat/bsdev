<?php 

// if the text starts with http, return a link text
function mk_linkable( $text, $link_name = NULL )
{
	$text = trim($text);
	if( strpos($text, 'http:') === 0 ) {
		return "<a href='$text' target='_blank'>$text</a>";
	} else {
		return $text;
	}
}

// this is used to turn table field names form the database into
// humban readbale, suitable for a lable.
// used for event reference details
function mk_label( $text )
{
	$text = preg_replace('/_/', ' ', $text );
	return ucwords($text);
}

// dates come from the database as strings
// this formats them for site wide display usage
function fmt_date( $dt, $long = true )
{
	if( $long ) {
		// long format
		return date('D M j, Y @ g:i a',strtotime($dt));	
	} else {
		// short format
		return date('M j, Y @ g:i a',strtotime($dt));			
	}
}

//
function html_table( $data, $header = NULL, $caption = NULL )
{
	$s = '<table>';
	if( $caption ) {
		$s .= '<caption>' . $caption . '</caption>';
	}
	
	if( $header and count($header)) {
		$s .= '<tr>';
		foreach( $header as $row ) {
			$s .= '<td>' . $row . '</td>';
		}
		$s .= '</tr>';
	}
	
	$count = 0;
	if( $data and count($data)) {
		foreach( $data as $row ) {
			$s .= '<tr';
			if( $count % 2 != 0 ) {
				$s .= ' class="odd"';
			}
			$s .= '>';
			foreach( $row as $item ) {
				$s .= '<td>' . $item . '</td>';
			}
			$s .= '</tr>';
			$count++;
		}
	}
	
	$s .= '</table>';
	return $s;
}

function validEmail($email, $check_dns = FALSE )
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
			if( $check_dns ) {
	      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
	      {
	         // domain not found in DNS
	         $isValid = false;
	      }
			}
   }
   return $isValid;
}


function generatePassword($length=6, $strength=0) 
{
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


function ends_with( $subj, $str  )
{
	$end = substr($subj, strlen($subj) - strlen($str));
	return $end == $str;
}


