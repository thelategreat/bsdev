<?php

/**
 * Used as a recursion to create rows for a tree view
 *
 * @param string $data nested array
 * @param string $offset how deep we are (tracks recursive)
 * @return void
 */
function emit_tree_rows( $url, $data, $offset = 5, $fld_name = 'name' )
{
	$first = true;
	$i = 0;
 	$count = 0;

	foreach( $data as $item ): 
		$last = (count($data) == ($i + 1));
		$style = ($count % 2) == 0 ? "" : 'class="odd"';
		echo "<tr $style>";
		  echo '<td style="width: 70%; padding-left: ' . $offset . 'px">';
			echo '<img src="/img/admin/text-x-generic.png" style="margin-bottom: -3px;"/> ';				
			echo '<a href="'.$url.'/edit/'.$item->id.'">'.$item->$fld_name. "</a>";
			echo '</td>';
			echo '<td>';
			echo ($first ? '&nbsp;-&nbsp;' : '<a href="'.$url.'/sort/up/'.$item->id.'" title="move up"><img src="/img/admin/go-up.png" class="icon" /></a>');
			echo '&nbsp;';
			echo ($last ? '&nbsp;-&nbsp;' : '<a href="'.$url.'/sort/down/'.$item->id.'" title="move down"><img src="/img/admin/go-down.png" class="icon" /></a>');
			echo '</td>';
			if( isset($item->deletable) && $item->deletable ) {
				echo '<td><a href="'.$url.'/rm/'.$item->id.'" title="delete" onclick="return confirm(\'Really delete this item?\nThis will delete any children too!\');"><img src="/img/admin/user-trash.png" /></a>';
			} else {
				echo '<td/>';
			}
		echo '</tr>';
	
		if( count($item->children) ) { 
			emit_tree_rows( $url, $item->children, $offset + 30, $fld_name ); 
		}
		
		$first = false;
		$i++;
		$count++;
		
	endforeach;
}