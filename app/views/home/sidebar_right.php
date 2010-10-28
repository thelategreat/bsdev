<div style="padding-bottom: 10px">
<ul>
	<li>
		<a href="/profile"><img src="/img/icons/mail.png" style="height: 20px; margin-bottom: -5px"/> Subscribe</a>
	</li>
	<li>
		<a href="/calendar"><img src="/img/icons/calendar_1.png" style="height: 20px; margin-bottom: -5px"/> Calendar</a>
	</li>
</ul>
<span style="padding-top: 15px; display: block; font-style: italic;">Follow us...</span>
<a href="#" title="Twitter"><img src="/img/icons/icon_twitter.png" /></a>
<a href="#" title="Facebook"><img src="/img/icons/icon_facebook.png" /></a>
<a href="#" title="RSS"><img src="/img/icons/icon_rss.png" /></a>
</div>

<?php 
if( count($groups) > 3 ) {
	for( $i = 3; $i < count($groups); $i++ ) {
		echo '<h3>';
		if( file_exists('img/icons/black/' . strtolower($groups[$i][0]) . '.png')) {
			echo '<img src="/img/icons/black/' . strtolower($groups[$i][0]) . '.png" height="16px"/> ';			
		}
		echo '<a href="/home/section/'.$groups[$i][1].'">' . $groups[$i][0] . '</a></h3>';
		echo '<ul>';
		foreach( $groups[$i][2]->result() as $sub_group ):
			echo '<li><a href="/home/section/'.$groups[$i][1].'/'. $sub_group->id.'">' . $sub_group->name . '</a></li>';
		endforeach;
		echo '</ul>';
	}
}
?>
<!--
<h3>
	Ad Blob 1
</h3>
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
<h3>
	Ad Blob 2
</h3>
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
<h3>
	Ad Blob 3
</h3>
<p>
	Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
</p>
-->