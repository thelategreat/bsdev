
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
			echo '<li><a href="/home/section/'.$group[1].'/'.$sub_group->id.'">' . $sub_group->name . '</a></li>';
		endforeach;
		echo '</ul>';
		$count++;
		if( $count > 2 ) {
			break;
		}
	endforeach; ?>

<!--
<h3>
	Group 1
</h3>
<img src="/img/junk/gallery_photo5.jpg" style="float: left; margin: 0px; height: 50px; margin-top: 20px; margin-right: 5px" />
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
<h3>
	Group 2
</h3>
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
<h3>
	Group 3
</h3>
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
-->