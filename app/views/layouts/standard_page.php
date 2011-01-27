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
	
	<!-- javascript -->
	<script type="text/javascript" src="/js/jquery-1.3.2.min.js" ></script>

	<script type="text/javascript" src="/js/jquery.tools.min.js" ></script>
	<script type="text/javascript" src="/js/jqModal.js"></script>
  <script type="text/javascript" src="/js/jquery.jdropdown.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('#nav').dropDown({speed: 'fast'});
    });
  </script>

</head>

<body class="<?=$css_name?>">
	<div id="container">
		<div id="header-nav">
			<ul>
				<li><a href="/cart" title="Shopping Cart: <?= $cart->total_items() ?> items"><img src="/img/icons/white/shop_cart.png" style="height: 20px;"/> <?= $cart->total_items() ?> </a></li>
				<li><a href="/profile" title="My Bookshelf"><img src="/img/icons/white/user.png" style="height: 20px"/></a></li>
				<!--<li><a href="/page/help" title="Help/FAQ"><img src="/img/icons/white/info.png" style="height: 20px"/> </a></li> -->
			</ul>
			<div id="search">
				<form id="main_search" action="/search" method="post">
					<select name="type" id="search-type">
						<option value="books">books</option>
						<option value="events">events</option>
						<option value="articles">articles</option>
					</select>
					<input type="search" size="20" name="q" placeholder="search..." />
					<button><img src="/img/icons/white/zoom.png" width="14px"/></button>
				</form>
			</div>
		</div>
		<div id="header">
			<h1>ookshelf</h1>
			<!--
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
					Copyright © Bookshelf, 2010
				</div>
			</div>
		</div>
	</div>

</body>
</html>

