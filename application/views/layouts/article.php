<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<article id='article' class='tooltip' title='Article'>
					<? $article_start = 0; 
						for ($article_id = 0; $article_id < 1; $article_id++) {

						//$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix'>
							<div class="article-header">
								<div class='meta'>
									<time class='date'><?=date('j M Y',strtotime($item->publish_on))?></time>
									<div class='section'><?=$item->group ?></div>
								</div>
								<div class='article-image article-image-main'>
									<img src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/200' />
								</div>
								
								<h1 class='title'><?=$item->title?></h1>
								<h2 class='byline'><?=$item->author?></h2>							
							</div>
							<div class='article-content'>			
								<div id='teaser'><?=$item->excerpt;?></div>						
								<div id='article_body'><?=$item->body;?></div>
							</div>
							
							<div class='article-tags'>
								<? if (count($tags) > 0) { ?>
								<div class='title'>FILE UNDER</div>
								<div class='tags'>
								<? foreach ($tags as $tag) { ?>
									<a href='<? echo base_url('/search/tags/'.$tag);?>'><?=$tag;?></a> 
								<? } ?>
								</div>
								<? } ?>
							</div>

						</article>
					<? } ?>		
				</article>
			</div>
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
					<? } ?>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>