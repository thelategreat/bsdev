<script type="text/javascript">
function approve( id )
{
	window.location = '/admin/comments/approve/' + id;
}

function remove( id )
{
	if( !confirm("Really delete this comment?")) {
		return;
	}
	window.location = '/admin/comments/remove/' + id;
}
</script>
<h3>Comments Queue</h3>

<table>
<?php
 	$count = 0;
	foreach( $comments->result() as $comment ) { ?>
	<tr class="<?= ($count % 2) == 0 ? 'odd' : ''?>">
		<td valign="top">
			<a href="/admin/users/edit/<?=$comment->user_id?>"><?=$comment->firstname?> <?=$comment->lastname?></a><br/>
			<?=$comment->comment_date?>
		</td>
		<td valign="top" width="65%"><?=$comment->comment?></td>
		<td valign="top">
			<button onclick="approve('<?=$comment->id?>')">Approve</button>
			<button onclick="remove('<?=$comment->id?>')">Delete</button>
		</td>
	</tr>
<?php $count++; } ?>
</table>

<?= $pager ?>