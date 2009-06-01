<h2><img src="/img/user_add.png" /> Add User</h2>

<form class="general" action="/admin/users/add" method="post">
<fieldset><legend>Details</legend>
<table style="border: 0">
  <tr>
    <td><label for="username">Username</label></td>
    <td><input name="username" size="30" value="<?=set_value('username')?>"/></td>
	<td><?=form_error('username')?></td>
  </tr>
	  <tr>
	    <td valign="top"><label for="firstname">First Name</label></td>
	    <td><input name="firstname" size="30" value="" /></td>
		<td/>
	  <tr>
	    <td valign="top"><label for="lastname">Last Name</label></td>
	    <td><input name="lastname" size="30" value="" /></td>
		<td/>
  <tr>
  <tr>
    <td valign="top"><label for="role">Role</label></td>
    <td><?= $role_select ?></td>
    <td/>
  </tr>
  <tr>
    <td valign="top"><label for="passwd">Password</label></td>
    <td><input type="password" name="passwd" size="30" autocomplete="off" value=""/></td>
	<td><?=form_error('passwd')?></td>
  </tr>
  <tr>
    <td valign="top"><label for="passwd">Verify Password</label></td>
    <td><input type="password" name="vpasswd" size="30" autocomplete="off" value=""/></td>
	<td><?=form_error('vpasswd')?></td>
  </tr>
  <tr>
    <td valign="top"><label for="email">Email</label></td>
    <td><input name="email" size="30" value="<?=set_value('email')?>"/></td>
	<td><?=form_error('email')?></td>
  </tr>
</table>
</fieldset>
<input type="submit" name="save" value="Create User" />
<input type="submit" name="cancel" value="Cancel" />
</form>
