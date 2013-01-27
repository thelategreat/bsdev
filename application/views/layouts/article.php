<? $this->load->view('common/header'); ?>
<ul class="ym-skiplinks">
	<li><a class="ym-skip" href="#nav">Skip to navigation (Press Enter)</a></li>
	<li><a class="ym-skip" href="#main">Skip to main content (Press Enter)</a></li>
</ul>

<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>Bookshelf</h1>
		</header>
<nav id="nav">
	<div class="ym-wrapper">
		<div class="ym-hlist">
			<ul>
				<li><a href="/">Main page</a></li>
				<li><a href="/beta/section">Section</a></li>
				<li class='active'><a href="/beta/article"><strong>Article</strong></a></li>
			</ul>
			<form class="ym-searchform">
				<input class="ym-searchfield" type="search" placeholder="Search..." />
				<input class="ym-searchbutton" type="submit" value="Search" />
			</form>
		</div>
	</div>
</nav>
<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<article id='articles' class='tooltip' title='Article'>
					<? $article_start = 0; 
						for ($article_id = 0; $article_id < 1; $article_id++) {

						$item = $lists['_section'][$article_id + $article_start];
					 ?>
						<article class='ym-clearfix'>
							<div class="article-header">
								<h2><?=$item->title?></h2>
								<span class="date"><?=$item->author?>. - <?=date('j M Y',strtotime($item->publish_on))?> - <?=$item->group ?></span>
							</div>
							<div style='float:right'><img style='margin:5px' src='/i/size/o/<?=substr($item->media[0]['thumbnail'], strrpos($item->media[0]['thumbnail'],'/')+1);?>/w/300' /></div>							
							<p><?=$item->body;?></p>							
						</article>
					<? } ?>		
				</article>
			</div>
			<div class="ym-g25 ym-gr" style='; border: 1px dashed #aac'>
				<div style='height:350px;'>
					<h3>Book buying info</h3>
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
<footer>
	<? $this->load->view('common/footer'); ?>
</footer>
<!-- full skip link functionality in webkit browsers -->
<script src="../yaml/core/js/yaml-focusfix.js"></script>
</body>
</html>