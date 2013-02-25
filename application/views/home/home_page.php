<script type="text/javascript">
function vote()
{
  return false;
}

$(function() {
  $('#slides').cycle({
    delay: 3000,
    speed: 500,
  });
  $('#content a[tooltip]').each(function() {
    $(this).qtip({
      content: $(this).attr('tooltip'),
        style: 'dark'
    });
  });
});

</script>

<div class="row" style="height: 350px;">
  <!-- features -->
  <div class="column grid_8" >
    <div class="feature gradient" style="min-height: 300px; border-radius: 5px;">
    <h3>Featured</h3>
      <div id="slides">
        <?php if( array_key_exists('Features', $lists)) { 
          foreach( $lists['Features'] as $item ) { ?>
          <div class="slide">
            <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
              <?php if( count($item->media)) { ?>
              <img src="<?=$item->media[0]['thumbnail']?>" height="250px" />
              <?php } else { ?>
              <?php } ?>
            </a>
            <h3>
              <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
              <?=$item->title?></a>
            </h3>
            by <?= $item->author ?>
            <div class="caption" style="bottom:0">
              <p><?=$item->excerpt?></p>
            </div>
          </div>
        <?php } 
        }
        ?>
      </div> <!-- slides -->
    </div>
  </div>
  <!-- events -->
  <div class="column grid_4">
    <h3>Upcoming Events</h3>
    <ul class="events-list">
    <?php foreach( $events->result() as $event ) { ?>
      <li>
      <img src="/media/<?=$event->uuid?>" /> 
      <a href="/events/details/<?=$event->id?>"><?= $event->title ?></a>
      <span class="date"><?=date('M d', strtotime($event->dt_start)) . ' @ '. date('g:i a',strtotime($event->dt_start))?></span>
      </li>
    <?php } ?>
    </ul>
  </div>
</div>

<div class="row" style="height: 250px; border-bottom: 1px dotted #ddd;">
  <!-- serendipity -->
  <div class="column grid_8" style="overflow: auto;" >
    <div style="width: 1000px;">
    <h3>Serendipity</h3>
    <?php if( array_key_exists('Serendipity', $lists)) { 
      foreach( $lists['Serendipity'] as $item ) { ?>
      <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
      <?php if( count($item->media)) { ?>
        <img src="<?=$item->media[0]['thumbnail']?>" height="150px" />
      <?php } else { ?>
        <img src="/img/image_not_found.jpg" height="150px" />
      <?php } ?>
      </a>
    <?php } 
    }
?>
    </div>
  </div>
  <!-- calendar -->
  <div class="column grid_4">
    <h3><a href="/calendar">Bookshelf Calendar for <?= date('M'); ?></a></h3>
    <?= $cal ?>
  </div>
</div>

<div class="row">
  <div class="column grid_8">
    <div class="row" style="min-height: 100px;">
      <!-- v3 -->
      <div class="column grid_4 article-preview">
        <?php if( array_key_exists('v3', $lists)) { 
          if( count($lists['v3']) > 0 )  {  $item = $lists['v3'][0]; ?>
          <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
          <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" />
          <?php } else { ?>
            <img src="/img/image_not_found.jpg" />
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
<?php
          } 
        }
        ?>
     </div>
      <!-- v4 -->
      <div class="column grid_4 article-preview">
         <?php if( array_key_exists('v4', $lists)) { 
          if( count($lists['v4']) > 0 )  {  $item = $lists['v4'][0]; ?>
          <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
          <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" />
          <?php } else { ?>
            <img src="/img/image_not_found.jpg" />
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
        <?php } 
        }
        ?>
     </div>
    </div>
    <div class="row" style="min-height: 100px;">
      <!-- v5 -->
      <div class="column grid_4 article-preview">
         <?php if( array_key_exists('v5', $lists)) { 
          if( count($lists['v5']) > 0 )  {  $item = $lists['v5'][0]; ?>
          <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
          <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" />
          <?php } else { ?>
            <img src="/img/image_not_found.jpg" />
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
        <?php } 
        }
        ?>
     </div>
      <!-- v6 -->
      <div class="column grid_4 article-preview">
         <?php if( array_key_exists('v6', $lists)) { 
          if( count($lists['v6']) > 0 )  {  $item = $lists['v6'][0]; ?>
          <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
          <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" />
          <?php } else { ?>
            <img src="/img/image_not_found.jpg" />
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
        <?php } 
        }
        ?>
     </div>
    </div>
  </div>
  <!-- twitter -->
  <div class="column grid_4">
    <?= $tweets ?>
  </div>
</div>

<div class="row" style="min-height: 100px;">
  <!-- v7 -->
  <div class="column grid_4 article-preview">
    <?php if( array_key_exists('v7', $lists)) { 
      if( count($lists['v7']) > 0 )  {  $item = $lists['v7'][0]; ?>
        <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
        <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
        <?php if( count($item->media)) { ?>
          <img src="<?=$item->media[0]['thumbnail']?>" />
        <?php } else { ?>
          <img src="/img/image_not_found.jpg" />
        <?php } ?>
        <h3><?=$item->title?></h3>
        </a>
        <?=$item->excerpt?>
      <?php } 
      }
      ?>
 </div>
  <!-- v8 -->
  <div class="column grid_4 article-preview">
    <?php if( array_key_exists('v8', $lists)) { 
      if( count($lists['v8']) > 0 )  {  $item = $lists['v8'][0]; ?>
        <a href="/home/section/<?=$item->group_id?>" class="section"><?=$item->group_name?></a>
          <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
          <img src="<?=$item->media[0]['thumbnail']?>" />
          <?php } else { ?>
            <img src="/img/image_not_found.jpg" />
          <?php } ?>
        <h3><?=$item->title?></h3>
        </a>
        <?=$item->excerpt?>
        <?php } 
        }
        ?>
 </div>
  <!-- poll -->
  <div class="column grid_4" style="border-top: 1px dotted #ddd;">
    <div id="poll-container">
      <h3>Poll: <?=$poll->question?></h3>
      <form id="poll" action="" method="post" >
      <?php for($i = 1; $i <= count($poll->answers); $i++ ) { ?>
        <input type="radio" name="poll" value="<?=$poll->answers[$i-1]->id?>" id="opt$i"><label for="opt$i"><?=$poll->answers[$i-1]->answer?></label><br/>
      <?php } ?>
      <input type="submit" value="Vote" onclick="return vote();"/>
      </form>
    </div>
  </div>
</div>
<p/>
