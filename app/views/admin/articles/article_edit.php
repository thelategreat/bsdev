<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>

<script>
/* image picker callback */
function mediaBrowserCallback( field_name, url, type, win ) {
  browserField = field_name;
  browserWin = win;
  window.open('/admin/media/mce/articles/<?=$article->id?>','browserWindow','modal,width=600,height=600,scrollbars=yes');
}

function add_to_list()
{
  var listid = $('#lists-sel').val();

  $.post("/admin/lists/addtolist",{ listid: listid, url: 'article/view/<?=$article->id?>'},
    function( data ) {
      if( data.ok ) {
        alert( data.msg );
      } else {
        alert( data.msg );
      }
    }, 'json');
}

function add_category()
{
	$('#new-cat-row').toggle('slow');
	return false;
}

function add_group()
{
	$('#new-group-row').toggle('slow');
	return false;
}

$(function()
{	
	//date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	$('.date-pick').dpSetStartDate('2000-01-01');
	
	$('#new-cat').keypress(function(event) {
		if( event.keyCode == 13 ) {
			event.preventDefault();
			$('#new-cat-row').hide('slow');
			$.post("/admin/articles/addcat", {cat: $('#new-cat').val() },
				function( data ) {
					if( data.ok ) {
						$('#new-cat').val('');
						var opts = $('#category-sel').attr('options');
						opts[opts.length] = new Option(data.cat, data.id, true, true);
					} else {
						alert(data.msg);
					}
				}, 'json');
		}
	});

	$('#new-group').keypress(function(event) {
		if( event.keyCode == 13 ) {
			event.preventDefault();
			$('#new-group-row').hide('slow');
			$.post("/admin/articles/addgroup", { group: $('#new-group').val() },
				function( data ) {
					if( data.ok ) {
						$('#new-group').val('');
						var opts = $('#group-sel').attr('options');
						opts[opts.length] = new Option(data.group, data.id, true, true);
					} else {
						alert(data.msg);
					}
				}, 'json');
		}
	});

	
});
</script>

<?= $tabs ?>

<form class="general" action="/admin/articles/edit/<?=$article->id?>" method="post">

<div style="float: right">
	<fieldset><legend>Meta</legend>
	<table style="border: 0">
	  <tr>
			<td>
      <table>
				<tr><th>Status</th></tr>
				<tr><td>
					<?= $status_select ?>
				</td></tr>
			</table>
			</td>
		</tr>
    <!--
	  <tr>
			<td>
			<table>
				<tr><th>Group</th></tr>
				<tr><td><?= $group_select ?></td></tr>
				<tr style="display: none;" id="new-group-row"><td><input id="new-group" /></td></tr>
			</table>
			</td>
		</tr>
		-->
	  <tr>
			<td>
			<table>
				<tr><th>Category</th></tr>
				<tr><td><?= $category_select ?>&nbsp;<a href="#" onclick="return add_category();"><img src="/img/admin/add.png" /></a></td></tr>
				<tr style="display: none;" id="new-cat-row"><td><input id="new-cat" /></td></tr>
			</table>
			</td>
		</tr>
    <!--
	  <tr>
			<td>
			<table>
				<tr><th>Pub Date</th></tr>
				<tr><td>
					<input class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d',strtotime($article->publish_on))?>"/>
					<br/><span class="small">yyyy-mm-dd<span>
				</td></tr>
			</table>
			</td>
		</tr>
		-->
	  <tr>
			<td>
			<table>
				<tr><th>Display Priority</th></tr>
				<tr><td><?= $priority_select ?></td></tr>
			</table>
			</td>
		</tr>
		<tr>
			<td>
			<table>
				<tr><th>Lists</th></tr>
        <tr><td><?= $lists_select ?>&nbsp;&nbsp;<a href="javascript:add_to_list();" title="Add to list"><img src="/img/admin/add.png" /></a></td></tr>
			</table>
			</td>
		</tr>
    <tr>
			<td>
			<table>
				<tr><th>Tags</th></tr>
				<tr>
					<td><textarea name="tags" class="mceNoEditor" cols="20" rows="2"><?=set_value('tags',$article->tags)?></textarea>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
				<hr/>
				<table>
					<tr>
						<td>
							<input type="submit" class="save-button" name="save" value="Update" />
							<input type="submit" class="delete-button" name="rm" value="Delete" onclick="return confirm('Realy delete this article?');" />
						</td>
						<td align="right">
							| <input type="submit" class="cancel-button" name="cancel" value="Cancel" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<fieldset><legend>Edit Essay</legend>
<table style="border: 0">
  <tr>
    <td>
		<table style="margin-top: -10px;">
			<tr>
    		<td><label for="title">Title</label></td>
				<td colspan="4"><input name="title" size="60" value="<?= set_value('title',$article->title)?>"/></td>
        <td><?=form_error('title')?></td>
        <td/>
			</tr>
			<tr>
    		<td><label for="author">User</label></td>
				<td colspan="4"><input name="author" size="60" value="<?= set_value('author',$article->author)?>"/></td>
        <td><?=form_error('author')?></td>
        <td/>
			</tr>
      <tr>
        <td>Pub Date</td>
        <td>
          <input title="YYYY-MM-DD" class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d')?>"/>
        </td>
        <td>Section</td>
        <td><?= $group_select ?></td>
      </tr>
		</table>
    </td>
  </tr>
  <tr>
    <td/>
  </tr>
  <tr>
    <th>Article</th>
  </tr>
  <tr>
    <td><textarea name="body" rows="15" cols="80"><?= set_value('body',$article->body)?></textarea>
    <br/><?=form_error('body')?></td>
  </tr>
  <tr>
    <th>Teaser</th>
  </tr>
  <tr>
    <td>
      <textarea name="excerpt" class="mceNoEditor" rows="5" cols="80"><?= set_value('excerpt',$article->excerpt)?></textarea>
      <br/><?=form_error('excerpt')?>
		</td>
  </tr>
</table>
</fieldset>
</form>
