<? $this->load->view('common/header'); ?>
<body>
<ul class="ym-skiplinks">
	<li><a class="ym-skip" href="#nav">Skip to navigation (Press Enter)</a></li>
	<li><a class="ym-skip" href="#main">Skip to main content (Press Enter)</a></li>
</ul>

<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>Bookshelf</h1>
		</header>

		<? $this->load->view('common/nav', $nav); ?>

<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<div id='main_article'>
					<? $article_start = 0; 
						for ($article_id = 0; $article_id < 1; $article_id++) {
						$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix tooltip' title='Main Article'>
							<h2><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h2>
							<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/150' /></div>							
							<p><?=$item->excerpt;?></p>							
						</article>
					<? } ?>
				</div>
				<div class='tooltip' title='Secondary Stories List'>
					<? $article_start = $article_id; 
						for ($article_id = 0; $article_id < 2; $article_id++) {
						$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix'>
							<h3><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h3>
							<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/150/h/150' /></div>							
							<p><?=$item->excerpt;?></p>							
						</article>
					<? } ?>
				</div>
				<div class='ym-wrapper'>									
					<? $article_start += $article_id; 
						for ($article_id = 0; $article_id < 3; $article_id++) {
						if (!isset($lists['_section'][$article_id + $article_start])) continue;
						$item = $lists['_section'][$article_id + $article_start];
					 ?>
					 <div class='ym-g33 ym-gl'>
						<article class='tooltip' title='Tertiary Article'>
							<h4><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h4>
							<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/150' /></div>							
							<p><?=$item->excerpt;?></p>							
						</article>
					</div>						
					<? } ?>
				</div>
				
				
				<? if (isset($lists['position_bottom'])) { 
					$this->load->view('widgets/horizontal_generic_list', array('name'=>'bottom', 'list'=>$lists['position_bottom']));
				} ?>
			</div>
			<div class="ym-g25 ym-gr">
				<div class="tooltip" title="Serendipity List"> 
					<? foreach ($lists['serendipity'] as $item) { ?>
						<div class='list-item list-v' style='height:150px;margin-bottom:10px;'>
							<h6><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h6>
							<div style='text-align:center'>
							<? if (isset($item->media) && isset($item->media[0])) { ?>
								<img src='<?=$item->media[0]['thumbnail'];?>' height='100'/>
							<? } ?>
							</div>
						</div>
					<? } ?>				
				</div>
			</div>
		</div>
	</div>
</div>
<? $this->load->view('/common/footer'); ?>
</body>
</html>
