<script type="text/javascript">
function do_lookup()
{
	var title = $('#title').val();
	if( title == "") {
		alert('Please input a title');
		return;
	}
	alert("search: " + title );
	$.post('/admin/films/imdb_lookup', {t: title, fo: 'json'}, function(data) {
		alert(data);
		var film = eval("(" + data + ")");
		if( film['trynt'] && film['trynt']['movie-imdb']['error']) {
			alert(film['trynt']['movie-imdb']['msg']);
		} else {
			//$('#link').val(film['trynt']['movie-imdb']['matched-url']);
			$('#title').val(film['title']);
			$('#director').val(film['director']);
			$('#description').val(film['description']);
			$('#country').val(film['country']);
			$('#year').val(film['year']);
			$('#link').val(film['link']);
		}
	});
}

function do_link_lookup()
{
	var link = $('#link').val();
	if( link == "") {
		alert('Please input a link');
		return;
	}
	$.post('/admin/films/imdb_search', {u: link, fo: 'json'}, function(data) {
		alert(data);
		var film = eval("(" + data + ")");
		if( film['trynt'] && film['trynt']['movie-imdb']['error']) {
			alert(film['trynt']['movie-imdb']['msg']);
		} else {
			//$('#link').val(film['trynt']['movie-imdb']['matched-url']);
			$('#title').val(film['title']);
			$('#director').val(film['director']);
			$('#description').val(film['description']);
			$('#country').val(film['country']);
			$('#year').val(film['year']);
			$('#running_time').val(film['running_time']);
			$('#rating').val(film['rating']);
			$('#link').val(film['link']);
		}
	});
}

function lookup()
{
	var url = "http://www.imdb.com/find?s=all&q=" + escape($("#title").val());
	window.open(url,"imdb");
	return false;
}


$(document).ready(function() {
	initMCE();
});


</script>

<div class=container>
	<header>Add Film</header>
	<br>

<?= form_open('admin/films/add', array('class'=>'general')); ?>

<section> 
		<fieldset><legend>Film Details</legend>
		<table class='form-table'>
			<tr><th>TT #</th>
				<td><input name="ttno" type="text" value="<?=set_value('ttno')?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr><th>Title</th><td><input id="title" name="title" type="text" size="40" class="required" value="<?=set_value('title')?>"/>
				<button onclick="return lookup();">IMDB Lookup</button></td>
				<td class="form_error"><?=form_error('title')?></td>
			</tr>
			<tr><th>Director</th><td><input name="director" type="text" class="required" value="<?=set_value('director')?>"/></td>
				<td class="form_error"><?=form_error('director')?></td>
			</tr>
			<tr><th>Country</th><td><input name="country" type="text" value="<?=set_value('country')?>"/></td>
				<td class="form_error"><?=form_error('country')?></td>
			</tr>
			<tr><th>Year</th><td><input name="country" type="text" value="<?=set_value('year')?>"/></td></tr>
			<tr><th>Running Time (mins)</th><td><input name="running_time" type="text" size="4" value="<?=set_value('running_time')?>"/></td></tr>
			<tr><th>Aspect Ratio</th><td><input name="aspect_ratio" type="text" size="10" value="<?=set_value('aspect_ratio')?>"/></td></tr>
			<tr><th>Rating</th><td><input name="rating" type="text" value="<?=set_value('rating')?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr><th>Link</th>
				<td><input name="link" id="link" type="text" size="50" value="<?=set_value('link')?>"/></td>
				<td class="form_error"><?=form_error('link')?></td>
			</tr>
			<tr><th>Description</th>
				<td><textarea name="description"><?=set_value('description')?></textarea></td>
			</tr>
		</table>
	</fieldset>
</section>


<div style="clear:both"></div>

<nav>
	<button type='submit' id="save-button" class='save-button' name='add' value="Save">
		<i class="icon-save icon-2x"></i> Save
	</button>
	<button type='submit' id='save-add-button' class='save-button2' name='addedit' value='Save &amp; Add Media'>
		<i class='icon-save icon-2x'></i> Save &amp; Add Media 
	</button>
	<button type='submit' id='cancel-button' class='cancel-button' name='cancel' value='Cancel'> 
		<i class='icon-reply icon-2x'></i> Cancel 
	</button>
</nav>
</form>
</div>