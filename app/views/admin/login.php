<h2><img src="/img/lock.png" /> Please log in</h2>  
<form class="login" action="/admin/login" method="post">  
<table>
<tr>  
    <td><label for="username">User name</td>  
    <td><input type="text" name="username" value="" id="username"  /></td>  
</tr>  
<tr>  
    <td><label for="password">Password</td>
    <td><input type="password" name="password" value="" id="password"  /></td>
</tr>  
<tr class="break">
	<td><input type="submit" name="submLogin" value="Log In"  /></td>
	<td align="right">
	<?= isset($error) ? "<p class='error'>$error</p>" : '' ?>    
	</td>
</tr>
</table>  
</form>
<script language="javascript" type="text/javascript">
$("#username").focus();
</script>