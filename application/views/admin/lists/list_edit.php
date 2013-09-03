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
  $.post('/search/json',
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


function do_search1( page )
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


// Save the data
function do_save()
{
	if( !$('#name').val().trim().length ) {
    $('#name').addClass('field_error');
    alert("You must provide a list name!");
		return;
	}

	var items = $('#target-sortable li');

  var itemList = [];  

  for( var i = 0; i < items.length; i++ ) {
    var li = new Object();
    li.id = $(items[i]).attr('data-id');
    li.type = $(items[i]).attr('data-type');
    li.title = $(items[i]).text();
    itemList.push( li );
  }	

  var can_delete = $('#can_delete').is(':checked') ? 1 : 0;

	$.post('/admin/lists/save', 
			{ 
        id: $('#id').val(), 
        name: $('#name').val(), 
        can_delete: can_delete, 
        items: $.toJSON(itemList) 
      },
			function(data) {
				if( data.error) {
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
      placeholder: "ui-state-highlight"
    });

    // trigger search on enter
    $("#query-box").keyup(function(event){
      if(event.keyCode == 13){
        $("#query-button").click();
      }
    });


   $('input[name="search"]').keypress(function(e) {
        if(e.which == 13) {
            jQuery(this).blur();
            jQuery('#search_submit').focus().click();
            return false;
        }
    });

  $('#search_submit').click(function() {
    var q = $('input[name="search"]').val();
    $('#search_status').html('Searching...');
    $.post('/search/json', {q: q, size: 25, type: 'bf'}, function(data) {
      $('#search_status, .results').html('');
      if (data.status.code == 200) {
        $.each(data.data, function(index, value) {
          $('#search_results_' + value.type + ' .results').append('<li class="listitem color_' + value.type + ' " data-id="' + value.id + '" data-type="' + value.type + '"><b>' + value.title + (value.dt_start != null ? " <time>" + value.dt_start + "</time>" : "") + '</b> ' + (value.author ? ' - ' + value.author : '') + '</li>');
        });
      } else {
        $('#search_status').html(data.status.message);
      }

      //registerResultClicks();

      $('.draggable-list li').draggable({
          revert: "true",
          helper: "clone",
          connectWith: "#target-sortable"
        });

     $('.draggable-list li').draggable({
          revert: "true",
          helper: "clone",
          connectToSortable: "#target-sortable"
        });
        $('#target-sortable').droppable({
          drop: function( event, ui ) {
            // bind removal event
            ui.draggable.unbind('dblclick');
            ui.draggable.bind('dblclick',function(){
              if( confirm('Remove this item?')) {
                $(this).remove();
              }
            });
            //ui.draggable.prepend('<span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>'  + $(ui).attr('data-type') + ': ');
            //alert("dropped id: " + ui.draggable.attr('id'));
          }          


        }); 
    }, 'json');
  });
  
});

</script>


<div class=container>
  <header>List Editor <? if (isset($list->name)) echo "- {$list->name}"; ?></header>

  <aside class=instruction>
    Search for items to add to the list from the search box, add to the list by dragging. Reorder list items by dragging the handle. Double-click items to remove from the list.
  </aside>


  <nav>
    <a href="/admin/lists">
      <button id='btn_save'>
        <i class="icon-chevron-left icon"></i> Cancel
      </button>
    </a>

    <a href="#" onclick="do_save()">
      <button id='btn_cancel'>
        <i class="icon-save icon"></i> Save
      </button>
    </a>

    <a href="#" onclick="rm()">
      <button id='btn_delete'>
        <i class="icon-remove icon"></i> Delete
      </button>
    </a>
  </nav>

  <br>


<?php if( !isset($slot)) { $slot = 'general'; ?>
  <input type="hidden" id="slot_select" name="slot" value="general" />
<?php } else { ?>
Slot: <select id="slot_select" name="slot" onchange="reload()">
  <option <?=$slot == 'general' ? 'selected' : ''?>>general</option>
<?php foreach( explode(",", $slots) as $s ) {
  if( !empty($s) && strlen(trim($s)) ) { ?>
      <option <?=$slot == $s ? 'selected' : ''?>><?=$s?></option>
<?php }
  }
} ?>
</select>


<form method="post" id="poll_form">
  <br>

  List Name: <input name="name" id="name" size="60" value="<?=$list->name?>" />
  <input name="id" id="id" type="hidden" value="<?=$list->id?>">
  <input type="checkbox" id="can_delete" <?= $list->can_delete ? "checked" : "" ?> /> Deletable
</form>
<br/>
<br/>

  <div style='border: 1px solid black'>
  <div style='background: #ccc;width:100%'>Items - <i>Drag to reorder, double-click to remove</i></div>
  <ul id="target-sortable" class="draggable-list">
  <?php foreach( $list->items as $item ) { ?>
    <li class="listitem color_<?=$item->type;?>" data-id="<?=$item->data_id?>" data-type="<?=$item->type;?>">
      <?=$item->title?>
    </li>
  <?php } ?>
  </ul>
  </div>


<br><br><br>


Search for essays, products, and events: <input type="text" name="search" /><button id="search_submit">Search</button><div id="search_status"></div><br/>
<div id="search_results" style='width:100%; border: 1px solid #ccc;height:20px;'>
  
  <div style='width:24%;margin-right:1%;float:left;height:auto'><h4 class='color_article'>Article</h4><hr>
    <div id='search_results_article' style='height:170px;overflow-y:scroll'>
      <ul class='results draggable-list'></ul>
    </div>
  </div>

  <div style='width:24%;margin-right:1%;float:left;height:auto'><h4 class='color_product'>Product</h4><hr>
    <div id='search_results_product' style='height:170px;overflow-y:scroll'>
      <ul class='results draggable-list'></ul>
    </div>
  </div>

  <div style='width:24%;margin-right:1%;float:left;height:auto'><h4 class='color_event'>Events</h4><hr>
    <div id='search_results_event' style='height:170px;overflow-y:scroll'>
      <ul class='results draggable-list'></ul>
    </div>
  </div>

  <div style='width:24%;margin-right:1%;float:left;height:auto'><h4 class='color_film'>Films</h4><hr>
    <div id='search_results_film' style='height:170px;overflow-y:scroll'>
      <ul class='results draggable-list'></ul>
    </div>
  </div>
  <div style='clear:both'></div>
</div>
