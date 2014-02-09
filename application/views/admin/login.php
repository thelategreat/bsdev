<form class="login" action="/admin/login" method="post">  
<table>
<tr>  
    <td>User name:</td><td><input class='short' type="text" name="username" value="" id="username"  /></td>  
</tr>  
<tr>  
    <td>Password:</td><td><input class='short' type="password" name="password" value="" id="password"  /></td>
</tr>  
<tr class="break">
	<td><input type="submit" class='short' name="submLogin" value="Log In"  /></td>
	<td align="right">
	<?= isset($error) ? "<p class='error'>$error</p>" : '' ?>    
	</td>
</tr>
</table>  
	<input type="hidden" name="redir" value="<?=$login_redir?>" />
</form>

<script language="javascript" type="text/javascript">
$("#username").focus();
</script>