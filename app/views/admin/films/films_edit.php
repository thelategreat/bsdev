
<script language="javascript" type="text/javascript">
function lookup()
{
	var url = "http://www.imdb.com/find?s=all&q=" + escape($("#title").val());
	window.open(url,"imdb");
	return false;
}
</script>

<h3>Edit Film</h3>

<?= form_open('admin/cinema/edit/' . $film->id, array('class'=>'general')); ?>
<table style="border: 0">
	<tr><td valign="top">
		<fieldset><legend>Details</legend>
		<table style="border: 0">
			<tr>
				<td><label for="ttno">tt#</label><br/>
				<input name="ttno" type="text" value="<?=set_value('ttno', $film->ttno )?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr>
				<td><label for="title">title</label><br/>
				<input id="title" name="title" type="text" size="40" class="required" value="<?=set_value('title', $film->title )?>"/>
				<button onclick="return lookup();">IMDB</button></td>
				<td class="form_error"><?=form_error('title')?></td>
			</tr>
			<tr>
				<td><label for="director">director</label><br/>
				<input name="director" type="text" class="required" value="<?=set_value('director', $film->director )?>"/></td>
				<td class="form_error"><?=form_error('director')?></td>
			</tr>
			<tr>
				<td><label for="country">country</label><br/>
				<input name="country" type="text" value="<?=set_value('country', $film->country )?>"/></td>
				<td class="form_error"><?=form_error('country')?></td>
			</tr>
			<tr>
				<td>
					<table style="border: 0">
						<tr>
							<td><label for="year">year</label><br/>
							<input name="year" type="text" size="4" value="<?=set_value('year', $film->year )?>"/></td>
							<td class="form_error"><?=form_error('year')?></td>
							<td><label for="running_time">running time</label><br/>
							<input name="running_time" type="text" size="4" value="<?=set_value('running_time', $film->running_time)?>"/></td>
							<td class="form_error"><?=form_error('running_time')?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><label for="rating">rating</label><br/>
				<input name="rating" type="text" value="<?=set_value('rating', $film->rating)?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr>
				<td><label for="link" onclick="do_link_lookup()">link</label><br/>
				<input name="link" id="link" type="text" size="50" value="<?=set_value('link', $film->imdb_link)?>"/></td>
				<td class="form_error"><?=form_error('link')?></td>
			</tr>
		</table>
		</fieldset>
	</td>
	<td valign="top">
	  <table>
	    <tr>
	      <td>
      		<fieldset><legend>Description</legend>
      		<textarea name="description" cols="60" rows="15"><?=set_value('description',$film->description)?></textarea>
      		</fieldset>
    		</td>
    	</tr>
    	<tr>
    	  <td valign="top">
					<fieldset><legend>Media</legend>
						<div id="media_preview">
							<a href="#" onclick="MediaBrowser.init({path: '/films/<?=$film->id?>'});"><img src="/pubmedia/library/no_image.jpg" height="80" /></a>
							<br><small>no image assigned</small>
						</div>
					</fieldset>
    	  </td>
    	</tr>
		</table>
	</td>
	</tr>
</table>
<br/>
<input type="submit" name="update" value="Update" />
<input type="submit" name="cancel" value="Cancel" />
</form>

