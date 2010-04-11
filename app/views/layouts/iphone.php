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
		    padding-left: 54px;
		    padding-right: 40px;
		    min-height: 34px;
		}
		li .icon {
	    display: block;
	    position: absolute;
	    margin: 0;
	    left: 6px;
	    top: 7px;
	    text-align: center;
			background-color: #999;
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

<body>
    <div class="toolbar">
        <h1 id="pageTitle">The Bookshelf</h1>
        <a id="backButton" class="button" href="/iph/"></a>
	      <a class="button" href="#searchForm">Search</a>
	  </div>

    <ul id="home" title="The Bookshelf" selected="true">
        <li><img class="icon" src="/img/icons/icon_cinema.gif" /><a href="/iph/cal">Cinema</a></li>
        <li><img class="icon" src="/img/icons/icon_ebar.gif" /><a href="/iph/ebar">eBar</a></li>
        <li><img class="icon" src="/img/icons/icon_greenroom.gif" /><a href="/iph/books">Bookstore</a></li>
        <li><a href="#about">About</a></li>
    </ul>

		<div id="about">
			<h3>About the Bookshelf</h3>
			<p>The Bookshelf is located at 
			<a target="_self" href="http://maps.google.ca/maps?f=q&source=s_q&hl=en&geocode=&q=bookshelf,+41+quebec+street,+guelph,+ont&sll=43.546775,-80.250642&sspn=0.009005,0.014527&ie=UTF8&hq=bookshelf,&hnear=41+Quebec+St,+Guelph,+ON&ll=43.545686,-80.250642&spn=0.009005,0.014527&z=16">
				41 Quebec Street, Guelph, Ontario, Canada.</a>
			</p>
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



