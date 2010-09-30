<?
 	 // this is a cheat for the pg_data which I haven't migrated completely yet
	if( !isset($section)) {
		$section = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title><?= $page_title ?></title>
	
	<!-- style -->
	<link rel="stylesheet" href="/css/style.css" type="text/css"  media="screen" />
	
	<!-- javascript -->
	<script type="text/javascript" src="/js/jquery-1.3.2.min.js" ></script>
	
</head>

<body class="<?=$css_name?>">
	<div id="container">
		<div id="header-nav">
			<ul>
				<li><a href="/cart" title="Shopping Cart: <?= $cart->total_items() ?> items"><img src="/img/icons/black/shop_cart.png" style="height: 20px;"/> <?= $cart->total_items() ?> </a></li>
				<li><a href="/profile" title="My Bookshelf"><img src="/img/icons/black/user.png" style="height: 20px"/></a></li>
				<li><a href="/page/help" title="Help/FAQ"><img src="/img/icons/black/info.png" style="height: 20px"/> </a></li>
			</ul>
			<div id="search">
				<form id="main_search" action="/search" method="post">
				<select name="group">
					<option>all</option>
					<option>books</option>
					<option>cinema</option>
					<option>ebar</option>
				</select>
				<input type="search" size="13" name="q" />
				<img src="/img/icons/black/zoom.png" style="height: 20px; margin-bottom: -5px"/>
				</form>
			</div>
		</div>
		<div id="header">
			<a href="/"><img style="float: left; height: 100px" src="/img/wv/wv_logo_100.png" /></a>
			<a href="/"><img style="height: 60px; align: center;" src="/img/wv/wv_header_100.png" /></a>
			<p style="margin-left: 150px; font-style: italic">Monday October 4th - Monday October 11th 2010</p>
				<!-- Bookshelf -->
		</div>
		<div id="navigation">
			<ul>
				<li><a href="/" <?= $section == "home" ? 'class="selected"' : ''?>>Home</a></li>
				<li><a href="/home/section/books" <?= $section == "books" ? 'class="selected"' : ''?>>Books</a></li>
				<li><a href="/home/section/cinema" <?= $section == "cinema" ? 'class="selected"' : ''?>>Cinema</a></li>
				<li><a href="/home/section/ebar" <?= $section == "ebar" ? 'class="selected"' : ''?>>eBar</a></li>
			</ul>
		</div>
		<div id="content-container1">
			<div id="content-container2">
				<?php if( isset($sidebar_left)) { ?>
				<div id="sidebar-left">
					<?= $sidebar_left ?>
				</div>
				<?php } ?>
				<div id="content">
					<?= $content ?>
				</div>
				<div id="sidebar-right">
					<?= $sidebar_right ?>
				</div>
				<div id="pre-footer">
					<?= $ad_footer ?>
				</div>
				<div id="footer">
					<div id="footer-nav">
						<ul>
							<li><a href="/page/contact">Contact</a></li>
							<li><a href="/page/about">About</a></li>
							<li><a href="/page/location">Directions/Hours</a></li>
							<li><a href="/page/legal">Legal/Privacy</a></li>
						</ul>
					</div>
					Copyright © Bookshelf, 2010
				</div>
			</div>
		</div>
	</div>

</body>
</html>

