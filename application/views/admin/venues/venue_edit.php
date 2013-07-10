<div class=container>
	<header>Venue Edit - <?=$data->name?></header>

	<?=$tabs?>

	<form method="post">
	<fieldset><legend>Venue Details</legend>
	<table style="border:0">
	<input type="hidden" name="id" value="<?=$data->id?>" />
	<tr>
	  <td><label for="venue">Venue</label></td>
	  <td><input name="name" size="50" value="<?=$data->name?>" /></td>
	</tr>
	<tr>
	  <td><label for"location">Location</label></td>
	  <td><? echo form_dropdown('locations_id', $location_options, $selected_location) ?></td>
	</tr>
	</table>
	</fieldset>
	
	<button type='submit' id='btn_save' name='save' value='save'>
    	<i class="icon-save  icon-2x"></i> Save 
	</button>
	<button type='submit' id='btn_cancel' name='cancel' value='cancel'>
    	<i class="icon-reply  icon-2x"></i> Cancel
	</button>
	</form>
</div>