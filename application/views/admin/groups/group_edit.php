<fieldset><legend>Edit Group</legend>
<?= form_open('/admin/groups/edit/' . $group->id);?>

<link rel="stylesheet" href="<? echo base_url('/js/fancybox/jquery.fancybox.css?v=2.1.5');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<? echo base_url('/js/fancybox/jquery.fancybox.pack.js?v=2.1.5');?>"></script>

<script type='text/javascript'>
    $(document).ready(function() {
        $(".boxframe").fancybox({
            'width' : '75%',
            'height' : '75%',
            'autoScale' : false,
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'type' : 'iframe'
        });
        $('body').on('change','.preview_link',function(){
            build_preview_link(this);
        });
    });
    
    function build_preview_link(list){
        position_id = $(list).attr('position_id')
        value = $(list).val();
        link = "/admin/preview_list/index/"+position_id+"/"+value;
        $('#preview_link_'+position_id).attr('href',link);
        console.log(link);
    }
        
</script>
<table style="border: 0">
	<tr>
		<td><label>Parent</label></td>
		<td><select name="parent_id"><option value="1">--Top Level--</option><?=$parent_select?></select></td>
	</tr>
	<tr>
		<td><label>Name</label></td>
		<td><input type="text" name="name" class="textbox" size="50" value="<?= set_value('name', $group->name)?>" />
			<?=form_error('name')?>
		</td>
	</tr>
	<tr>
		<td><label>Active</label></td>
		<td><input type="checkbox" name="active"  <?= $group->active ? "checked='checked'" : '' ?>/>
		</td>
	</tr>
	<tr>
		<td><label>Page Template</label></td>
		<td>
			<select name='template_id'>
				<option value=''>Default Template</option>
				<? foreach ((array)$templates as $it) { ?> 
				<option value='<?=$it->id;?>'><?=$it->name;?></option>
				<? } ?>
			</select>
		</td>
	</tr>

	<? foreach ($list_positions as $l) { ?>
	<tr>
            <td><?=$l->name?></td>
            <td>
                <select   class='preview_link'  name='lists[<?=$l->id;?>]' id="list_positions_<?=$l->id?>" position_id="<?=$l->id?>">
                    <? foreach ($lists_dropdown as $key=>$val) { ?>
                        <option value='<?=$key?>' <? if (isset($group_lists[$l->id]) && $group_lists[$l->id] == $key) echo 'selected'; ?>><?=$val?></option> 
                    <? } ?>			
                </select>
            </td>
            <td>
                <a position_id="<?=$l->id?>" id = "preview_link_<?=$l->id?>" class='boxframe' href="/admin/preview_list/index/<?=$l->id?>/0" >Preview </a>
            </td>
	</tr>
	<? } ?>
</table>
</fieldset>
<input class="button" name="save" type="submit" value="Save" />
<input class="button" name="cancel" type="submit" value="Cancel" />
</form>
