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
<a href="http://twitter.com/#!/Bookshelfnews" target="_blank" title="Twitter"><img src="/img/icons/icon_twitter.png" /></a>
<a href="http://www.facebook.com/home.php?#!/group.php?gid=193052215184" title="Facebook" target="_blank"><img src="/img/icons/icon_facebook.png" /></a>
<a href="/rss" title="RSS"><img src="/img/icons/icon_rss.png" /></a>
</div>

<?php 

if( count($groups) > 3 ) {
	for( $i = 3; $i < count($groups); $i++ ) {
		echo '<h3>';
		if( file_exists('img/icons/black/' . strtolower($groups[$i]->name) . '.png')) {
			echo '<img src="/img/icons/black/' . strtolower($groups[$i]->name) . '.png" height="16px"/> ';			
		}
		echo '<a href="/home/section/'.$groups[$i]->id.'">' . $groups[$i]->name . '</a></h3>';
		echo '<ul>';
		emit_sidebar_subgroup_menu( $groups[$i]->children, $section );
		/*
		foreach( $groups[$i]->children as $sub_group ):
			echo '<li><a href="/home/section/'. $sub_group->id.'">' . $sub_group->name . '</a></li>';
		endforeach;
		*/
		echo '</ul>';
	}
}
?>
