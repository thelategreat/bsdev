<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>
 
<script>

/* image picker callback */
function mediaBrowserCallback( field_name, url, type, win ) {

	var cmsURL = '<?=base_url('admin/tinymce/browse/films/' . $film->id);?>';
	
	tinyMCE.init({
	    file_browser_callback: function(field_name, url, type, win) { 
	        tinymce.activeEditor.windowManager.open({
	            title: "My file browser",
	            url: '/admin/tinymce/browse/films/<?=$film->id?>',
	            width: 800,
	            height: 600
	        }, {
	            oninsert: function(url) {
	                win.document.getElementById(field_name).value = url; 
	            }
	        });
	    }
	});
    return false;
}

$(document).ready(function() {
	initMCE('films/<?=$film->id;?>');
});

function lookup()
{
	var url = "http://www.imdb.com/find?s=all&q=" + escape($("#title").val());
	window.open(url,"imdb");
	return false;
}
</script>

<div class=container>
	<header>Film - <?= $film->title; ?></header>
	<nav>
		<?= $tabs ?>
	</nav>
	<br>

<?= form_open('admin/films/edit/' . $film->id, array('class'=>'general')); ?>

<section> 
		<header>Film Details</header>
		<table class='form-table'>
			<tr><th>TT #</th>
				<td><input name="ttno" type="text" value="<?=set_value('ttno', $film->ttno )?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr><th>Title</th><td><input id="title" name="title" type="text" size="40" class="required" value="<?=set_value('title', $film->title )?>"/>
				<button onclick="return lookup();">IMDB Lookup</button></td>
				<td class="form_error"><?=form_error('title')?></td>
			</tr>
			<tr><th>Director</th><td><input name="director" type="text" class="required" value="<?=set_value('director', $film->director )?>"/></td>
				<td class="form_error"><?=form_error('director')?></td>
			</tr>
			<tr><th>Country</th><td><input name="country" type="text" value="<?=set_value('country', $film->country )?>"/></td>
				<td class="form_error"><?=form_error('country')?></td>
			</tr>
			<tr><th>Year</th><td><input name="country" type="text" value="<?=set_value('year', $film->year )?>"/></td></tr>
			<tr><th>Running Time (mins)</th><td><input name="running_time" type="text" size="4" value="<?=set_value('running_time', $film->running_time)?>"/></td></tr>
			<tr><th>Aspect Ratio</th><td><input name="aspect_ratio" type="text" size="10" value="<?=set_value('aspect_ratio', $film->aspect_ratio )?>"/></td></tr>
			<tr><th>Rating</th><td><input name="rating" type="text" value="<?=set_value('rating', $film->rating)?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr><th>Link</th>
				<td><input name="link" id="link" type="text" size="50" value="<?=set_value('link', $film->imdb_link)?>"/></td>
				<td class="form_error"><?=form_error('link')?></td>
			</tr>
			<tr><th>Description</th>
				<td><textarea name="description"><?=set_value('description',$film->description)?></textarea></td>
			</tr>
		</table>
</section>


<div style="clear:both"></div>

<nav>
	<button type='submit' id="save-button" class='iconbutton' name='update' value="Save">
		<i class="icon-save icon-2x"></i> Update
	</button>
	<? if( $can_delete ) { ?>
	<button type='submit' id='delete-button' class='iconbutton' name='rm' value='Delete' onclick="return confirm('Realy delete this film?');">
		<i class='icon-trash icon-2x'></i> Delete
	</button>
	<? } ?>
	<button type='submit' id='cancel-button' class='iconbutton' name='cancel' value='Cancel'> 
		<i class='icon-reply icon-2x'></i> Cancel 
	</button>
</nav>
</div>

<? 

/*
<table style="border: 0">
	<tr><td valign="top">
		<fieldset><legend>Film Details</legend>
		<table style="border: 0">
			<tr>
				<td><label for="ttno">tt#</label></td>
				<td><input name="ttno" type="text" value="<?=set_value('ttno', $film->ttno )?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr>
				<td><label for="title">title</label></td>
				<td><input id="title" name="title" type="text" size="40" class="required" value="<?=set_value('title', $film->title )?>"/>
				<button onclick="return lookup();">IMDB</button></td>
				<td class="form_error"><?=form_error('title')?></td>
			</tr>
			<tr>
				<td><label for="director">director</label></td>
				<td><input name="director" type="text" class="required" value="<?=set_value('director', $film->director )?>"/></td>
				<td class="form_error"><?=form_error('director')?></td>
			</tr>
			<tr>
				<td><label for="country">country</label></td>
				<td><input name="country" type="text" value="<?=set_value('country', $film->country )?>"/></td>
				<td class="form_error"><?=form_error('country')?></td>
			</tr>
			<tr>
				<td colspan="3">
					<table style="border: 0">
						<tr>
							<td><label for="year">year</label></td>
							<td><input name="year" type="text" size="4" value="<?=set_value('year', $film->year )?>"/></td>
							<td><label for="running_time">running time</label></td>
							<td><input name="running_time" type="text" size="4" value="<?=set_value('running_time', $film->running_time)?>"/></td>
							<td><label for="aspect_ratio">aspect ratio</label></td>
							<td><input name="aspect_ratio" type="text" size="10" value="<?=set_value('aspect_ratio', $film->aspect_ratio )?>"/></td>
						</tr>
						<tr>
							<td class="form_error"><?=form_error('year')?></td>
							<td class="form_error"><?=form_error('running_time')?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><label for="rating">rating</label></td>
				<td><input name="rating" type="text" value="<?=set_value('rating', $film->rating)?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr>
				<td><label for="link" onclick="do_link_lookup()">link</label></td>
				<td><input name="link" id="link" type="text" size="50" value="<?=set_value('link', $film->imdb_link)?>"/></td>
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
      		<textarea name="description" cols="60" rows="25"><?=set_value('description',$film->description)?></textarea>
      		</fieldset>
    		</td>
    	</tr>
		</table>
	</td>
	</tr>
</table>
<br/>
<input class="save-button" type="submit" name="update" value="Save" />
<?php if( $can_delete ) { ?>
<input class="delete-button" type="submit" name="rm" value="Delete" onclick="return confirm('Really delete this film?');" />
<?php } ?>
&nbsp;&nbsp;<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form>
*/ 
?>

