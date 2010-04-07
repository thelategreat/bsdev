<?php 

function gen_uuid( $prefix = '' )
{
  $chars = md5(uniqid(mt_rand(), true));
  $uuid  = substr($chars,0,8) . '-';
  $uuid .= substr($chars,8,4) . '-';
  $uuid .= substr($chars,12,4) . '-';
  $uuid .= substr($chars,16,4) . '-';
  $uuid .= substr($chars,20,12);
  return $prefix . $uuid;	
}	

/**
 * Modifies a string to remove all non ASCII characters and spaces.
 */
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text); 
    // trim
    $text = trim($text, '-');
    // transliterate
    if (function_exists('iconv'))
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    // lowercase
    $text = strtolower($text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    if (empty($text))
    {
        return 'n-a';
    }
    return $text;
}

/*
<!--
<object width="480" height="295">
	<param name="movie" value="http://www.youtube.com/v/yjXY9kl2ljQ&hl=en_US&fs=1&rel=0"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed src="http://www.youtube.com/v/yjXY9kl2ljQ&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="295"></embed>
</object>
-->
*/
function get_embed_object( $url, $width="425", $height="344" )
{
	$purl = parse_url( $url );
	if( $purl['host'] == 'www.vimeo.com' || $purl['host'] == 'vimeo.com') {
		$url = 'http://vimeo.com/moogaloop.swf?clip_id=' . substr($purl['path'], 1 ) . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1';
	}
	else if( $purl['host'] == 'www.youtube.com' || $purl['host'] == 'www.youtube.com' ) {
		// link from browser...
		//http://www.youtube.com/watch?v=yjXY9kl2ljQ
		// turns into this for embed
		//http://www.youtube.com/v/yjXY9kl2ljQ&hl=en_US&fs=1&rel=0
		if( !empty($purl['query'])) {
			$vals = explode('&', html_entity_decode($purl['query']));
			$attrs = array();
			foreach( $vals as $val ) {
				$tmp = explode('=', $val );
				$attrs[$tmp[0]] = $tmp[1];
			}
			if( isset($attrs['v'])) {
				$url = 'http://www.youtube.com/v/' . $attrs['v'] . '&hl=en_US&fs=1&rel=0';
			}
		}
	}	
	
	$s = '<object width="'.$width.'" height="'.$height.'">';
	$s .= '	<param name="movie" value="'. $url .'"></param>';
	$s .= '	<param name="allowFullScreen" value="false"></param>';
	$s .= '	<param name="allowscriptaccess" value="always"></param>';
	$s .= '  <embed src="'. $url .'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="false" width="'.$width.'" height="'.$height.'"></embed>';
	$s .= '</object>';
	
	return $s;
}


/**
 * Returns a file size (or any number) as english with bytes, kb, mb
 * appended.
 *
 * $size the number
 * @return a string with english size
 */
function pretty_file_size( $size )
{
  // i dont think php can handle a number beyond tera ;)
	// i think these should be capitalized too
  foreach(array('b','kb','mb','gb','tb','pb','eb','zb','yb') as $sz ) {
    if( $size < 1024.0 ) {
      return sprintf("%3.1f %s", $size, $sz );
    }
    $size /= 1024.0;
  }	
}
?>
