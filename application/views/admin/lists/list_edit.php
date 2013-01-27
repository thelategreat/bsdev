<script type="text/javascript">
// hrmmm
jQuery.extend({
    postJSON: function(url, data, callback) {
      return jQuery.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(data),
        success: callback,
        dataType: "json",
        contentType: "application/json",
        processData: false
      });
    }
  });


function do_search( page )
{
  if( !page ) page = 1;
  $.post('/admin/lists/search',
    { q: $('#query-box').val(), page: page },
    function(data) {
      if( !data.ok ) {
        alert( data.msg );
      } else {
        var html = '<ul id="draglist" class="draggable-list">';
        for( var i = 0; i < data.data.length; i++ ) {
          html += '<li id="' + data.data[i].id + '">' + data.data[i].title + '</li>';
        }
        html += '</ul>'
        $('#search-results').html( html );
        // make drag-n-drop
        $('#draglist li').draggable({
          revert: "invalid",
          helper: "clone",
          connectToSortable: "#target-sortable"
        });
        $('#target-sortable').droppable({
          drop: function( event, ui ) {
            // make it sortable
            ui.draggable.prepend('<span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>');
            // bind removal event
            ui.draggable.bind('dblclick',function(){
			        if( confirm('Remove this item?')) {
				        $(this).remove();
			        }
            });          
            //alert("dropped id: " + ui.draggable.attr('id'));
          }
        });
      }
    }, 'json' );
}

function do_save()
{
	if( !$('#name').val().trim().length ) {
    alert("You need to fill in a name!");
		return;
	}

	var items = $('#target-sortable li');
  
  var litems = new Array();
	
  for( var i = 0; i < items.length; i++ ) {
    var li = new Object();
    li.id = $(items[i]).attr('id');
    li.title = $(items[i]).text();
    li.type = 'essay'; 
    litems.push( li );
  }	

  var can_delete = $('#can_delete').is(':checked') ? 1 : 0;

	$.post('/admin/lists/save', 
			{ id: $('#id').val(), name: $('#name').val(), can_delete: can_delete, items: $.toJSON(litems) },
			function(data) {
				if( data.error.length ) {
					alert(data.error);
				} else {
					window.location = '/admin/lists';
				}
			},'json');
}


function do_save1()
{
	if( !$('#name').val().trim().length ) {
    alert("You need to fill in a name!");
		return;
	}

	var items = $('#target-sortable li');
  
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
  	$("#target-sortable li").bind('dblclick',function(){
			if( confirm('Remove this item?')) {
				$(this).remove();
			}
		});
		$("#target-sortable").sortable({
			revert: true,
      handle: '.handle',
      placeholder: "ui-state-highlight"
    });
    // trigger search on enter
    $("#query-box").keyup(function(event){
      if(event.keyCode == 13){
        $("#query-button").click();
      }
    });

});

</script>


<table>
<tr><td  width="50%" valign="top">
<form method="post" id="poll_form">
<h4>List</h4>
<input name="id" id="id" type="hidden" value="<?=$list->id?>">
<input name="name" id="name" size="60" value="<?=$list->name?>" /><p/>
<input type="checkbox" id="can_delete" <?= $list->can_delete ? "checked" : "" ?> /> Deletable
</form>
<?php if( !$adding ) { ?>
<h4>Items</h4>
<ul id="target-sortable" class="draggable-list" style="background-color: #eee8aa;">
<?php foreach( $list->items as $item ) { ?>
  <li class="listitem" id="<?=$item->data_id?>"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span><?=$item->url?></li>
<?php } ?>
</ul>
<p class="small italic">drag handle to re-order, double click to remove an item</p>
<?php } // if( !$adding ) ?>
<hr/>
<button class="save-button" class="ok" onclick="do_save()">Save</button>
<button class="cancel-button" onclick="cancel()">Cancel</button>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php if( !$adding ) { ?>
<button class="delete-button" onclick="rm()">Delete</button>
<?php } ?>
</td>

<?php if( !$adding ) { ?>
<td width="50%" valign="top">
<button onclick="javascript:void()">&lt;</button>
<button onclick="javascript:void()">&gt;</button> &nbsp;
<input name="q" id="query-box" size="40" /> <button id="query-button" onclick="do_search()">Search</button>
<hr/>
<div id="search-results">

</div>
</td>
<?php } // if( !$adding ) ?>

</tr>
</table>


