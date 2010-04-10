<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>iBookshelf</title>
		<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
		<style type="text/css" media="screen">@import "/iui/iui.css";</style>
		<script type="application/x-javascript" src="/iui/iui.js"></script>
</head>

<body>
    <div class="toolbar">
        <h1 id="pageTitle">The Bookshelf</h1>
        <a id="backButton" class="button" href="/iph/"></a>
	      <a class="button" href="#searchForm">Search</a>
	  </div>

    <ul id="home" title="The Bookshelf" selected="true">
        <li><a href="/iph/cal">Cinema</a></li>
        <li><a href="/iph/ebar">eBar</a></li>
        <li><a href="/iph/books">Bookstore</a></li>
        <li><a href="/iph/about">About</a></li>
    </ul>


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



