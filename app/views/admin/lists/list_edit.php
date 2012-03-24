<script type="text/javascript">
function do_save()
{
	if( !$('#name').val().trim().length ) {
    alert("You need to fill in a name!");
		return;
	}

	var items = $('.draggable-list li');
  
  var litems = '';
	
	for( var i = 0; i < items.length; i++ ) {
		litems += $(items[i]).text() + "||";
	}	
	
	$.post('/admin/lists/save', 
			{ id: $('#id').val(), name: $('#name').val(), items: litems },
			function(data) {
				if( data.error.length ) {
					alert(data.error);
				} else {
					window.location = '/admin/lists';
				}
			},'json');
}

function cancel()
{
	window.location='/admin/lists';
}

function rm()
{
	if( confirm('Really delete this list?')) {
		window.location='/admin/lists/rm/' + $('#id').val();		
	}
}

$(function() {
  	$("#sortable li").bind('dblclick',function(){
			if( confirm('Remove this item?')) {
				$(this).remove();
			}
		});
		$("#sortable").sortable({
			revert: true,
			handle: '.handle'
		});
});

</script>

<form method="post" id="poll_form">
<h4>List</h4>
<input name="id" id="id" type="hidden" value="<?=$list->id?>">
<input name="name" id="name" size="60" value="<?=$list->name?>" />
</form>
<h4>Items</h4>
<ul id="sortable" class="draggable-list">
<?php foreach( $list->items as $item ) { ?>
	<li class="listitem"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span><?=$item?></li>
<?php } ?>
</ul>
<p class="small italic">drag handle to re-order, double click to remove an item</p>
<hr/>
<button class="save-button" class="ok" onclick="do_save()">Save</button>
<button class="cancel-button" onclick="cancel()">Cancel</button>
&nbsp;&nbsp;&nbsp;&nbsp;
<button class="delete-button" onclick="rm()">Delete</button>
