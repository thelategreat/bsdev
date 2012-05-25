<script type="text/javascript">
function vote()
{
  return false;
}

$(function() {
  $('#slides').cycle();
});

</script>

<div class="row" style="height: 350px;">
  <!-- features -->
  <div class="column grid_8" >
    <div class="feature gradient" style="min-height: 400px;">
    <h3>Featured</h3>
      <div id="slides">
        <?php foreach( $lists['Features'] as $item ) { ?>
          <div class="slide">
            <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
              <img src="<?=$item->media[0]['thumbnail']?>" height="250px" />
            </a>
            <h3><?=$item->title?></h3>
            <div class="caption" style="bottom:0">
              <p><?=$item->excerpt?></p>
            </div>
          </div>
        <?php } ?>
      </div> <!-- slides -->
    </div>
  </div>
  <!-- events -->
  <div class="column grid_4">
    <h3>Upcoming Events</h3>
    <?php foreach( $events->result() as $event ) { ?>
      <a href="/event/details/<?=$event->id?>"><?= $event->title ?></a>
      <?=date('M d', strtotime($event->dt_start)) . ' @ '. date('g:i a',strtotime($event->dt_start))?>
      <p/>
    <?php } ?>
  </div>
</div>

<div class="row" style="height: 250px;">
  <!-- serendipity -->
  <div class="column grid_8">
    <h3>Serendipity</h3>
    <?php foreach( $lists['Serendipity'] as $item ) { ?>
      <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
        <img src="<?=$item->media[0]['thumbnail']?>" height="150px" />
      </a>
    <?php } ?>
  </div>
  <!-- calendar -->
  <div class="column grid_4">
    <h3>Bookshelf Calendar for <?= date('M'); ?></h3>
    <table style="width: 100%; table-layout: fixed;">
    <tr>
      <th>Su</th>
      <th>Mo</th>
      <th>Tu</th>
      <th>We</th>
      <th>Th</th>
      <th>Fr</th>
      <th>Sa</th>
    </tr>
<?php 
      for( $i = 1; $i < 6; $i++ ) {
      echo '<tr>';
        for( $j = 1; $j < 8; $j++ ) { ?>
          <td style="border: 1px solid #666; height: 30px; text-align: center; border-radius: 3px"> o </td>
<?php
      }
      echo '</tr>';
    }
?>
    <tr>
      <td>&lt;</td>
      <td colspan="5" style="text-align: center"></td>
      <td style="text-align: right">&gt;</td>
    </tr>
    </table>
  </div>
</div>

<div class="row">
  <div class="column grid_8">
    <div class="row" style="min-height: 100px;">
      <!-- v3 -->
      <div class="column grid_4">
        <h3>v3</h3>
      </div>
      <!-- v4 -->
      <div class="column grid_4">
        <h3>v4</h3>
      </div>
    </div>
    <div class="row" style="min-height: 100px;">
      <!-- v5 -->
      <div class="column grid_4">
        <h3>v5</h3>
      </div>
      <!-- v6 -->
      <div class="column grid_4">
        <h3>v6</h3>
      </div>
    </div>
  </div>
  <!-- twitter -->
  <div class="column grid_4">
    <h3>Twitter</h3>
  </div>
</div>

<div class="row" style="min-height: 100px;">
  <!-- v7 -->
  <div class="column grid_4">
    <h3>v7</h3>
  </div>
  <!-- v8 -->
  <div class="column grid_4">
    <h3>v8</h3>
  </div>
  <!-- poll -->
  <div class="column grid_4">
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

