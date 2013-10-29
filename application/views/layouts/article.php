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
		<?= $tweets ?>	
	</div>
</div>

<div id="main_content" class="subpage">
	<div id='featured_column'>
		<div class='columnBlock'>
	      <h1 class='book'><?=$item->section;?></h1>
										
			<div class='imageFloat'>
				<img src='<? echo imageLinkHelper($item, $width=170, $height=false); ?>' width=170/>
				<h2><?=date('j M Y',strtotime($item->publish_on))?></h2>
				<h3 class='byline'><?=$item->author?></h3>
			</div>
		
		<h2><?=$item->title?></h2>
		<div id='article_body'><?=$item->body;?></div>

		<div class='article-tags'>
			<? if (isset($tags) && count($tags) > 0) { ?>
			<div class='title'>FILE UNDER</div>
				<div class='tags'>
				<? foreach ($tags as $tag) { ?>
					<a href='<? echo base_url('/search/tags/'.$tag);?>'><?=$tag;?></a> 
				<? } ?>
				</div>
			</div>
			<? } ?>		
		</div>
	</div>
</div>

<div id='list_right'>

	List right
</div>

	<? /*	
			<div class="ym-g25 ym-gr">
				<div class="ym-wbox sidebar">
					<? if (isset($associated_products)) { ?>
					<div id='associated_products'>
					<? foreach($associated_products as $item) { ?>
						<aside class='associated-product' id='associated_<?=$item['id'];?>'>
							<a href="<? echo base_url('/product/view/' . $item['id']); ?>"><h2><?=$item['title'];?></h2></a>
							<div class='author'><?=$item['contributor'];?></div>
							<div class='category'><?=$item['bisac_text'];?></div>
							<div class='pages'><?=$item['pages'];?> pages</div>
							<div class='price'>$ <?=$item['bs_price'];?></div>
						</aside>						
					<? } } ?>
					</div>					
					<? if (isset($associated_events)) { ?>
					<div id='associated_events'>
					<h2 class='title'>Events</h2>
					<? foreach($associated_events as $item) { ?>
						<aside class='associated-event' id='associated_<?=$item['id'];?>'>
							<a href="<? echo base_url('/events/details/' . $item['id']); ?>"><h2><?=$item['title'];?></h2></a>
							<div class='venue'><?=$item['venue'];?></div>
							<div class='time'><? if (date('Y-m-d', strtotime($item['dt_start'])) == date('Y-m-d', strtotime($item['dt_end']))) { ?>
								<?=date('D M d', strtotime($item['dt_start']));?><br>
								<?=date('g:i a', strtotime($item['dt_start']));?> to <?=date('g:i a', strtotime($item['dt_end']));?>
							<? } else { ?>
								<?=date('D M d g:i a', strtotime($item['dt_start']));?> to <?=date('D M d g:i a', strtotime($item['dt_end']));?>
							<? } ?>
							</div>
						</aside>						
					<? } 
					}?>
					</div>					
				</div>
			</div>

			*/ ?>

			<div style='clear:both'></div>
		</div>
	</div>
</div>