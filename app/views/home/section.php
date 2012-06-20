<div class="row">
  <div class="column grid_3">
    <!-- col 1 -->
     <h3>Serendipity</h3>
    <?php if( array_key_exists('Serendipity', $lists)) { 
      echo "<ul class='serendipity-list'>";
      foreach( $lists['Serendipity'] as $item ) { ?>
      <li>
      <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
      <?php if( count($item->media)) { ?>
        <img src="<?=$item->media[0]['thumbnail']?>" height="150px" />
      <?php } else { ?>
        <img src="/img/image_not_found.jpg" height="150px" />
      <?php } ?>
      </a>
      <h3>
        <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
        <?=$item->title?></a>
      </h3>
      <?= $item->excerpt?>
      </li>
    <?php } 
    echo '</ul>';
    }
?>
  </div>
  <div class="column grid_6">
  <!-- col 2 -->
    <div class="row" style="height: 350px;">
      <div class="column grid_6">
      <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 0 ) {
         $item = $lists['_section'][0]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 200px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="column grid_3">
       <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 1 ) {
         $item = $lists['_section'][1]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
      <div class="column grid_3">
        <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 2 ) {
         $item = $lists['_section'][2]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="column grid_3">
         <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 3 ) {
         $item = $lists['_section'][3]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
      <div class="column grid_3">
          <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 4 ) {
         $item = $lists['_section'][4]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="column grid_3">
           <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 5 ) {
         $item = $lists['_section'][5]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
      <div class="column grid_3">
         <?php if( array_key_exists('_section',$lists) && count($lists['_section']) > 6 ) {
         $item = $lists['_section'][6]; ?>
           <a href="/article/view/<?=$item->id?>" title="<?=$item->title?>">
          <?php if( count($item->media)) { ?>
            <img src="<?=$item->media[0]['thumbnail']?>" style="float: left; height: 80px; margin: 0 5px 5px 0;" />
          <?php } else { ?>
          <?php } ?>
          <h3><?=$item->title?></h3>
          </a>
          <?=$item->excerpt?>
      <?php } ?>
      </div>
    </div>
  </div>
  <div class="column grid_3">
  <!-- col 3 -->
    <div class="row">
      <div class="column grid_3">
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
    <div class="row">
      <div class="column grid_3">
        <?= $cal ?>
      </div>
    </div>
    <div class="row">
      <div class="column grid_3">
      ad
      </div>
    </div>
    <div class="row">
      <div class="column grid_3">
        <?= $tweets ?>
      </div>
    </div>
  </div>
</div>

