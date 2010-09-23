
<?php foreach( $articles->result() as $article ): ?>
	<div class="article">
		<h2><a href="/article/view/<?=$article->id?>"><?= $article->title ?></a></h2>
		<p><?= $article->excerpt ?></p>
		<p class="read-more"><a href="/article/view/<?=$article->id?>"><em>read more...</em></a><p>
	</div>
<?php endforeach; ?>

<!--
<div class="article">
	<h2>
		Heading
	</h2>
	<img src="/img/junk/gallery_photo4.jpg" style="float: right; margin: 5px; margin-top: 20px" />
	<p>
		Lorem ipsum dolor sit amet consect etuer adipi scing elit sed diam nonummy nibh euismod tinunt ut laoreet dolore magna aliquam erat volut. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
	</p>
	<p>
		Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
	</p>
</div>
<div class="article">
	<h2>
		Heading
	</h2>
	<img src="/img/junk/gallery_photo5.jpg" style="float: left; margin: 5px; margin-top: 20px" />
	<p>
		Lorem ipsum dolor sit amet consect etuer adipi scing elit sed diam nonummy nibh euismod tinunt ut laoreet dolore magna aliquam erat volut. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
	</p>
</div>
<div class="article">
	<h2>
		Heading
	</h2>
	<img src="/img/junk/gallery_photo6.jpg" style="float: right; margin: 5px; margin-top: 20px" />
	<p>
		Lorem ipsum dolor sit amet consect etuer adipi scing elit sed diam nonummy nibh euismod tinunt ut laoreet dolore magna aliquam erat volut. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
	</p>
</div>
-->