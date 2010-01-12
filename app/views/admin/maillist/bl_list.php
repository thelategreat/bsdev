
<h3>Bad Folks</h3>

<table>
	<tr>
		<td width="70%" valign="top">
			<hr/>
			<table>
			<?php foreach( $blacklist as $item ) { ?>
				<tr>
					<td><?=$item->email ?></td>
					<td><a href="/admin/maillist/blacklist/rm/<?=$item->id?>" onclick="return confirm('Really remove this?');">remove</td>
				</tr>
			<?php } ?>
			</table>
			<hr/>
		</td>
		<td>
			<p style="margin: 10px">
				These email addresses are ignored for all communications. They cannot sign 
				up for mailing lists and if they happen to be on a mailing list already,
				they never receive and mail. If they are not registered they are unable 
				to register.
			</p>
		</td>
	</tr>
</table>

<form method="POST">
	<label for="email">Email</label>
	<input name="email" value="" />
	<input type="submit" name="add" value="Add" />
</form>
