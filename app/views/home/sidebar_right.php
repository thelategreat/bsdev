<ul>
	<li>
		<a href="/profile"><img src="/img/icons/mail.png" style="height: 20px; margin-bottom: -5px"/> Subscribe</a>
	</li>
	<li>
		<a href="/calendar"><img src="/img/icons/calendar_1.png" style="height: 20px; margin-bottom: -5px"/> Calendar</a>
	</li>
</ul>
<span style="padding-top: 15px; display: block; font-style: italic">Follow us...</span>
<a href="#" title="Twitter"><img src="/img/icons/icon_twitter.png" /></a>
<a href="#" title="Facebook"><img src="/img/icons/icon_facebook.png" /></a>
<a href="#" title="RSS"><img src="/img/icons/icon_rss.png" /></a>

<?php foreach( $ads as $ad ): ?>
	<h3><?= $ad->title ?></h3>
	<p>Ad Image goes here</p>
<?php endforeach; ?>
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