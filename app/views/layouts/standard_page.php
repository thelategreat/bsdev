<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= $page_title ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?= $style ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/page.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/fancybox.css" type="text/css" media="screen" />
	<!--[if IE 7]><link rel="stylesheet" href="/css/ie7.css" type="text/css" media="screen" /><![endif]-->
	
	<script src="/scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="/scripts/common.js" type="text/javascript"></script>
	<script src="/scripts/jquery.cycle.min.js" type="text/javascript"></script>
	<!-- Only used for jQuery stylesheet switcher -->
	<script src="/scripts/jquery.cookie.js" type="text/javascript"></script>
	<script src="/scripts/jquery.jcarousellite.min.js" type="text/javascript"></script>
	<script src="/scripts/jquery.pop.js" type="text/javascript"></script>
	<script src="/scripts/jquery.fancybox.js" type="text/javascript"></script>
	<?php if( $css_name != 'events-calendar') { ?>
		<!-- This breaks the calendar pop-ups for some reason -->
		<script src="/scripts/jquery.easing.1.1.1.js" type="text/javascript"></script> 
	<?php } ?>
	<script src="/scripts/cufon-yui.js" type="text/javascript"></script>
	<script src="/scripts/TradeGothic_700.font.js" type="text/javascript"></script>
</head>
	
<body id="<?= $css_name ?>"> <!-- Make this change for each page -->
<!--
<ul id="stylesheet">
	<li><a href="#" rel="/css/screen.css">Red</a></li>
	<li><a href="#" rel="/css/blue.css">Blue</a></li>
	<li><a href="#" rel="/css/green.css">Green</a></li>
	<li><a href="#" rel="/css/purple.css">Purple</a></li>
</ul>
-->
<div id="site_wrapper">
	<h1 id="vertical_logo"><a href="/">Book Shelf</a></h1>

	<ul id="info_block">
	    <li class="categories"><img src="/i/header_text_block.gif" width="105" height="95" alt="alt text goes here" /></li>
	    <li class="address"><img src="/i/header_address_block.gif" width="105" height="95" alt="alt text goes here" /></li>
	</ul>

<!--
	<a id="left_arrow" href="#">&laquo; Previous</a>
	<a id="right_arrow" href="#">Next &raquo;</a>
-->

<?= isset($main_nav_arrows) ? $main_nav_arrows : '' ?>

<div id="wrapper">

    <div id="header">
    	<ul id="main_nav">
    		<li class="books <?= $section == 'books' ? 'selected' :''?>"><a href="/home/books" class="cufon">Books</a></li>
    		<li class="cinema <?= $section == 'cinema' ? 'selected' :''?>"><a href="/home/cinema" class="cufon">Cinema</a></li>
    		<li class="ebar <?= $section == 'ebar' ? 'selected' :''?>"><a href="/home/ebar" class="cufon">Ebar</a></li>	
    	</ul>

    	<ul id="header_meta_nav">
    		<li class="subscribe"><a href="/profile" class="cufon">My Bookshelf</a></li>
    		<li class="help"><a href="/page/help" class="cufon">Help</a></li>
    	</ul>

    	<div id="search">	
				<form action="/search" method="post">
    		<input id="search_box" name="box" type="text" value="search..." onfocus="this.value=''; this.style.color='#222';" onblur="this.value='search...'; this.style.color='#ccc';" />
    		<input id="search_button" name="button" type="image" src="/i/search_button.png" value="" onclick="submit();" />
				</form>
    	</div>

    	<div id="top_feature">
			  <?= $featured_top ?>
    		<?= $main_content_nav ?>
    	</div>

    	<div id="header_photo">
			<?= $sidebar_nav ?>
	    </div>	
    </div><!-- /Header -->

	  <div id="content_wrapper">
	    <div id="main_content">
      <?= $content ?>
	    </div><!-- /Main Content -->


	    <div id="sidebar">
	    <?= $sidebar ?>
	    </div><!-- /Sidebar -->
    </div><!-- /Content Wrapper -->

		<?= $featured_bottom ?>
		
</div><!-- /Wrapper -->

	<div id="footer">
  <?= $footer ?>
  </div><!-- /Footer -->

</div><!-- /Site Wrapper -->

</body>
</html>