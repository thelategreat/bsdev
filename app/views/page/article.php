<div class="article">
	
	<h3><?=$article->title?></h3>

	<?php if( count($images)) {
    if( $images[0]['type'] == 'link') {
        echo get_embed_object( $images[0]['fname'] );
   } else {
  ?>
			<img src="<?=$images[0]['thumbnail']?>" style="width: 150px; margin: 10px; float: right" />
      <?php
   }
 } ?>

	<?=$article->body?>

</div>


<div style="float: right">
	<!-- http://www.facebook.com/sharer.php?u=http://news.slashdot.org/story/10/04/15/1214248/Microsoft-Mice-Made-in-Chinese-Youth-Sweatshops -->
	<a href="http://www.facebook.com/sharer.php?u=<?=base_url() . 'article/view/' .$article->id?>" title="Share this on facebook"><img src="/img/icons/icon_facebook.png"/></a>
	<!-- http://twitter.com/home?status=Microsoft+Mice+Made+in+Chinese+Youth+Sweatshops%3F%3A+http%3A%2F%2Fbit.ly%2Fdvk9yn -->
	<a href="http://twitter.com/home?status=<?=$article->title . '?: ' . base_url() . 'article/view/' .$article->id?>" title="Share this on twitter"><img src="/img/icons/icon_twitter.png"/></a>
</div>
