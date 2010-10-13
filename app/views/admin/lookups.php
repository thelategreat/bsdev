<script type="text/javascript" src="/js/jquery.editinplace.js"></script>

<script type="text/javascript">

function add_item()
{
	$.post("/admin/lookups/additem", { item: $('#lookups').val(), value: $('#value').val() },
		function( data ) {
			if( data.ok ) {
				$('#value').val('');
				sel_section();
			} else {
				alert(data.msg);
			}
		}, 'json');
}

function remove_item( id )
{
	if( !confirm('Are you sure you want to try and delete this item?')) {
		return;
	}
	
	$.post("/admin/lookups/rmitem", { item: $('#lookups').val(), id: id },
		function( data ) {
			if( data.ok ) {
				sel_section();
			} else {
				alert(data.msg);
			}
		}, 'json');
}

function sel_section()
{
	$('#cat-listing').html('<img src="/img/ajax-loader.gif" />');
	$.post( '/admin/lookups/get_items', { item: $('#lookups').val()},
		function( data ) {
			$('#cat-listing').html(data);
			
			// inplace editing
			$('.inplace-edit').editInPlace({
				callback: function (elem_id, newval, oldval) {
					var edit_ok = true;
					$.post("/admin/lookups/edititem", { item: $('#lookups').val(), id: elem_id, value: newval },
						function( data ) {
							if( !data.ok ) {
								edit_ok = false;
								alert(data.msg);
							}							
						}, 'json' );
					if( edit_ok ) {
						return( newval );
					}
				},
				saving_text: "saving..."
				/*
				url: "/admin/lookups/edititem",
				params: function() {
					return 'item=' + $('#lookups').val();
				},
				success: function() { alert('ok'); },
				error: function() { alert('error'); }
			 */
			});
			
		});
		
}

$(document).ready( function() {
	sel_section();
});
</script>

<h2>Lookups</h2>

<label for="lookup">Section</label>
<select name="lookup" id="lookups" onchange="sel_section();">
	<?php foreach( $lookups as $k => $v ): ?>
		<option value="<?=$k?>"><?=$v[0]?></option>
	<?php endforeach;?>
</select>
<button onclick="sel_section();">Go</button>
<hr/>
	
<div id="cat-listing"></div>
<hr/>
<input name="value" id="value" size="20" /> <button onclick="add_item()">Add</button>