<?php 

// used by the sidebar menus
function emit_sidebar_subgroup_menu( $group, $section, $indent = 0 ) {
	foreach( $group as $sub_group ):
		echo "<li class='subgroup_$indent'>" . '<a href="/home/section/'. $sub_group->id.'">' . $sub_group->name . '</a></li>';
		if( count($sub_group->children) ) {
			emit_sidebar_subgroup_menu( $sub_group->children, $section, $indent + 2 );
		}
	endforeach;	
}


/* Detect mobile browsers */
function is_mobile_browser()
{
	$mobile_browser = 0;
	
	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $mobile_browser++;
	}

	if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	    $mobile_browser++;
	}    

	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
	$mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda','xda-');

	if(in_array($mobile_ua,$mobile_agents)) {
	    $mobile_browser++;
	}

	if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
	    $mobile_browser++;
	}

	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
	    $mobile_browser=0;
	}
	
	return $mobile_browser;
}

// simple dbg dump
function dbg( $var )
{
	echo '<pre style="font-size: 10px; background-color: white; color: black;">' . var_export($var, true) . '</pre>';
}

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

/**
 * return a link tag
 */
function link_to( $url, $text = null, $title = null, $class = null )
{
	return "<a href='$url' " . ($class ? " class='$class'" : '') . ($title ? " title='$title'" : '') . ">" . ($text ? $text : $url) . '</a>';
}

function mk_select( $name, $data, $default = null, $onclick = null )
{
  $s = "<select name='$name'";
  if( onclick ) {
    $s .= " onclick='$onclick'";
  }
  $s .= '>';

  foreach( $data as $row ) {
    $s .= "<option value='$row[0]'";
    if( $default && $row[0] == $default ) {
      $s .= " selected='selected'";
    }
    $s .= ">$row[1]</option>";
  }

  $s .= '</select>';
  return $s;
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

// check input looks like a valid email
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

// auto gen a password
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

/** 
 * Make a pager
 *
 * $page: the page we are on
 * $page_size : the number of times on the page
 * $max : the number of items we got. this is a bit cheezy since if we 
 *        have a full page, we presume there is another one.
 * $url : the url to use. page # is appended
 * $append : string to append to the url after the page number
 */
function mk_pager( $page, $page_size, $max, $url, $append = '' )
{
	// pagination
	$next_page = '';
	$prev_page = '';
  // previous page
  if( $page > 1 ) {
		$prev_page = '<button onclick="window.location=\''.$url.'/'.($page-1).'/'. $append . '\'"><img src="/img/admin/32-arrow-right.png" width="12px;"/></button>';
  } else {
    //$prev_page = '<button onclick="javascript:void(0)" style="width: 24px; height: 24px;"> </button>';
  }
  // next page
	if( $max == $page_size ) {
		$next_page = '<button onclick="window.location=\''.$url.'/'.($page+1).'/' . $append . '\'"><img src="/img/admin/32-arrow-left.png" width="12px;"/></button>';
  } else {
    //$next_page = '<button onclick="javascript:void(0)" style="width: 12px;"> </button>';
  }
		
	$s = '<table class="pager"><tr><td align="left" style="width: 32px;">' . $prev_page . '</td><td align="right">' . $next_page . '</td></tr></table>';

	return $s;	
}

// does a string end with
function ends_with( $subj, $str  )
{
	$end = substr($subj, strlen($subj) - strlen($str));
	return $end == $str;
}

// return string of max len with ... if too long
function str_max_len( $str, $max = false )
{
	if( $max ) {
		if( strlen($str) > $max ) {
			return substr( $str, 0, $max ) . '...';
		}
	}
	return $str;
}


/**
	Depending on the type of list object (article, product, etc) the file paths
	are somewhat different and the resizer needs to know where to look. Or, if it's an external 
	link, we need to return that directly.

	@param Item object
	@param Width
	@param Height
	*/
function imageLinkHelper($item, $width=false, $height=false) {

	/* Depending on the type of record, images are stored differently */

	switch ($item->object_type) {
		case 'article':
			// No image found
			if (!isset($item->media[0]['thumbnail'])) {
				$ret = base_url('/images/image_not_found.jpg');
				return $ret;
			}

			// This isn't a stored media link, it's an external link
			if (strtolower($item->media[0]['type']) == 'link') {
				return $item->media[0]['thumbnail'];
			}

			// Media link to send through the resizer
			$ret = base_url("/i/size/o/{$item->media[0]['thumbnail']}");
		break;

		case 'product':
			$ret = '/i/size/o/product--' . substr($item->ean, -1) . '--' . $item->ean;
		break;

		default:
			$ret = null;
		break;
	}

	// The dimensions specified in the link to the helper - this controls the resizer
	if ($width) {
			$ret .= "/w/{$width}";
		}
		if ($height) {
			$ret .= "/h/{$height}";
		}

	return $ret;

}
