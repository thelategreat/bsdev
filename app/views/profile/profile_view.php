<div style="float: right; background-color: #000; color: #fff; padding: 10px;">
	<a style="color: #fff;" href="/profile/logout">logout</a>
</div>
<h2>Your Bookshelf</h2>

<p>
Hi! This is your profile page. Can't do much here yet, but
it's coming!
</p>

<hr/>
<?=$error?>

<form method="POST">
	<table>
		<tr class="odd">
			<td>Email</td><td><?=$email?></td>
		</tr>
		<tr class="odd">
			<td>Since</td><td><?=$created_on?></td>
		</tr>
		<tr>
			<td>First Name</td><td><input name="firstname" value="<?=$firstname?>"/></td>
		</tr>
		<tr>
			<td>Last Name</td><td><input name="lastname" value="<?=$lastname?>"/></td>
		</tr>
		<tr>
			<td>Password</td>
			<td>
				<input name="password" type="password" /><br/>
				<small>just leave this blank if you don't want to change your password</small>
			</td>
		</tr>
		<tr>
			<td>Verify</td><td><input name="password2" type="password" /></td>
		</tr>
	</table>

<hr/>
<h3>Your Communications</h3>
<table>
	<? foreach( $maillists as $list ) { ?>
	<tr>
		<td><input name="list_<?=$list[0]?>" type="checkbox" <?=$list[3] ? "checked" : ""?> /></td>
		<td><?=$list[1]?></td>
		<td><?=$list[2]?></td>
	</tr>
	<? } ?>
</table>

<hr/>
<input type="submit" name="update" value="Save" />

</form>

