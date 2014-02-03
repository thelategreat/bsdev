<script type='text/javascript'>
$(function() {		
	$('#search_submit').click(function(e) {
		e.preventDefault();
		$('#search_status').html('Searching...');

		$.post('/admin/media/search_json', {q:$('#search').val()}, function(data) {
			var area = $('#search_results .items');
			$(area).html('');
			$('#search_status').html('');

			if (data != null) {
				$.each(data, function(i, item) {
					area.append('<div class="result-image" data-media-id="'+item.id+'" data-uuid="'+item.uuid+'"><img src="/i/size/o/media--'+item.uuid+'/w/70"></div>');
				});
				$('.result-image').click(function() {
					$(this).toggleClass('selected');
				});
			}
		}, 'json');

		$('#search_results').show();
	});

	$('#searchform').submit(function(e) {
		e.preventDefault();
	});

});
</script>

<div class=container>
	<header>Media Library</header>
	<br>

<br>
<div> 
	<form id='searchform' method='post'>
		Search for media: <input type="text" id='search' name="search" /><button id="search_submit">Search</button><div id="search_status"></div><br/>
	</form>
</div>

