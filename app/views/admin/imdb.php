<script type="text/javascript">

String.prototype.trim = function() { return this.replace(/^\s+|\s+$/, ''); }

function live_search()
{
	var query = $("#query").val();
	// take three chars and don't hit it if the last char is a space
	if( query.length >= 3 && query.substr( query.length-1, 1) != " ")  {
		search();
	}
}

function search()
{
	var query = $("#query").val();
	var type = $('#type :selected').val();
	
	if( query.trim() == "" ) {
		alert("Please type something");
		return;
	}
	$('#results').html('<center><p><img src="/img/ajax-loader.gif" /></p>Hang on...</center>');
	$.post('/admin/imdb/ajax_search', {q: query, t: type }, function(data) {
		data = eval("(" + data + ")");
		if( data.err == 1 ) {
			alert( data.msg );
		} else {
			$('#results').html(data.html);
		}
	});
	
}

function show_item( row )
{
	var row_id = $(row).find('td').html();
	var type = $('#type :selected').val();

	if( !row_id )
		return;

	if( row_id.trim() == "" ) {
		return;
	}
	
	$('#results').html('<center><p><img src="/img/ajax-loader.gif" /></p>Hang on...</center>');
	
	$.post('/admin/imdb/ajax_details', {id: row_id, t: type }, function(data) {
		data = eval("(" + data + ")");
		if( data.err == 1 ) {
			alert( data.msg );
		} else {
			$('#results').html(data.html);
		}
	});

}

</script>

<h3>IMDB</h3>

<fieldset>
	<select id="type">
		<optgroup label="movies">
			<option value="title">title</option>
			<option value="year">year</option>
		</optgroup>
		<optgroup label="people">
			<option value="actor">actor</option>
			<option value="director">director</option>
		</optgroup>
	</select>
	<input id="query"  onkeyup="live_search()" size="30" />
	<button onclick="search()">Go</button>
</fieldset>

<div id="info" class="info" style="float: right; width: 200px;">
	<h4>Searching</h4>
	<p>Names and titles in this database have the first name and articles
		at the end, after a comma, so "The Pink Panther" is "Pink Panther, The"
		and "Will Smith" is "Smith, Will". 
	</p>
	<p>
		Searching will be refined in the future but for now search terms are
		treated at the beginning of the string. So search for "smith" rather
		then "will".
	</p>
	<ul>
		<li>"title" - tv series</li>
		<li>"title" (mini) - tv mini-series</li>
		<li>(TV) - tv</li>
		<li>(V) - video</li>
		<li>(VG) - video game</li>
	</ul>
			
</div>
<div id="results" style="overflow: auto; max-height: 400px; ">
</div>
