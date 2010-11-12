<?php
/**
 * Used by the popup media selector
 */
?>
<script type="text/javascript">
$(function() {		
	Gallery.init({
		width: 800,
		onclick : function( uuid ) {
			$.post('/admin/media/add', { path: '<?= $path ?>', uuid : uuid, slot : '' },
				function(data) {
					if( data.error ) {
						alert( data.error_msg );
						return;
					}
					// this click func needs to return false 
					// to give the ajax call a chance to complete
					$.modal.close();
					// TODO: this kindo defeats the purpose of ajax however
					window.location.reload();
				}, "json" );
				return false;
			}
	});	
});

// search... hmmm....
function search_handler()
{
	Gallery.render( 1, $('#q').val());
	return false;
}

</script>


<div style="float: right">
	<form id="search_form" method="post" onsubmit="return search_handler();">
		<input id="q" name="q" value="<?foreach($stags as $tag){ echo $tag . " ";}?>" />
	</form>
</div>

<h3>Media Librarie</h3>

<?= $errors ?>

<div class="gallery" id="gallery-div"></div>

<div style="float: right" id="next-button">
	<a href='#'><img src="/img/cal/arrow_right.png" width="20px"/></a>
</div>
<div style="float: left" id="prev-button">
	<a href='#'><img src="/img/cal/arrow_left.png" width="20px"/></a>
</div>

<div id="work-area" style="display: none" />
