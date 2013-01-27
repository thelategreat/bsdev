
<h3><img src="/img/admin/weather_clouds.png" /> Bad Folks</h3>

<table>
	<tr>
		<td width="70%" valign="top">
			<hr/>
			<table>
			<?php $count = 0; foreach( $blacklist as $item ) { ?>
				<tr <?= ($count % 2 != 0) ? " class='odd'" : ""?>>
					<td><?=$item->email ?></td>
					<td><a href="/admin/maillist/blacklist/rm/<?=$item->id?>" onclick="return confirm('Really remove this?');">remove</td>
				</tr>
			<?php $count++; } ?>
			</table>
			<hr/>
		</td>
		<td>
			<p style="margin: 10px;" class="info small">
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
