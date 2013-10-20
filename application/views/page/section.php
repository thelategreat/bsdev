<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_subpage.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_subpage.css');?>" />

<div id="list_left">
	<? if (isset($events) && count($events) > 0) { ?>
	<div class="section_heading">
		<h2>
			Event Highlights
		</h2>
	</div>
	<? if (isset($events)) foreach ($events as $it) { ?>
      <div class="sidebar_item <? if ($it === end($events)) echo 'last' ?>">
        <h1 class="<?=$it->category;?>"><?=ucwords($it->category);?></h1>
        <a href="/events/<?=$it->id;?>"><h2><?=$it->title;?></h2></a>
        <h3><?= date('D M j, g:i A', strtotime($it->start_time)); ?></h3>
      </div>
    <? } ?>	
   	<? } ?>
	<div class="section_heading">
		<h2>
			@Bookshelf News
		</h2>
	</div>
	<div class="sidebar_item">
		<? /*= $tweets */?>	
	</div>
</div>

<div id="main_content" class="subpage">
	<div id='featured_column'>
		<? 
		$i = 0;
		foreach ($articles as $item) { 
			if ($i++ >= $this->config->item('section_list_length')) break;
			?> 
		<div class="columnBlock">
            <!---the is an item in the list always gien a class of columnBlock-->
			<div class='imageFloat'>
				<img src='<? echo imageLinkHelper($item, $width=170, $height=false); ?>' width=170/>
				<h2><?=date('j M Y',strtotime($item->publish_on))?></h2>
				<h3 class='byline'><?=$item->author?></h3>
			</div>
            <!--end of the image and caption float-->
			<h2><?=$item->title?></h2>
			<div class='article_body'><?=$item->body;?></div>
          </div>
          <!--end of the first even item-->
		<div class='article-tags'>
			<? if (isset($tags) && count($tags) > 0) { ?>
			<div class='title'>FILE UNDER</div>
				<div class='tags'>
				<? foreach ($tags as $tag) { ?>
					<a href='<? echo base_url('/search/tags/'.$tag);?>'><?=$tag;?></a> 
				<? } ?>
				</div>
			<? } ?>		
			</div>
			<? } ?>
		</div>

	</div>
</div>

<div id='list_right'>
	<? if (isset($lists['right'])) {
		new dBug($lists['right']);
	} ?>
</div>

		<div class='clear'></div>

			<div style='clear:both'></div>
		</div>
	</div>
</div>

<? /*
<div id="section_content">
	<h2><?= $title ?></h2>
	<?= $body ?>

<? if (isset($lists['top'])) {
	new dBug($lists['top']);
} ?>

<? if (isset($lists['middle'])) {
	new dBug($lists['middle']);
} ?>

<? if (isset($lists['sidebar'])) {
	new dBug($lists['sidebar']);
} ?>

<? if (isset($lists['bottom'])) {
	new dBug($lists['bottom']);
} ?>
</div>
*/ ?>