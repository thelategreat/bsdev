<div style="float: right">
	<a href="/profile/logout">logout</a>
</div>
<h2>Your Bookshelf</h2>

<p>
Hi there, this is your profile page. Can't do much here yet, but
it's coming!
</p>
<hr/>
<?=$error?>

<form method="POST">
	<table>
		<tr>
			<td>User</td><td><?=$username?></td>
		</tr>
		<tr>
			<td>Email</td><td><?=$email?></td>
		</tr>
		<tr>
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
	<input type="submit" name="update" value="Update" />
</form>

