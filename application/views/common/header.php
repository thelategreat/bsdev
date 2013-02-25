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
	
	<? /*
	<script type="text/javascript" src="/js/jquery.tools.min.js" ></script>
	<script type="text/javascript" src="/js/jqModal.js"></script>
	<script type="text/javascript" src="/js/jquery.qtip.min.js"></script>
	<script type="text/javascript" src="/js/jquery.cycle.lite.js"></script>
	*/ ?>
</head>