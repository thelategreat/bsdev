<!DOCTYPE html>
<html>
<head>
<title><? echo (isset($title) ? $title : (isset($page_title) ? $page_title : '')); ?></title>

<? // Import Google Fonts ?>
<link href='http://fonts.googleapis.com/css?family=Merriweather:400,400italic|Arvo:700|Open+Sans:400,700,600,700italic,800,600|Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>

<? // Liquid Slider jQuery and easing effects ?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

<script src="<? echo base_url('/js/jquery.liquid-slider-0.1.min.js');?>"></script>
<script src="<? echo base_url('/js/jquery-ui-1.8.20.custom.min.js');?>"></script>
<script src="<? echo base_url('/js/custom_slider_home.js');?>"></script>
<script src="<? echo base_url('/js/jquery.columnizer.js');?>"></script>
<script type='text/javascript'>
    $(function(){
      $('.colz').addClass("dontsplit");
      $('.enclosure').columnize({
        width : 280
      });
    });
  </script>

<? // General sytles and slider styles ?>
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/common.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_home.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_home.css');?>" />

<meta charset='utf-8'>
</head>
<body>
<div id="page">
  <div id="container">

    <? // Start Search wrapper ?> 
    <div id="search_wrapper">
      <? // Start Search box ?>
      <div id="search"> <a class="profile" href="#"><img src="<? echo base_url('/images/icon_profile.png');?>" alt="Profile" border="0"></a> <a class="shopping_cart" href="#"><img src="<? echo base_url('/images/icon_cart.png');?>" alt="Shopping Cart" width="16" height="17" border="0"></a>
        <form id="cse-search-box" action="http://www.google.com/search" class="lighter" >
          <input type="hidden" name="cx" value="017505389840199103811:kud_tegrox0" />
          <input type="hidden" name="cof" value="FORID:0" />
          <input type="hidden" name="ie" value="UTF-8" />
          <input type="text" class="rounded_corners" name="q" value="Search" onblur="this.value = this.value || this.defaultValue;" onfocus="this.value == this.defaultValue &amp;&amp; (this.value = '');"/>
          <input type="submit" id="submit" name="sa" value="Go" />
        </form>
      </div>
      <? // End Search Box ?>
      <h2><span>Books.</span><span>Films.</span><span>Music.</span><span>Food.</span><span>Ideas.</span></h2>
      
    </div>
    <? // End search wrapper ?>


    <? // Start heaader ?>

    <div id="header">
      <h1><span>BookShelf</span></h1>
      <? // Start menu ?>
      <div id="nav_wrapper">
        <ul id="nav">
          <li class=""><a href="/">Home</a></li>
          <? foreach ($nav as $it) { ?>
            <li class="<? if (isset($page) && ( $it->id == $page->id || in_array($page->id, $it->child_ids) ) ) echo 'active' ?>"><a href="<? echo base_url('/section/view/' . $it->id);?>"><?=$it->name;?></a>
            <? if (isset($it->children) && count($it->children) > 0) { ?>
              <ul>
              <? foreach ($it->children as $sub) { ?>
                <li><a href="<? echo base_url('/section/view/' . $sub->id);?>"><?=$sub->name;?></a></li>
              <? } ?>
              </ul>
            </li>
            <? 
            } 
          } ?>

          <? /*
          <li class="active"><a href="#">Home</a></li>
          <li class="calendar"><a href="#">Calendar</a>
            <? // Subnav Starts Here ?>
            <span> <a href="#">Film</a> | <a href="#">Music</a> | <a
             href="#">Books</a> </span>
            <? // Subnav Ends Here ?>
          </li>
          <li><a href="#">Trending</a></li>
          <li><a href="#">Storylines</a></li>
          <li><a href="#">Stranger Than Fiction</a></li>
          <li><a href="#">Self-Health</a></li>
          <li><a href="#">YouthPhoria</a></li>
          <li><a href="#">Artcetera</a></li>
          <li class="last"><a href="#">Around Town</a></li>
          */ ?>
        </ul>
      </div>
      <? // End menu ?>
    </div>
    <? // End header ?>

<? /*
<!DOCTYPE html>
<html lang="en">
<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif|Fjalla+One|Karla:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
	<meta charset="utf-8"/>
	<title><? echo (isset($title) ? $title : (isset($page_title) ? $page_title : '')); ?></title>

	<!-- Mobile viewport optimisation -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- (en) Add your meta data here -->

	<link href="<? echo base_url('/yaml/flexible-columns.css'); ?>" rel="stylesheet" type="text/css"/>

	<link href="<? echo base_url('/js/tooltipster-master/css/tooltipster.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<? echo base_url('/js/tooltipster-master/css/themes/tooltipster-shadow.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<? echo base_url('/js/tooltipster-master/css/themes/tooltipster-punk.css');?>" rel="stylesheet" type="text/css"/>	
	<!--[if lte IE 7]>
	<link href="/yaml/core/iehacks.css" rel="stylesheet" type="text/css" />
	<![endif]-->

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	
	<script type="text/javascript" src="/js/jquery.tools.min.js" ></script>
	<script type="text/javascript" src="/js/jqModal.js"></script>
	<script type="text/javascript" src="/js/jquery.qtip.min.js"></script>
	<script type="text/javascript" src="/js/jquery.cycle.lite.js"></script>
	
</head>
*/ ?>