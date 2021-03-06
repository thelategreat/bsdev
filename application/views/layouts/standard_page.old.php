<?
 	 // this is a cheat for the pg_data which I haven't migrated completely yet
	if( !isset($section)) {
		$section = "";
	}
	
	//if( is_mobile_browser()) {
	//	redirect('iph');
	//}
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">

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
       
      </ul>
    </div> <!-- venue-nav -->
		<div id="header-nav">
			<form id="main_search" action="/search" method="post">
			<select style="display: none" name="type" id="search-type">
				<option value="books">books</option>
				<option value="events">events</option>
				<option value="articles">articles</option>
			</select>
			<input id='search_box' type="search" size="10" name="q" placeholder="search..." />
			<button style=""><img style="" src="/img/icons/black/zoom.png" width="16px"/></button>
		</form>
		<ul>
				<li><a href="/cart" title="Shopping Cart: <?= $cart->total_items() ?> items"><img src="/img/icons/black/shop_cart.png" style="height: 20px;"/> <span style="font-size: 11px; color: white;"><?= $cart->total_items() ?></span></a></li>
				<li><a href="/profile" title="My Bookshelf"><img src="/img/icons/black/user.png" style="height: 20px"/></a></li>
				<!--<li><a href="/page/help" title="Help/FAQ"><img src="/img/icons/white/info.png" style="height: 20px"/> </a></li> -->
			</ul>
		</div>
    <div id="header">
	</div>
	<nav id="topnav">
      <?= $main_content_nav ?>
		</nav>
		<div id="content-container1">
			<div id="content-container2">
				<div id="sidebar-left">
					<?= $sidebar_left ?>
				</div>
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

