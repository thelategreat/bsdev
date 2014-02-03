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

<style type='text/css'>
	div#results div { border-bottom: 1px dashed #ccc; margin-bottom:20px;}
	th {background-color:#cec;}
</style>

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
			$('#search_status, .results').html('');
			$('#search_results').show();
			if (data.status.code == 200) {
				$.each(data.data, function(index, value) {
					$('#search_results_' + value.type + ' .results').append('<div class="search_result ' + value.type + '" data="' + value.id + '"><b>' + value.title + '</b> ' + (value.author ? ' - ' + value.author : '') + (value.dt_start ? ' - ' + value.dt_start: '') + '</div>');
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

        if ($(this).hasClass('article')) {
            $.post('/admin/items/addarticle', 
            	{	article_id: $(this).attr('data'), 
            		item_id: <?= $this->uri->segment(4) ?>,
            		item_type: '<?= $item_type ?>' }, function(data) {
                if (data.ok == true) {
                	$.notify('Item added.', 'success');
                    reload();
                } else {
                	$.notify(data.msg, 'warn');
                }
            }, 'json');
        }
        if ($(this).hasClass('product')) {
            $.post('/admin/items/additem', 
            	{
            		product_id: $(this).attr('data'), 
            		item_id: <?= $this->uri->segment(4) ?>,
            		item_type: '<?= $item_type ?>'}, function(data) {
                if (data.ok == true) {
                	$.notify('Item added.', 'success');
                    reload();
                } else {
                	$.notify(data.msg, 'warn');
                }
            }, 'json');
        }
        if ($(this).hasClass('event')) {
            $.post('/admin/items/addevent', 
            	{
            		event_id: $(this).attr('data'), 
            		item_id: <?= $this->uri->segment(4) ?>,
            		item_type: '<?= $item_type ?>'
            	}, function(data) {
                if (data.ok == true) {
                	$.notify('Event added.', 'success');
                    reload();
                } else {
                	$.notify(data.msg, 'warn');
                }
            }, 'json');
        }
        if ($(this).hasClass('film')) {
        	console.log('add film')
            $.post('/admin/items/addfilm', 
            	{
            		film_id: $(this).attr('data'), 
            		item_id: <?= $this->uri->segment(4) ?>,
            		item_type: '<?= $item_type?>'
            	}, function(data) {
                if (data.ok == true) {
					$.notify('Film added.', 'success');
                    reload();
                } else {
                	$.notify(data.msg, 'warn');
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
	articlesRendered = false;
	productsRendered = false;
	eventsRendered = false;
	filmsRendered = false;

	$.post('/admin/items/item_articles_browser',
        { 	item_id: <?= $this->uri->segment(4) ?>,
        	item_type: '<?= $item_type ?>' },

		function(data) {
			articlesRendered = true;
			$('#article_area').html( data );
			registerHandlers();			
		}
    );
	$.post('/admin/items/item_products_browser',
        { 	item_id: <?= $this->uri->segment(4) ?>,
        	item_type: '<?= $item_type ?>' 
        },
		function(data) {
			$('#product_area').html( data );
			productsRendered  = true;
			registerHandlers();			
		}
    );

	$.post('/admin/items/item_events_browser',
        { 	item_id: <?= $this->uri->segment(4) ?>,
        	item_type: '<?= $item_type ?>' },
		function(data) {
			$('#events_area').html( data );			
			eventsRendered = true;	
			registerHandlers();			
		});

	$.post('/admin/items/item_films_browser',
        { 	item_id: <?= $this->uri->segment(4) ?>,
        	item_type: '<?= $item_type ?>' },
		function(data) {
			$('#films_area').html( data );			
			filmsRendered = true;	
			registerHandlers();			
		});
}

function registerHandlers() {
	if (!filmsRendered || !eventsRendered || !productsRendered || !articlesRendered) return; 
	// All must render, we only want to register handlers once

	$('.remove').click(function() {
		var id = $(this).attr('data');
		
		if ($(this).hasClass('article')) {
			$.post('/admin/items/removearticle', 
				{ 	associated_article_id: $(this).attr('data'), 
					item_id: <?= $this->uri->segment(4) ?>,
					item_type: '<?= $item_type ?>' }, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
		}

		if ($(this).hasClass('book')) {
			$.post('/admin/items/removeitem', 
				{	product_id: $(this).attr('data'), 
					item_id: <?= $this->uri->segment(4) ?>,
					item_type: '<?= $item_type ?>'
				}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
		}
		
		if ($(this).hasClass('event')) {
			$.post('/admin/items/removeevent', 
				{	event_id: $(this).attr('data'), 
					item_id: <?= $this->uri->segment(4) ?>,
					item_type: '<?= $item_type ?>'
				}, function(data) {
                if (data.ok == true) {
                    reload();
                }
            }, 'json');
		}

		if ($(this).hasClass('film')) {
			$.post('/admin/items/removefilm', 
				{	film_id: $(this).attr('data'), 
					item_id: <?= $this->uri->segment(4) ?>,
					item_type: '<?= $item_type ?>'
				}, function(data) {
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

<style type='text/css'>
	.results {max-height: 200px;overflow-y:scroll;}
</style>

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
<div id="search_results" style='width:100%'>
	<div id="search_results_article" style='width:22%;margin-right:2%;float:left;'><header>Articles</header><div class='results'></div></div>
	<div id="search_results_product" style='width:22%;margin-right:2%;float:left'><header>Products</header><div class='results'></div></div>
	<div id="search_results_event" style='width:22%;margin-right:2%;float:left'><header>Events</header><div class='results'></div></div>
	<div id="search_results_film" style='width:22%;float:left'><header>Films</header><div class='results'></div></div>
</div>
<div style='clear:both'></div>

<hr/>
<div id='results'>
	<div id="article_area" ></div>
	<div id="product_area" ></div>
	<div id="events_area" ></div>
	<div id="films_area" ></div>
</div>
<hr/>
