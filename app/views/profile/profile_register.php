<h2>Register</h2>

<?=$error?>

<form method="post">
	<table>
		<tr>
			<td><label for="email">Email</label></td>
			<td><input name="email" value="" /></td>
		</tr>
		<tr>
			<td>What is <?=$n1?> + <?=$n2?> = </td>
			<td><input name="cap" value="" size="2" /></td>
		</tr>
	</table>
	<input type="submit" name="register" value="Register" />
	<input type="hidden" name="j" value="<?=$n1?>" />
	<input type="hidden" name="b" value="<?=$n2?>" />
</form>
<hr/>
<p>Already registered? Please <a href="/profile/login">login</a>.
<hr/>
<p>We require a valid email address for you to register. We will send a 
	confirmation email to the address you enter. Simply follow the instructions
	within.
</p>
<p>The math is to prevent automated scripts from filling our database 
	and mail servers with garbage.
</p>