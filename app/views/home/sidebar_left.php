
<?php 
	$count = 0;
	foreach( $groups as $group ):
		echo '<h3>';
		if( file_exists('img/icons/black/' . strtolower($group[0]) . '.png')) {
			echo '<img src="/img/icons/black/' . strtolower($group[0]) . '.png" height="16px"/> ';			
		}
		echo '<a href="/home/section/' . $group[1] . '">' . $group[0] . '</a></h3>';
		echo '<ul>';
		foreach( $group[2]->result() as $sub_group ):
			echo '<li><a href="/home/section/'.$sub_group->id.'">' . $sub_group->name . '</a></li>';
		endforeach;
		echo '</ul>';
		$count++;
		if( $count > 2 ) {
			break;
		}
	endforeach; 
?>
