<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Demo: Flexible Column Layout &ndash; YAML CSS Framework</title>

	<!-- Mobile viewport optimisation -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- (en) Add your meta data here -->
	<!-- (de) Fuegen Sie hier ihre Meta-Daten ein -->

	<link href="/yaml/flexible-columns.css" rel="stylesheet" type="text/css"/>

	<!--[if lte IE 7]>
	<link href="/yaml/core/iehacks.css" rel="stylesheet" type="text/css" />
	<![endif]-->

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<ul class="ym-skiplinks">
	<li><a class="ym-skip" href="#nav">Skip to navigation (Press Enter)</a></li>
	<li><a class="ym-skip" href="#main">Skip to main content (Press Enter)</a></li>
</ul>

<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>Bookshelf</h1>
		</header>
<nav id="nav">
	<div class="ym-wrapper">
		<div class="ym-hlist">
			<ul>
				<li><a href="/beta/">Main page</a></li>
				<li><a href="/beta/section">Section</a></li>
				<li class='active'><a href="/beta/article"><strong>Article</strong></a></li>
			</ul>
			<form class="ym-searchform">
				<input class="ym-searchfield" type="search" placeholder="Search..." />
				<input class="ym-searchbutton" type="submit" value="Search" />
			</form>
		</div>
	</div>
</nav>
<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g75 ym-gl">
				<div id='articles' style='height:500px; border: 1px dashed #aac'>
					<h1>Article</h1>				
				</div>
			</div>
			<div class="ym-g25 ym-gr" style='; border: 1px dashed #aac'>
				<div style='height:350px;'>
					<h3>Book buying info</h3>
				</div>
				<div style='height:350px;'>
					<h3>Other books and links</h3>
				</div>
				<div style='height:350px;'>
					<h3>Ad space</h3>
				</div>

			</div>
		</div>
	</div>
</div>
<footer>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<p>© Company 2012 &ndash; Layout based on <a href="http://www.yaml.de">YAML</a></p>
		</div>
	</div>
</footer>
<!-- full skip link functionality in webkit browsers -->
<script src="../yaml/core/js/yaml-focusfix.js"></script>
</body>
</html>
