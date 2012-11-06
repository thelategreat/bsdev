<?php
/*
Generic media tab for system

pass in the following minimum:

$page_data = array(
'tabs' => $this->tabs->gen_tabs($this->page_tabs, $cur_tab, '/admin/venues/edit/' . $venue->id),
'title' => "The Tab Title",											// the page title
'path' => '/pages/' . $page->id,								// the page in the db for this
'next' => "/admin/pages/edit/$page->id/media",  // the web path to this tab
);

optionally you can pass in:

$page_data['slot'] = 'general';									// the current page slot
$page_data['slots] = 'front,back';              // any extra slots required. comma sep

- slots are simply sub categories. the default is 'general' They can be used as
  bin on the page, layouts oriented stuff

*/
?>

<script language="javascript" type="text/javascript">
$(document).ready(function() {
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
			$('#search_status, #search_results').html('');
			if (data.status.code == 200) {
				$.each(data.data, function(index, value) {
					$('#search_results').append('<div class="search_result ' + value.type + '" data="' + value.id + '"><b>' + value.title + '</b> - ' + value.author + '</div>');
				});
			} else {
				$('#search_status').html(data.status.message);
			}

			registerResultClicks();
		}, 'json');
	});
	
});

function registerResultClicks() {
    $('.search_result').click(function() {
        if ($(this).hasClass('book')) {
            $.post('/admin/articles/additem', {product_id: $(this).attr('data'), article_id: <?= $this->uri->segment(4) ?>}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
        }
        if ($(this).hasClass('event')) {
            $.post('/admin/articles/addevent', {event_id: $(this).attr('data'), article_id: <?= $this->uri->segment(4) ?>}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
        }
	});
}

function reload()
{
	slot = 'general';
	if( $('#slot_select')) {
		slot = $('#slot_select').val();
	}
	$.post('/admin/products/article_products_browser',
        { article_id: <?= $this->uri->segment(4) ?> },
		function(data) {
			$('#product_area').html( data );
		}
    );

	$.post('/admin/event/article_events_browser',
        { article_id: <?= $this->uri->segment(4) ?> },
		function(data) {
			$('#events_area').html( data );			
			registerHandlers();			
		});
}

function registerHandlers() {
	$('.remove').click(function() {
		var id = $(this).attr('data');
		if ($(this).hasClass('book')) {
			$.post('/admin/articles/removeitem', {product_id: $(this).attr('data'), article_id: <?= $this->uri->segment(4) ?>}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
		}
		
		if ($(this).hasClass('event')) {
			$.post('/admin/articles/removeevent', {event_id: $(this).attr('data'), article_id: <?= $this->uri->segment(4) ?>}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
		}
	});
}

$(function() {
		reload();
});
</script>

<?=$tabs?>

<h3><?=$title?></h3>

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

Search for item: <input type="text" name="search" /><button id="search_submit">Search</button><div id="search_status"></div><br/>
<div id="search_results"></div>

<hr/>
<div id="product_area" ></div>
<div id="events_area" ></div>
<hr/>
