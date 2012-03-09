
<?php foreach( $parents as $parent ) { ?>
	<a href="/home/section/<?=$parent['id']?>"><?= $parent['name'] ?></a>  ❖
<?php } ?>

<!--
<?php if( $events !== NULL && $events->num_rows() > 0 ) { ?>
	<a style="float: right" href="/calendar"><span style="font-size: 90%;">...see the full calendar</span> <img src="/img/fancy_right.png" width="18px" style="margin-bottom: -5px"/></a>
	<h3 style="margin: 0; padding: 0; font-style: italic;">Coming up... </h3>
	<div id="scroller">
		<?php foreach( $events->result() as $event ): ?>
			<div class="section">
				<div class="hp-highlight" style="background:url(/media/<?=$event->uuid?>) no-repeat 0 0">
					<div class="feature-headline film">
						<h1><a href="/events/details/<?=$event->id?>"><?=$event->title?></a></h1>
						<p><?=date('M d', strtotime($event->dt_start)) . ' @ '. date('g:i a',strtotime($event->dt_start))?></p>
					</div>
			  </div>
			</div>
		<? endforeach; ?>
	</div>
<? } ?>
-->

<div class="row" style="height: 450px;">
  <div class="column grid_6">
    <h3>Features</h3>
      <div class="row shadow" style="margin: 6px; border: 1px solid #333; height: 385px; width: 96%;">
        <div class="column grid_6" style="">
          <div style="overflow: scroll; width: 1000px; height: 200px; text-align: center;">
            foo
          </div>  
        </div>
      </div>
    <!--
    <div class="row shadow" style="margin: 1px; border: 1px solid #333;">
     <div class="column grid_3" style="">
        <img src="<?=$articles[0]->media[0]['thumbnail']?>" style="width: 225px; height: 175px;" />
      </div>
      <div class="column grid_2">
        <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[0]->id?>"><?= $articles[0]->title ?></a></h4>
		    <?= strlen(trim($articles[0]->excerpt)) ? implode(' ', array_slice(explode(' ', $articles[0]->excerpt),0,30)) . '...' : implode(' ', array_slice(explode( ' ', $articles[0]->body),0,30) ) . '...' ?>
		    <p style="font-style: italic">by: <span class="date"><?=$articles[0]->author?>. - <?=date('j M Y',strtotime($articles[0]->publish_on))?> - <?=$articles[0]->group ?></span></p>
      </div>
    </div>
    <p/>
    <div class="row" style="padding-top: 10px;">
      <div class="column grid_2">
        <img src="<?=$articles[1]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
        <p><a href="/article/view/<?=$articles[1]->id?>"><?= $articles[1]->title ?></a></p>
      </div>
       <div class="column grid_2">
        <img src="<?=$articles[2]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
        <p><a href="/article/view/<?=$articles[2]->id?>"><?= $articles[2]->title ?></a></p>
      </div>
      <div class="column grid_2">
        <img src="<?=$articles[3]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
        <p><a href="/article/view/<?=$articles[3]->id?>"><?= $articles[3]->title ?></a></p>
      </div>
    </div>
    -->
  </div>
  <div class="column grid_6">
    <div class="row" style="height: 225px;">
      <div class="column grid_6">
        <h3>@Bookstore</h3>
        <div style="width: 180px; float: left;">
          <img src="<?=$articles[4]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
          <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[4]->id?>"><?= $articles[4]->title ?></a></h4>
          <p>blurb.....</p>
        </div>
        <div>
          <img src="<?=$articles[5]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
          <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[5]->id?>"><?= $articles[5]->title ?></a></h4>
          <p>blurb.....</p>
        </div>
      </div>
    </div>
    <div class="row" style="border-top: 1px solid #aaa;">
      <div class="column grid_6">
        <h3>@Cinema</h3>
         <div style="width: 180px; float: left;">
          <img src="<?=$articles[6]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
          <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[6]->id?>"><?= $articles[6]->title ?></a></h4>
          <p>blurb.....</p>
        </div>
        <div>
          <img src="<?=$articles[7]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
          <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[7]->id?>"><?= $articles[7]->title ?></a></h4>
          <p>blurb.....</p>
        </div>
     </div>
    </div>
  </div>
</div>

<div class="row" style="border-top: 1px solid #aaa; margin-top: 15px;">
  <div class="column grid_2" style="height: 175px;">
    <h3 style="text-align: center;">@eBar</h3>
  </div>
  <div class="column grid_2" style="margin: 5px;">
    <img src="<?=$articles[8]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
    <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[8]->id?>"><?= $articles[8]->title ?></a></h4>
    <p>blurb.....</p>
  </div>
  <div class="column grid_2" style="margin: 5px;">
    <img src="<?=$articles[9]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
    <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[9]->id?>"><?= $articles[9]->title ?></a></h4>
    <p>blurb.....</p>
  </div>
  <div class="column grid_2" style="margin: 5px;">
    <img src="<?=$articles[8]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
    <p>blurb.....</p>
 </div>
  <div class="column grid_2" style="margin: 5px;">
    <img src="<?=$articles[8]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
    <p>blurb.....</p>
 </div>
  <div class="column grid_2" style="margin: 5px;">
    <img src="<?=$articles[8]->media[0]['thumbnail']?>" style="height: 100px;" class="shadow" />
    <p>blurb.....</p>
 </div>
</div>

<div class="row" style="border-top: 1px solid #aaa;">
  <div class="column grid_6">
    <h3 style="text-align: center;">News and Views</h3>
    <?php for( $i = 0; $i < 4; $i++ ) { ?>
    <div class="row" style="margin-bottom: 15px;">
      <div class="column grid_3">
        <img src="<?=$articles[$i]->media[0]['thumbnail']?>" class="shadow" style="height: 100px; width: 100px; float: right;" />
        <h4 style="margin: 2px 0;"><a href="/article/view/<?=$articles[$i]->id?>"><?= $articles[$i]->title ?></a></h4>
      </div>
      <div class="column grid_3">
		    <?= strlen(trim($articles[$i]->excerpt)) ? $articles[$i]->excerpt : implode(' ', array_slice(explode( ' ', $articles[$i]->body),0,100) ) . '...' ?>
      </div>
    </div>
    <?php } ?>

    <h3 style="text-align: center">Columns</h3>
    <div class="row">
      <div class="column grid_6" style="height: 150px; background: #ddd;">
      </div>
    </div>

    <h3>Quote of the Day</h3>
    <blockquote>
      <p>We must rediscover the distinction between hope and expectation.</p>
    </blockquote>     
    <cite>Ivan Illich</cite>
  </div>
  <div class="column grid_6">
    <h3 style="text-align: center;">Other stuffs</h3>
    <div class="row">
      <div class="column grid_3">
        <div style="background: #16f6fa; text-align: center; padding: 5px; border: 1px solid #005;">
          <h4 style="margin: 2px 0;">Dinner and a Movie</h4>
          <p>
            $20.00 buys you a night on the town. A delicious dinner in the
            Bookshelf Greenroom and reserved seats in the Cinema.
          </p>
          <p>
            Phone 519-821-3311 to reserve
          </p>
        </div>
        <p/>
        <div style="background: #00f; text-align: center; padding: 5px; border: 1px solid #005;">
          <h4 style="color: white; margin: 2px 0;">Bookshelf Gift Cards</h4>
          <p style="font-weight: bold;">
            Uncertain what to buy? Let them decide! Click here to order
            Bookshelf Gift Cards or purchase them at the till.
          </p>
        </div>
      </div>
      <div class="column grid_3">
        <div style="border: 1px solid #bbb; background: #eee; text-align: center;">
          <h3>Follow Us</h3>
          <img src="/img/social/32/facebook.png" />
          <img src="/img/social/32/twitter.png" />
          <img src="/img/social/32/digg.png" />
          <img src="/img/social/32/reddit.png" />
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// execute your scripts when the DOM is ready. this is mostly a good habit
$(function() {

	// initialize scrollable
	$(".scrollable").scrollable();

});
</script>


