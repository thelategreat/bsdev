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
<script src="<? echo base_url('/js/notifyjs.min.js');?>"></script>
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
        <form id="search-box" action="<? echo base_url('/search/results'); ?>" method='post' class="lighter" >
          <input type="text" class="rounded_corners" name="q" />
          <input type="submit" id="submit" />
        </form>
      </div>
      <? // End Search Box ?>
      <h2>
        <span <? if (isset($active_links) && in_array('books', $active_links)) echo 'class="active"'; ?>>Books.</span>
        <span <? if (isset($active_links) && in_array('films', $active_links)) echo 'class="active"'; ?>>Films.</span>
        <span <? if (isset($active_links) && in_array('music', $active_links)) echo 'class="active"'; ?>>Music.</span>
        <span <? if (isset($active_links) && in_array('food', $active_links)) echo 'class="active"';  ?>>Food.</span>
        <span <? if (isset($active_links) && in_array('ideas', $active_links)) echo 'class="active"'; ?>>Ideas.</span>
      </h2>
      
    </div>
    <? // End search wrapper ?>

    <? // Start heaader ?>
    <div id="header">
      <h1><span>BookShelf</span></h1>
      <? // Start menu ?>
      <div id="nav_wrapper">
        <ul id="nav">
          <li><a href="<? echo base_url(); ?>">Home</a></li>
          <? foreach ($nav as $it) if ($it->active != false) { ?>
            <li class="<? if (isset($page) && ( $it->id == $page->id || in_array($page->id, $it->child_ids) ) ) echo 'active' ?>">
                <? if (isset($it->route) && ($it->route != null)) { ?>
                <a href="<? echo base_url($it->route) ;?>"><?=$it->name;?></a>
                <? } else { ?>
                <a href="<? echo base_url('/section/view/' . $it->id);?>"><?=$it->name;?></a>
                <? }
             
               if (isset($it->children) && count($it->children) > 0) { ?>
                <ul>
                <? foreach ($it->children as $sub) if ($sub->active != false) { 
                    if (isset($sub->route) && ($sub->route != null)) {
                      ?> <li><a href="<? echo base_url($sub->route); ?>"><?=$sub->name;?></a></li> <?
                    } else {
                      ?> <li><a href="<? echo base_url('/section/view/' . $sub->id);?>"><?=$sub->name;?></a></li>  <?
                  }
               } 
               ?>
              </ul>
            </li>
            <? 
            } 
          } ?>
      </div>
      <? // End menu ?>
    </div>
    <? // End header ?>