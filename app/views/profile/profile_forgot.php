<h2>Forgot your password?</h2>

<p>
	No worries. It happens to us too. Just enter your email below
	and we will send you another.
</p>

<form method="POST">
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
<input type="submit" name="reset" value="Reset" />
<input type="hidden" name="j" value="<?=$n1?>" />
<input type="hidden" name="b" value="<?=$n2?>" />
</form>