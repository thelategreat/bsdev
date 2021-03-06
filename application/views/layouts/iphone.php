<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>iBookshelf</title>
		<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
		<style type="text/css" media="screen">@import "/iui/iui.css";</style>
		<style type="text/css">
		body > ul > li {
		    font-size: 14px;
		}
		body > ul > li > a {
		    padding-left: 50px;
		    padding-right: 40px;
		    min-height: 37px;
				padding-bottom: 0;
		}
		li .icon {
	    display: block;
	    position: absolute;
	    margin: 0;
	    left: 10px;
	    top: 10px;
	    text-align: center;
		}
		li .event-time {
	    display: block;
	    position: absolute;
	    margin: 0;
	    left: 6px;
	    top: 7px;
	    text-align: center;
	    font-size: 90%;
	    letter-spacing: -0.07em;
	    color: #93883F;
	    font-weight: bold;
	    text-decoration: none;
	    width: 36px;
	    height: 30px;
	    padding: 3px 0 0 0;		
			background: url(/iui/shade-compact.gif) no-repeat;	
		}
		.event-date {
			font-size: 0.9em;
			font-weight: bold;
			font-style: italic;
			color: #666;
		}
		.desc {
			padding-top: -10px;
	    padding-left: 43px;
			font-size: 0.8em;
			color: #666;
		}
		h2 {
		    margin: 10px;
		    color: slateblue;
		}

		p {
		    margin: 10px;
		}
		</style>
		<script type="application/x-javascript" src="/iui/iui.js"></script>
</head>

<body onload="setTimeout(function() { window.scrollTo(0, 1) }, 100);">
    <div class="toolbar">
        <h1 id="pageTitle">The Bookshelf</h1>
        <a id="backButton" class="button" href="/iph/"></a>
	      <a class="button" href="#searchForm">Search</a>
	  </div>

    <ul id="home" title="The Bookshelf" selected="true">
        <li><img class="icon" src="/img/icons/calendar_1.png" /><a href="/iph/cal">Events</a>
				</li>
        <li><img class="icon" src="/img/icons/black/the village.png" /><a href="#location">Location</a>
				</li>
        <li><img class="icon" src="/img/icons/black/info.png" /><a href="#about">About</a>
				</li>
    </ul>

		<div id="location">
			<h3>How to find us</h3>
			<a target="_self" href="http://maps.google.ca/maps?f=q&source=s_q&hl=en&geocode=&q=bookshelf,+41+quebec+street,+guelph,+ont&sll=43.546775,-80.250642&sspn=0.009005,0.014527&ie=UTF8&hq=bookshelf,&hnear=41+Quebec+St,+Guelph,+ON&ll=43.545686,-80.250642&spn=0.009005,0.014527&z=16">
				41 Quebec Street, Guelph, Ontario, Canada.</a>
			</p>
		</div>

		<div id="about">
			<h3>About the Bookshelf</h3>
			<p>The Bookshelf is located at 
			<p>Other stuff would be here</p>
		</div>
				
    <form id="searchForm" class="dialog" action="/iph/search">
        <fieldset>
            <h1>Bookshelf Search</h1>
            <a class="button leftButton" type="cancel">Cancel</a>
            <a class="button blueButton" type="submit">Search</a>
            
            <label>Title:</label>
            <input id="title" type="text" name="title"/>
            <label>Tags:</label>
            <input type="text" name="tags"/>
        </fieldset>
    </form>
				
</body>
</html>



