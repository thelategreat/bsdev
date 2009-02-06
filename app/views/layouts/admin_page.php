<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?=$title?></title>

	<!-- style -->
	<link rel="stylesheet" href="/css/admin_style.css" type="text/css"  media="screen" />
	
	<!-- javascript -->
	<script type="text/javascript" src="/js/jquery.js" ></script>
	
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
