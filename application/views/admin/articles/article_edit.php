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
	//$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	//$('.date-pick').dpSetStartDate('2000-01-01');

	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
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
	
	$('#associated_items .insert-img').click(function() {
		var imgsrc = $(this).attr('data');
		
		var ed = tinyMCE.get('article_body');                // get editor instance
		var range = ed.selection.getRng();		// get range
		var newNode = ed.getDoc().createElement ( "img" );  // create img node
		newNode.src=imgsrc;                           		// add src attribute
		try {
			range.insertNode(newNode);  
		} catch(e) {
			alert('Place the cursor in the essay text at the position where you wish to insert the image.');
		}		
	});
	
	$('#associated_items .insert-ref').click(function() {
		var ref = $(this).attr('data');

		var ed = tinyMCE.get('article_body');                // get editor instance
		var range = ed.selection.getRng();		// get range
		var html = '{{' + ref + '}}';
		try {
			ed.execCommand('mceInsertContent', false , html);
		} catch(e) {
		alert(e.message);
			alert('Place the cursor in the essay text at the position where you wish to insert the reference.');
		}		
	});

	
});
</script>



<div class=container>
	<header>Essay</header>

	<aside class=instruction>
		
	</aside>
	
	<nav>
		<?= $tabs ?>
	</nav>
	<br>


</div>




<form class="general" action="/admin/articles/edit/<?=$article->id?>" method="post">

<section id="meta" style="float:left">
		<header>Metadata</header>
		<table class='form-table'>
			<tr><th>Status</th><td><?= $status_select ?></td>
			<tr><th>Venue</th><td><?= $venue_select ?></td>
			<tr><th>Category</th><td><?= $category_select ?></td>
			<tr><th>Display Prioirty</th><td><?= $priority_select ?></td></tr>
			<tr><th>Tags</th><td><textarea name="tags" class="mceNoEditor" cols="20" rows="2"><?=set_value('tags',$article->tags)?></textarea></td></tr>
		</table>
</section>

<div style='clear:both' ></div>


<section id="editor">
	<header>Article Detail</header>
				<div id="associated_items" style='float:right;width:25%'> 
				<div class="container">
					<header>Associated Items</header>
					<? foreach ($associated as $assoc) { ?>
						
						<article class="associated-item">
						<? if ($assoc->type == 'product') { ?>
							<span class="title"><?= $assoc->title ?></span><br/>
							<ul class="details">
								<li><span>EAN:</span> <?= $assoc->ean ?></li>
								<li><span>Author:</span> <?= $assoc->contributor ?></li>
								<li><span>Publisher:</span> <?= $assoc->publisher ?></li>
								<li><span>Pub Date:</span> <?= $assoc->publish_date ?></li>
								<li><span>Pages:</span> <?= $assoc->pages ?></li>
								<li><span>List Price:</span> <?= $assoc->list_price ?></li>
							</ul>
							<img src="<?= $assoc->thumbnail ?>" width=150 /><br/>
							<div class="insert-link insert-img" data="<?= $assoc->thumbnail ?>">Insert Image</div>
							<div class="insert-link insert-ref" data="<?= $assoc->ref ?>">Insert Reference</div>
						<? } ?>
						
						<? if ($assoc->type == 'film') { ?>
							<span class="title"><?= $assoc->title ?></span><br/>
							<ul class="details">
								<li><span>Director:</span> <?= $assoc->director ?></li>
								<li><span>Running Time:</span> <?= $assoc->running_time?></li>
								<li><span>Rating:</span> <?= $assoc->rating ?></li>
							</ul>
							<img src="<?= $assoc->image ?>" width=150 /><br/>
							<div class="insert-link insert-img" data="<?= $assoc->image ?>">Insert Image</div>
							<div class="insert-link insert-ref" data="film_<?= $assoc->id ?>">Insert Reference</div>
						<? } ?>			

						<? if ($assoc->type == 'article') { ?>
							<span class="title"><?= $assoc->title ?></span><br/>
							<ul class="details">
								<li><span>Author:</span> <?= $assoc->author?></li>
							</ul>
							<img src="<?= $assoc->image ?>" width=150 /><br/>
							<div class="insert-link insert-img" data="<?= $assoc->image ?>">Insert Image</div>
							<div class="insert-link insert-ref" data="article_<?= $assoc->id ?>">Insert Reference</div>	
						<? } ?>			
						<? if ($assoc->type == 'event') { ?>
							<span class="title"><?= $assoc->title ?></span><br/>
							<ul class="details">
								<li><span>Venue:</span> <?= $assoc->venue ?></li>
								<li><span>Start:</span> <?= $assoc->dt_start ?></li>
								<li><span>End:</span> <?= $assoc->dt_end ?></li>
								<li><span>Rating:</span> <?= $assoc->rating ?></li>
							</ul>
							<img src="<?= $assoc->image ?>" width=150 /><br/>
							<div class="insert-link insert-img" data="<?= $assoc->image ?>">Insert Image</div>
							<div class="insert-link insert-ref" data="event_<?= $assoc->id ?>">Insert Reference</div>
						<? } ?>			
						</article>
					<? } ?>
				</div>
				</div>
		<table class='form-table' style='float:left; width: 70%'>
			<tr><th>Title</th><td><input name="title" size="60" value="<?= set_value('title',$article->title)?>"/></td></tr>
    	    <td><?=form_error('title')?></td>
    	    <tr><th>User</th><td><?= $user_select ?></td></tr>
      		<tr><th>Publication Date</th><td><input title="YYYY-MM-DD" placholder='YYYY-MM-DD' class="datepicker short" name="publish_on" size="12" id="fld_publish_on" value="<?=date('Y-m-d')?>"/></td></tr>
      		<tr><th>Section</th><td><?= $group_select ?></td>
      		<tr>
      			<th>Body</th>
      			<td>
      				<textarea id="article_body" name="body" rows="25" style="width:100%"><?= set_value('body',$article->body)?></textarea>

      			</td>
      		</tr>
    		<br/><?=form_error('body')?></td>
			<tr><th>Teaser</th><td><textarea name="excerpt" rows="5" style="width:100%"><?= set_value('excerpt',$article->excerpt)?></textarea></td></tr>
      		<br/><?=form_error('excerpt')?>
      	</table>

</section>

<div style="clear:both"></div>

<nav>
	<button type='submit' id="save-button" class='iconbutton' name='save' value="Update">
		<i class="icon-save icon-2x"></i> Update
	</button>
	<button type='submit' id='delete-button' class='iconbutton' name='rm' value='Delete' onclick="return confirm('Realy delete this article?');">
		<i class='icon-trash icon-2x'></i> Delete
	</button>
	<button type='submit' id='cancel-button' class='iconbutton' name='cancel' value='Cancel'> 
		<i class='icon-reply icon-2x'></i> Cancel 
	</button>
</nav>
<? /*
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
	  <tr>
			<td>
			<table>
				<tr><th>Venue</th></tr>
				<tr><td><?= $venue_select ?></td></tr>
			</table>
			</td>
		</tr>
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
    <!--
		<tr>
			<td>
			<table>
				<tr><th>Lists</th></tr>
        <tr><td><?= $lists_select ?>&nbsp;&nbsp;<a href="javascript:add_to_list();" title="Add to list"><img src="/img/admin/add.png" /></a></td></tr>
			</table>
			</td>
    </tr>
    -->
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
        <td><label for="user">User</label></td>
        <!-- <td colspan="4"><input name="owner" size="60" value="<?= $article->owner ?>" readonly=readonly/></td>
        -->
        <td colspan="4"><?= $user_select ?></td>
        <td/>
        <td/>
      </tr>
      <!--
			<tr>
    		<td><label for="author">Author</label></td>
				<td colspan="4"><input name="author" size="60" value="<?= set_value('author',$article->author)?>"/></td>
        <td><?=form_error('author')?></td>
        <td/>
      </tr>
      -->
      <tr>
        <td><label for="publish_on">Pub Date</label></td>
        <td>
          <input title="YYYY-MM-DD" class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d')?>"/>
        </td>
        <td><label for="section">Section</label></td>
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
    <td><textarea id="article_body" name="body" rows="25" style="width:100%"><?= set_value('body',$article->body)?></textarea>
    <br/><?=form_error('body')?></td>
  </tr>
  <tr>
    <th>Teaser</th>
  </tr>
  <tr>
    <td>
      <textarea name="excerpt" class="mceNoEditor" rows="5" style="width:100%"><?= set_value('excerpt',$article->excerpt)?></textarea>
      <br/><?=form_error('excerpt')?>
		</td>
  </tr>
</table>
</fieldset>

*/ ?>
</form>


<div style="clear:both"></div>