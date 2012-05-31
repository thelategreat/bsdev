<?
 	 // this is a cheat for the pg_data which I haven't migrated completely yet
	if( !isset($section)) {
		$section = "";
	}
	
	//if( is_mobile_browser()) {
	//	redirect('iph');
	//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title><?= $page_title ?></title>
	
	<!-- style -->
	<link rel="stylesheet" href="/css/style.css" type="text/css"  media="screen" />
	
	<!-- javascript
	<script type="text/javascript" src="/js/jquery-1.3.2.min.js" ></script>
  -->
	<script type="text/javascript" src="/js/jquery.tools.min.js" ></script>
	<script type="text/javascript" src="/js/jqModal.js"></script>
  <script type="text/javascript" src="/js/jquery.qtip.min.js"></script> -->
  <script type="text/javascript" src="/js/jquery.cycle.lite.js"></script> -->

</head>

<body class="<?=$css_name?>">
  <div id="container">
    <div id="venue-nav">
      <ul>
        <li style="background: #C79D12;"><a href="/">Home</a></li>
        <li style="background: #BA9314;"><a href="/browse/books">Bookstore</a></li>
        <li style="background: #967711;"><a href="/calendar">Cinema</a></li>
        <li style="background: #695209;"><a href="/">eBar</a></li>
        <li style="background: #423406;"><a href="/">Restaurant</a></li>
      </ul>
    </div> <!-- venue-nav -->
		<div id="header-nav">
					<form id="main_search" action="/search" method="post">
					<select style="display: none" name="type" id="search-type">
						<option value="books">books</option>
						<option value="events">events</option>
						<option value="articles">articles</option>
					</select>
					<input style="margin: -30px;" type="search" size="10" name="q" placeholder="search..." />
					<button style=""><img style="" src="/img/icons/black/zoom.png" width="16px"/></button>
				</form>
		<ul>
				<li><a href="/cart" title="Shopping Cart: <?= $cart->total_items() ?> items"><img src="/img/icons/black/shop_cart.png" style="height: 20px;"/> <span style="font-size: 11px; color: white;"><?= $cart->total_items() ?></span></a></li>
				<li><a href="/profile" title="My Bookshelf"><img src="/img/icons/black/user.png" style="height: 20px"/></a></li>
				<!--<li><a href="/page/help" title="Help/FAQ"><img src="/img/icons/white/info.png" style="height: 20px"/> </a></li> -->
			</ul>
		</div>
    <div id="header">
      <!-- <span>The</span><h1>Bookshelf</h1>
      <p>Books, Movies, Music, Food, Conversation</p> -->
      <!--
			<h1>ookshelf</h1>
			<a href="/"><img style="float: left; height: 100px" src="/img/wv/wv_logo_100.png" /></a>
			<a href="/"><img style="height: 60px; align: center;" src="/img/wv/wv_header_100.png" /></a>
			<p>Monday October 4th - Monday October 11th 2010</p>
			-->
				<!-- Bookshelf -->
		</div>
		<div id="nav">
      <?= $main_content_nav ?>
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
            <?= $footer ?>
					</div>
          Copyright © Bookshelf, <?= date('Y'); ?>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

