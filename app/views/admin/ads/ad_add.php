<script>
$(function()
{
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	$('.date-pick').dpSetStartDate('2000-01-01');
});
</script>

<form class="general" action="/admin/ads/add" method="post">

	<fieldset><legend>Add Advert</legend>
	<table style="border: 0">
	  <tr>
			<table style="margin-top: -10px;">
				<tr>
	    		<td><label for="title">Title/Client</label></td>
					<td><input name="title" size="60" value="<?=set_value('title')?>"/></td>
				</tr>
				<tr>
					<td/>
					<td><?=form_error('title')?></td>
				</tr>
				<tr>
	    		<td><label for="start_date">Start Date</label></td>
					<td>
						<input class="date-pick" name="start_date" size="12" onblur="" id="fld_start_date" value="<?=date('Y-m-d')?>"/>
						<br/><span class="small">yyyy-mm-dd<span>
					</td>
				</tr>
				<tr>
					<td/>
					<td><?=form_error('start_date')?></td>
				</tr>
				<tr>
	    		<td><label for="end_date">End Date</label></td>
					<td>
						<input class="date-pick" name="end_date" size="12" onblur="" id="fld_end_date" value="<?=date('Y-m-d')?>"/>
						<br/><span class="small">yyyy-mm-dd<span>
					</td>
				</tr>
				<tr>
					<td/>
					<td><?=form_error('end_date')?></td>
				</tr>
				<tr>
	    		<td><label for="url">Url</label></td>
					<td><input name="url" size="60" value="<?=set_value('url')?>"/></td>
				</tr>
				<tr>
					<td/>
					<td><?=form_error('url')?></td>
				</tr>
			</table>
	  </tr>
	</table>
	</fieldset>
	<input type="submit" style="background-color: #9f9;" name="save" value="Add" />
	<input type="submit" name="cancel" value="Cancel" />

</form>