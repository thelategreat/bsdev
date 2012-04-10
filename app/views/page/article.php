<script type="text/javascript">
function vote( id, vote )
{
  $.post('/comment/vote', {id: id, vote: vote},
		function( data ) {
      if( data.ok ) {
        alert(data.msg);
        window.location.reload(true);
			} else {
				alert(data.msg);
			}
    }, 'json');
}
</script>
<div class="article">
	<div class="article-header">
		<h3><?=$article->title?></h3>
		<span class="date"><?=$article->author?>. - <?=date('j M Y',strtotime($article->publish_on))?> - <?=$article->group ?></span>
	</div>

	
	<?php if( count($images)) {
    if( $images[0]['type'] == 'link') {
        echo get_embed_object( $images[0]['fname'] );
   } else { ?>
			<img src="<?=$images[0]['thumbnail']?>" style="width: 150px; margin: 10px; float: right; margin-top: -20px" />
  <?php
   }
 } ?>

	<div class="article-body">
		<?=$article->body?>
	</div>
</div>


<div style="float: right">
	<!-- http://www.facebook.com/sharer.php?u=http://news.slashdot.org/story/10/04/15/1214248/Microsoft-Mice-Made-in-Chinese-Youth-Sweatshops -->
	<a href="http://www.facebook.com/sharer.php?u=<?=base_url() . 'article/view/' .$article->id?>" title="Share this on facebook"><img src="/img/icons/icon_facebook.png"/></a>
	<!-- http://twitter.com/home?status=Microsoft+Mice+Made+in+Chinese+Youth+Sweatshops%3F%3A+http%3A%2F%2Fbit.ly%2Fdvk9yn -->
	<a href="http://twitter.com/home?status=<?=$article->title . '?: ' . base_url() . 'article/view/' .$article->id?>" title="Share this on twitter"><img src="/img/icons/icon_twitter.png"/></a>
</div>

<?php if( $allow_comments) { ?>

<h3>User Comments</h3>
<div style="background-color: #eee; border: 1px solid #999; padding: 5px; border-radius: 5px;">
<?php if( $can_comment ) { ?>
	<small><span onclick="$('#comment-form').toggle('slow')" style="cursor: hand;" /><img src="/img/comments_add.png" style="margin-bottom: -5px" /> add comment</span></small>
	<div id="comment-form" style="display: none;">
		<form action="/comment/add" method="post">
			<input name="redir" type="hidden" value="/article/view/<?=$article->id?>" />
			<input name="type" type="hidden" value="articles" />
			<input name="id" type="hidden" value="<?=$article->id?>" />
			<textarea rows="5" cols="60" name="comment"></textarea><br/>
			<input type="submit" name="add" value="Add Comment" />
		</form>
		<p class="info"><small>Please note that comments are subject to approval and may not appear right away</small></p>
	</div>
<?php } else { ?>
	<small><i>Please <a href="/profile/login">log in</a> to comment</i></small>
<?php } ?>
<br/>
<small><i><b>Fine print:</b> the following comments are owned by whoever posted them.</small></i><br/>
</div>

<?php foreach( $comments as $comment ) { ?>
	<div class="comment">
    <span class="author">
      posted by: <?=$comment->firstname . ' ' . $comment->lastname ?> on <?=date('j M Y',strtotime($comment->comment_date))?> @ <?=date('g:i a',strtotime($comment->comment_date))?>
      <?php if( $can_comment ) { ?>
        <button onclick="vote(<?=$comment->id?>, 1);"><img src="/img/thumbs_up.png" style="width: 18px;" /></button>&nbsp;
        <button onclick="vote(<?=$comment->id?>,-1);"><img src="/img/thumbs_down.png" style="width: 18px;" /></button>
      <?php } ?>
        score: <?= $comment->votes ?>
    </span>
		<p>
			<?= htmlspecialchars($comment->comment) ?>
		</p>
	</div>
<?php } ?>

<?php } // if( $allow_comments ) ?>
