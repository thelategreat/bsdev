<script type="text/javascript">
function do_save()
{
	if( !$('#question').val().trim().length ) {
		alert("You need to fill in a question.");
		return;
	}

	var items = $('.draggable-list li');
	
	if( !items.length ) {
		alert("You need at least 1 answer.");
		return;
	}
	
	var answers = '';
	
	for( var i = 0; i < items.length; i++ ) {
		answers += $(items[i]).text() + "||";
	}	
	
	$.post('/admin/polls/save', 
			{ id: $('#id').val(), question: $('#question').val(), answers: answers },
			function(data) {
				if( data.error.length ) {
					alert(data.error);
				} else {
					window.location = '/admin/polls';
				}
			},'json');
}

function cancel()
{
	window.location='/admin/polls';
}

function add_line( obj )
{
	var txt = $(obj).val();
	if( txt.length ) {
		$('#sortable').append("<li><span class='handle ui-icon ui-icon-arrowthick-2-n-s'></span>"+txt+"</li>");
		$(obj).val('');
		$('#sortable :last-child').bind('dblclick',function(){
			if( confirm('Remove this item?')) {
				$(this).remove();
			}
		});
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
<h4>Poll Question</h4>
<input name="id" id="id" type="hidden" value="<?=$poll->id?>">
<input name="question" id="question" size="60" value="<?=$poll->question?>" />
</form>
<h4>Answers</h4>
<ul id="sortable" class="draggable-list">
<?php foreach( $poll->answers as $answer ) { ?>
	<li class="listitem"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span><?=$answer?></li>
<?php } ?>
</ul>
<input name="ans" id="ans" size="60"/><button onclick="add_line('#ans');">add</button>
<p class="small italic">drag handle to re-order, double click to remove an item</p>
<hr/>
<button class="ok" onclick="do_save()">Save</button>
<button onclick="cancel()">Cancel</button>