<script type="text/javascript">
function approve( id )
{
	window.location = '/admin/comments/approve/' + id;
}
function deny( id )
{
	window.location = '/admin/comments/deny/' + id;
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
	<tr class="<?= ($count % 2) == 0 ? 'odd' : ''?>" <? if ($comment->approved == 0) { ?> class='unapproved' style='background-color:#fcfc00' <? } ?>>
		<td valign="top">
			<a href="/admin/users/edit/<?=$comment->user_id?>"><?=$comment->firstname?> <?=$comment->lastname?></a><br/>
			<?=$comment->comment_date?>
		</td>
    <td valign="top" width="65%">
      <?php if( $comment->approved == 2 ) echo "<s>"; ?>
      <?=$comment->comment?>
      <?php if( $comment->approved == 2 ) echo "</s>"; ?>
    </td>
		<td valign="top" align="right">
		<? if ($comment->approved != 1) { ?>
      <button onclick="approve('<?=$comment->id?>')" style="color: #0F0">Approve</button>
      	<? } ?>
      <?php if( $comment->approved != 2 ) { ?>
        <button onclick="deny('<?=$comment->id?>')" style="color: #ffcc00;">Deny</button>
      <?php } ?>
      &nbsp;&nbsp;|&nbsp;
			<button onclick="remove('<?=$comment->id?>')" style="color: #f00;">Delete</button>
		</td>
	</tr>
<?php $count++; } ?>
</table>

<?= $pager ?>
