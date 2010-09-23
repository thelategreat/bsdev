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
				<li><a href="/cart" title="Shopping Cart: <?= $cart->total_items() ?> items"><img src="/img/icons/shop_cart.png" style="height: 20px"/> <?= $cart->total_items() ?> </a></li>
				<li><a href="/profile" title="My Bookshelf"><img src="/img/icons/user.png" style="height: 20px"/></a></li>
				<li><a href="/page/help" title="Help/FAQ"><img src="/img/icons/info.png" style="height: 20px"/> </a></li>
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
				<img src="/img/icons/eye.png" style="height: 20px; margin-bottom: -5px"/>
				</form>
			</div>
		</div>
		<div id="header">
			<h1>
				<!-- Bookshelf -->
				<a href="/">ook<span style="color: #fff">shelf</span></a>
			</h1>
		</div>
		<div id="navigation">
			<ul>
				<li><a href="/home/section/books" <?= $section == "books" ? 'class="selected"' : ''?>><img src="/img/icons/book.png" style="height: 20px; margin-bottom: -5px"/> Books</a></li>
				<li><a href="/home/section/cinema" <?= $section == "cinema" ? 'class="selected"' : ''?>><img src="/img/icons/movie.png" style="height: 20px; margin-bottom: -5px"/> Cinema</a></li>
				<li><a href="/home/section/ebar" <?= $section == "ebar" ? 'class="selected"' : ''?>><img src="/img/icons/music.png" style="height: 20px; margin-bottom: -5px"/> eBar</a></li>
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

