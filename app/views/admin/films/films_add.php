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

</script>

<h3>Add Film</h3>

<?= form_open('admin/films/add', array('class'=>'general')); ?>
<table style="border: 0">
	<tr><td valign="top">
		<fieldset><legend>Details</legend>
		<table style="border: 0">
			<tr>
				<td><label for="ttno">tt#</label><br/>
				<input name="ttno" type="text" value="<?set_value('ttno')?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr>
				<td><label for="title" onclick="do_lookup()">title</label><br/>
				<input name="title" id="title" type="text" size="40" class="required" value="<?=set_value('title')?>"/>
				<button onclick="return lookup();">IMDB</button></td>
			</tr>
			<tr>
				<td> <span class="form_error"><?=form_error('title')?></span></td>
			</tr>
			<tr>
				<td><label for="director">director</label><br/>
				<input name="director" id="director" type="text" class="required" value="<?=set_value('director')?>"/></td>
			</tr>
			<tr>
				<td><span class="form_error"><?=form_error('director')?></span></td>
			</tr>
			<tr>
				<td><label for="country">country</label><br/>
				<input name="country" id="country" type="text" value="<?=set_value('country')?>"/></td>
				<td><span class="form_error"><?=form_error('country')?></span></td>
			</tr>
			<tr>
				<td>
					<table style="border: 0">
						<tr>
							<td><label for="year">year</label><br/>
							<input name="year" id="year" type="text" size="4" value="<?=set_value('year')?>"/></td>
							<td><label for="running_time">running time</label><br/>
							<input name="running_time" id="running_time" type="text" size="4" value="<?=set_value('running_time')?>"/></td>
							<td><label for="aspect_ratio">aspect ratio</label><br/>
							<input name="aspect_ratio" id="aspect_ratio" type="text" size="10" value="<?=set_value('aspect_ratio')?>"/></td>
						</tr>
						<tr>
							<td><span class="form_error"><?=form_error('year')?></span></td>
							<td><span class="form_error"><?=form_error('running_time')?></span></td>
							<td><span class="form_error"><?=form_error('aspect_ratio')?></span></td>
						</tr>
					</table>
				</td>
			<tr>
				<td><label for="rating">rating</label><br/>
				<input name="rating" id="rating" type="text" value="<?=set_value('rating')?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr>
				<td><label for="link" onclick="do_link_lookup()">link</label><br/>
				<input name="link" id="link" type="text" size="50" value="<?=set_value('link')?>"/></td>
				<td class="form_error"><?=form_error('link')?></td>
			</tr>
		</table>
	</td>
	<td valign="top">
		<fieldset><legend>Description</legend>
			<textarea name="description" id="description" cols="60" rows="25"><?=set_value('description')?></textarea>
		</fieldset>
	</td>
	</tr>
</table>
<br/>
<input style="background-color: #6d6" type="submit" name="add" value="Save" />
<input style="background-color: #6d6" type="submit" name="addedit" value="Save &amp; Add Media" />
&nbsp;
<input type="submit" name="cancel" value="Cancel" />
</form>
