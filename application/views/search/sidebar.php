<p id="returned_results">Your search on '<?=$results['query']?>' returned <?=$results['count']?> results.</p>
	    	
<!--
<div id="related_searches">
	<h4 class="results">Find more results with fewer search terms:</h4>
	
	<ul id="related_searches_list">
		<li><a href="#">Flight Conchords</a> has returned 72 terms</li>
		<li><a href="#">Flight Concert</a> has returned 62 terms</li>
		<li><a href="#">Conchords Concert</a> has returned 99 terms</li>
	</ul>
</div>
-->
<fieldset >
	<legend>Filter your search:</legend>
	<h4 class="results">Filter your current results by:</h4>
	<ul>
		<form method="post" action="/search">
		<li><label>Venue</label>
			<select name="venue">
				<option>EBAR</option>
				<option>Cinema</option>
				<option>St. George's Church</option>
			</select>
		</li>
		
		<!--
		<li><label>Genre</label>
			<select name="genre">
				<option>All Genres</option>
				<option>Alternative</option>
				<option>Punk</option>
				<option>Metal</option>
				<option>Rap</option>
			</select>
		</li>
		-->
			
		<li><label>Keyword</label>
			<input id="keyword" name="q" type="text" value="<?=$results['query']?>" />
		</li>
		
		<li class="radio_left"><input type="radio" /><label>New search</label></li>		
		<li class="radio_right"><input type="radio" checked="checked"/><label>Search within results</label></li>
		
	</ul>
</fieldset>
	    	
	<input id="sidebar_search_button" name="button" type="image" src="i/sidebar_search_button.png" value="" />
	</form>