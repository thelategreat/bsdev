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
			{ id: $('#id').val(), question: $('#question').val(), poll_date: $('#poll_date').val(), poll_end_date: $('#poll_end_date').val(), answers: answers },
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

function rm()
{
	if( confirm('Really delete this poll?')) {
		window.location='/admin/polls/rm/' + $('#id').val();		
	}
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
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({ startDate: '2000-01-01' });
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
<h4>Poll</h4>
<input name="id" id="id" type="hidden" value="<?=$poll->id?>">
<table>
  <tr>
    <td><label for="question">Question</label></td><td><input name="question" id="question" size="60" value="<?=$poll->question?>" /></td>
  </tr>
  <tr>
    <td><label for="poll_date">Start Date</label></td>
		<td><input class="date-pick" name="poll_date" size="12" onblur="" id="poll_date" value="<?=$poll->poll_date?>"/><span class="small">yyyy-mm-dd<span></td>
  </tr>
  <tr>
    <td><label for="poll_end_date">End Date</label></td>
		<td><input class="date-pick" name="poll_end_date" size="12" onblur="" id="poll_end_date" value="<?=$poll->poll_end_date?>"/><span class="small">yyyy-mm-dd<span></td>
  </tr>
</table>
</form>
<h4>Answers</h4>
<ul id="sortable" class="draggable-list">
<?php foreach( $poll->answers as $answer ) { ?>
	<li class="listitem"><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span><?=$answer->answer?></li>
<?php } ?>
</ul>
<input name="ans" id="ans" size="60"/><button onclick="add_line('#ans');">add</button>
<p class="small italic">drag handle to re-order, double click to remove an item</p>
<hr/>
<button class="save-button" class="ok" onclick="do_save()">Save</button>
<button class="cancel-button" onclick="cancel()">Cancel</button>
&nbsp;&nbsp;&nbsp;&nbsp;
<button class="delete-button" onclick="rm()">Delete</button>
