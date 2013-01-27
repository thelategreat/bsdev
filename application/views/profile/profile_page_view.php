<div style="float: right; background-color: #000; color: #fff; padding: 10px; border-radius: 5px;">
	<a style="color: #fff;" href="/profile/logout">logout</a>
</div>

<h2>Your Bookshelf</h2>

<p>
Hi <?=$firstname?>! This is your profile page.
</p>

<hr/>
<?=$error?>

<div class="tabs">
<ul>
 <li><a class="selected" href="/profile/page/">Identity</a></li>
 <li><a href="/profile/page/social">Social</a></li>
 <li><a href="/profile/page/subscribe">Subscriptions</a></li>
 <li><a href="/profile/page/purchase">Purchasing</a></li>
 <li><a href="/profile/page/history">History</a></li>
</ul>
</div>

<form method="POST">
	<table>
		<tr class="odd">
			<td>Email</td><td><?=$email?></td>
		</tr>
		<tr class="odd">
			<td>Since</td><td><?=$created_on?></td>
		</tr>
		<tr class="odd">
			<td>Last Seen</td><td><?=$last_seen?></td>
		</tr>
		<tr>
			<td>First Name</td><td><input name="firstname" value="<?=$firstname?>"/></td>
		</tr>
		<tr>
			<td>Last Name</td><td><input name="lastname" value="<?=$lastname?>"/></td>
		</tr>
	</table>
	<a href="#" onclick="$('#pass').toggle('slow');">Change Password</a>
	<table id="pass" style="display: none">
		<tr>
			<td>Currrent Password</td>
			<td>
				<input name="cpassword" type="password" /><br/>
				<small>just leave this blank if you don't want to change your password</small>
			</td>
		</tr>
		<tr>
			<td>New Password</td>
			<td>
				<input name="password" type="password" />
			</td>
		</tr>
		<tr>
			<td>Verify</td><td><input name="password2" type="password" /></td>
		</tr>
	</table>

<hr/>
<input type="submit" name="update" value="Save" />

</form>

