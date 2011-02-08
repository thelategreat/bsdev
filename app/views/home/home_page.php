<script type="text/javascript" src="/js/jquery.simplyscroll-1.0.4.min.js"></script>

<script type="text/javascript">
$(function() {
	$("#scroller").simplyScroll({
				className: 'custom',
				autoMode: 'loop',
				pauseOnHover: false,
				frameRate: 20,
				speed: 2
			});
	/*
	$("#events-preview a[title]").tooltip({ position: "top center", opacity: 0.99, offset: [-60,10], effect: "slide"});
	*/
	/*
	var count = $('#events-preview ul li').size();
	$('#events-preview').css('height','250px');
	//$('#events-preview').css('width','600px');
	$('#events-preview').css('overflow','hidden');
	$('#events-preview ul li').each( function(ndx) {
		$(this).css('display','none');
		$("img", this).css('width','300px');
		$("span", this).css('top', $(this).parent().parent().offset().top + 3);
		$("span", this).css('left', $(this).parent().parent().offset().left + 3);
		$("span", this).css('width', $('#events-preview').width);
		//$("span", this).css('height', '135px');
		$("span", this).css('display', 'none');
	});
	$('#events-preview ul li').first().fadeIn('slow');
	$('span',$('#events-preview ul li').first()).fadeIn('slow');

	var cur_image = 0;
	timer = setInterval( nextImage, 7000 );

	function nextImage()
	{
		cur_image++;
		if( cur_image >= $('#events-preview ul li').size()) {
			cur_image = 0;
		}
		$('#events-preview ul li').each( function(ndx) {
			if( ndx == cur_image ) {
				$(this).fadeIn('slow');				
				$("span", this).show('slow');
			} else {
				$(this).css('display','none');
				$("span", this).css('display', 'none');
			}
		});
	}
	*/
});
</script>

<?php foreach( $parents as $parent ) { ?>
	<a href="/home/section/<?=$parent['id']?>"><?= $parent['name'] ?></a>  ❖
<?php } ?>

<?php if( $events !== NULL ) { ?>
	<a style="float: right" href="/calendar"><span style="font-size: 90%;">...see the full calendar</span> <img src="/img/fancy_right.png" width="18px" style="margin-bottom: -5px"/></a>
	<h3 style="margin: 0; padding: 0; font-style: italic;">Coming up... </h3>
	<!---->
	<div id="scroller">
		<?php foreach( $events->result() as $event ): ?>
			<div class="section">
				<div class="hp-highlight" style="background:url(/media/<?=$event->uuid?>) no-repeat 0 0">
					<div class="feature-headline">
						<h1><a href="/events/details/<?=$event->id?>"><?=$event->title?></a></h1>
						<p><?=date('M d', strtotime($event->dt_start)) . ' @ '. date('g:i a',strtotime($event->dt_start))?></p>
					</div>
			  </div>
			</div>
		<? endforeach; ?>
	</div>
<? } ?>

<?php 
	$count = 0;
	foreach( $articles as $article ) { ?>
	<div class="article">
		<?php if( count($article->media) > 0 ) { $uuid = $article->media[0]['uuid']; ?>
			<!--<img src="/media/<?=$uuid?>" class="<?= $count % 2 > 0 ? 'img-preview-left' : 'img-preview-right'?>" />-->
      <img src="<?=$article->media[0]['thumbnail']?>" class="<?= $count % 2 > 0 ? 'img-preview-left' : 'img-preview-right'?> <?=$article->media[0]['type']?>"
              />
		<?php $count++; } ?>
		<h2><a href="/article/view/<?=$article->id?>"><?= $article->title ?></a></h2>
		<span class="date"><?=$article->author?>. - <?=date('j M Y',strtotime($article->publish_on))?> - <?=$article->group ?></span>
		<p><?= strlen(trim($article->excerpt)) ? $article->excerpt : implode(' ', array_slice(explode( ' ', $article->body),0,100) ) . '...' ?>
		<p class="read-more"><a href="/article/view/<?=$article->id?>"><em>read more...<img src="/img/big_feature_right_arrow.png" width="18px" style="margin-bottom: -4px"/></em></a><p>
	</div>
<?php } ?>

<?= $pagination ?>