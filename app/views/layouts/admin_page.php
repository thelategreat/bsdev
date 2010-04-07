<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?=$title?></title>

	<!-- style -->
	<link rel="stylesheet" href="/css/admin_style.css" type="text/css"  media="screen" />
	<link rel="stylesheet" href="/css/datePicker.css" type="text/css"  media="screen" />
	<link rel="stylesheet" href="/css/custom-theme/jquery-ui-1.7.2.custom.css" type="text/css"  media="screen" />
	
	<!-- javascript -->
	<script type="text/javascript" src="/js/jquery-1.3.2.min.js" ></script>
  <script type="text/javascript" src="/js/jquery.form.js" ></script>
  <script type="text/javascript" src="/js/jquery-ui-1.7.2.custom.min.js" ></script>
  <script type="text/javascript" src="/js/date.js" ></script>
  <script type="text/javascript" src="/js/jquery.datePicker.js" ></script>
  <script type="text/javascript" src="/js/jquery.simplemodal.min.js" ></script>
  <script type="text/javascript" src="/js/jquery.json-1.3.min.js" ></script>

	<script type="text/javascript" src="/js/admin.js" ></script>

	<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js" ></script>
		
	<script type="text/javascript">
	
	jQuery(document).ready(function() { 
		// drop menu
		$("#dropmenu ul").css({display: "none"}); // Opera Fix 
		$("#dropmenu li").hover(function(){ 
		        $(this).find('ul:first').css({visibility: "visible",display: "none"}).show(268); 
		        },function(){ 
		        $(this).find('ul:first').css({visibility: "hidden"}); 
		        }); 
	});

	tinyMCE.init({
		mode : "textareas",
		editor_deselector : "mceNoEditor",
		theme : "advanced",
		plugins : "safari,spellchecker,fullscreen,paste",
		theme_advanced_buttons1 : "mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,|,link,unlink,|,spellchecker,fullscreen",
		theme_advanced_buttons2 : "formatselect,forecolor,|,cut,copy,paste,pastetext,pasteword,|,undo,redo",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",		
	});

	</script>
	
	<!-- meta -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="generator" content="" />
	<meta name="description" content="" />	
</head>

<body>
	<div id="container">
	   <div id="header">
			<h1>Bookshelf - Admin</h1>
			<div style="float: right"><small>
			  <a href="/">site</a> 
			  <?php if( $this->auth->logged_in()) { ?>
				 | <a href="/admin/login/logout">logout</a> 
				<?php } ?>
				</small>
			</div>
	   </div>
	   <div id="nav">
			<?=$nav?>
	   </div>	
	   <div id="content">
			<?=$content?>
	   </div>
	   <div id="footer">
			<?=$footer?>
	   </div>
	</div>
</body>
</html>
