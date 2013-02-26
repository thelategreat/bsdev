<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<article id='article' class='tooltip' title='Article'>
					<? $article_start = 0; 
						for ($article_id = 0; $article_id < 1; $article_id++) {

						$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix'>
							<div class="article-header">
								<div class='meta'>
									<time class='date'><?=date('j M Y',strtotime($item->publish_on))?></time>
									<div class='section'><?=$item->group ?></div>
								</div>
								<div class='article-image article-image-main'>
									<img src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/190' />
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
									<a href='<? echo base_url('/articles/tags/'.$tag);?>'><?=$tag;?></a> 
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
					<div style='height:350px;'>
						<h3>Other books and links</h3>
					</div>
					<div style='height:350px;'>
						<h3>Ad space</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>