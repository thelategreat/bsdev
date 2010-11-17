


<?php 	
	/*var_dump( $parents );*/
	$count = 0;
	foreach( $groups as $group ):
		echo '<h3>';
		if( file_exists('img/icons/black/' . strtolower($group->name) . '.png')) {
			echo '<img src="/img/icons/black/' . strtolower($group->name) . '.png" height="16px"/> ';			
		}
		echo '<a href="/home/section/' . $group->id . '">' . $group->name . '</a></h3>';
		echo '<ul>';
		emit_sidebar_subgroup_menu( $group->children, $section );
		//foreach( $group->children as $sub_group ):
		//	echo '<li><a href="/home/section/' . $sub_group->id . '">' . $sub_group->name . '</a></li>';
		//endforeach;
		echo '</ul>';
		$count++;
		if( $count > 2 ) {
			break;
		}
	endforeach; 
?>
