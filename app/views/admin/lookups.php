<script type="text/javascript">

function sel_section()
{
	$.post( '/admin/lookups/get_items', { item: $('#lookups').val()},
		function( data ) {
			$('#cat-listing').html(data);
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