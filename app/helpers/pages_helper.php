<?php

/**
 * Generate a menu structure as UL
 *
 * @return void
 * @author J Knight
 */
function mk_menu( $parent = 0 )
{
	$CI =& get_instance();  
    $CI->load->database();

	$parent = (int)$parent;

	$items = $CI->db->query("SELECT id, title, body, page_type FROM pages WHERE parent_id = $parent AND active = 1 ORDER BY sort_order");

	$s = '';
	$s .= '<ul>';
	
	foreach( $items->result() as $item ) {
		if( $item->page_type == 'link') {
			$s .= '<li><a href="'. $item->body .'">' . $item->title . '</a></li>';					
		} else {
			$s .= '<li><a href="/page/view/'.$item->id.'">' . $item->title . '</a></li>';					
		}
	}
	
	if( is_admin()) { 
		$s .= '<li><a href="/admin">admin</a></li>';
	}
	$s .= '</ul>';
	
	return $s;
}

/**
 * Used as a recursion to create rows for the admin/pages/index view
 *
 * @param string $titles nested array
 * @param string $offset how deep are we (tracks)
 * @return void
 */
function emit_title_rows( $titles, $offset = 5 )
{
	$first = true;
	$i = 0;
	$count = 0;
	foreach( $titles as $page ): 
		$last = (count($titles) == ($i + 1));
		$style = ($count % 2) == 0 ? "" : 'class="odd"';
		echo "<tr $style>";
		  	echo '<td style="width: 70%; padding-left: ' . $offset . 'px">';
			switch( $page->page_type ) {
				case 'link':
				echo '<img src="/img/admin/link.png" style="margin-bottom: -3px;"/> ';
				break;
				
				default:
				echo '<img src="/img/admin/text-x-generic.png" style="margin-bottom: -3px;"/> ';				
				break;				
			}
				echo '<a href="/admin/pages/edit/'.$page->id.'">'.$page->title.'</a>';
			echo '</td>';
			echo '<td>';
			echo ($first ? '&nbsp;-&nbsp;' : '<a href="/admin/pages/sort/up/'.$page->id.'" title="move up"><img src="/img/admin/go-up.png" class="icon" /></a>');
			echo '&nbsp;';
			echo ($last ? '&nbsp;-&nbsp;' : '<a href="/admin/pages/sort/down/'.$page->id.'" title="move down"><img src="/img/admin/go-down.png" class="icon" /></a>');
			echo '</td>';
			echo '<td>';
			echo ($page->active ? '<img src="/img/admin/tick.png" onclick="deactivate(0);"/>' : '<img src="/img/admin/cross.png" onclick="activate(0);" />');
			echo '</td>';
			if( $page->deletable ) {
				echo '<td><a href="/admin/pages/rm/'.$page->id.'" title="delete" onclick="return confirm(\'Really delete this page?\');"><img src="/img/admin/user-trash.png" /></a>';
			} else {
				echo '<td/>';
			}
		echo '</tr>';
	
		if( count($page->children) ) { emit_title_rows( $page->children, $offset + 30 ); }
		$first = false;
		$i++;
		$count++;
	endforeach;
}

function pretty_size( $size )
{
  // i dont think php can handle a number beyond tera ;)
  foreach(array('b','kb','mb','gb','tb','pb','eb','zb','yb') as $sz ) {
    if( $size < 1024.0 ) {
      return sprintf("%3.1f %s", $size, $sz );
    }
    $size /= 1024.0;
  }
}