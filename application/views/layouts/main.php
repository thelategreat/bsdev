<? $this->load->view('common/header.php'); ?>
<body>
<ul class="ym-skiplinks">
	<li><a class="ym-skip" href="#nav">Skip to navigation (Press Enter)</a></li>
	<li><a class="ym-skip" href="#main">Skip to main content (Press Enter)</a></li>
</ul>

<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>The Bookshelf</h1>
		</header>

		<? $this->load->view('common/nav', $nav); ?>

<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<div id='articles' style='height:auto' class='tooltip' title='Main Articles'>
					<? $article_start = 0; 
						for ($article_id = 0; $article_id < 2; $article_id++) {
							if (!isset($lists['_section'][$article_id + $article_start])) continue;
							$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix'>
							<h2><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h2>
							<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/150' /></div>							
							<p><?=$item->excerpt;?></p>							
						</article>
					<? } ?>
				</div>
				<? if (isset($lists['serendipity'])) { ?>
				<div id='serendipity' style='overflow:hidden;height:180px' class='ym-clearfix dash tooltip' title='Serendipity List (random)'>
					<? foreach ($lists['serendipity'] as $item) { ?>
						<div class='list-item list-h' style='float:left;width:200px;height:150px;margin-bottom:10px;'>
							<h6><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h6>
							<div style='text-align:center'>
							<? if (isset($item->media) && isset($item->media[0])) { ?>
								<img src='<?=$item->media[0]['thumbnail'];?>' height='100'/>
							<? } ?>
							</div>
						</div>
					<? } ?>
				</div>
				<? } // serendipity block ?>
				<div class='ym-wrapper'>
					<div class='ym-g66 ym-gl tooltip' title='Secondary Articles'>
						<? $article_start += $article_id; 
							for ($article_id = 0; $article_id < 2; $article_id++) {
								if (!isset($lists['_section'][$article_id + $article_start])) continue;
								$item = $lists['_section'][$article_id + $article_start];
						?>
						<article style='height:150px'>
							<h3><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h3>
							<p><?=$item->excerpt;?></p>
						</article>
						<? } ?>
					</div>
					<div class='ym-g33 ym-gr tooltip' title='Tertiary Articles'>
						<? $article_start += $article_id;
							for ($article_id = 0; $article_id < 3; $article_id++) {
								if (!isset($lists['_section'][$article_id + $article_start])) continue;
								$item = $lists['_section'][$article_id + $article_start];
						?>
						<article style='height:100px'>
							<h4><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h4>
						</article>
						<? } ?>
					</div>
				</div>
			</div>
			<div class="ym-g25 ym-gr" style=''>
				<div style='height:250px;' class='tooltip' title='Schedule'>
					<? foreach ($events as $event) { ?>
						<article class='schedule-event'>
							<? if (isset($event['uuid'])) { ?>
								<a href='/events/details/<?=$event['id'];?>'>
									<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=$event['uuid'];?>/w/50/h/50' /></div>
									<div style='float:left'><h5><?=$event['title'];?></h5><?=$event['dt_start'];?></div>
								</a>
							<? } ?>
						</article>
					<? } ?>
				</div>
				<div id='twitter_feed' class='tooltip' title='Twitter Feed'>
					<?=$tweets; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<? $this->load->view('/common/footer'); ?>
</body>
</html>
