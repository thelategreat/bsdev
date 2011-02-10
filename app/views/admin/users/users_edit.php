
<h3><img src="/img/admin/user_edit.png" /> Edit User</h3>

<form class="general" action="/admin/users/edit/<?=$user->id?>" method="post">
<fieldset><legend>Details</legend>
<table style="border: 0">
  <tr>
    <td><label for="username">Username</label></td>
    <td><input name="username" size="30" value="<?=$user->username?>" readonly="1"/></td>
		<td><?=form_error('username')?></td>
  </tr>
	  <tr>
	    <td valign="top"><label for="firstname">First Name</label></td>
	    <td><input name="firstname" size="30" value="<?= $user->firstname ?>" /></td>
		<td/>
	  <tr>
	    <td valign="top"><label for="lastname">Last Name</label></td>
	    <td><input name="lastname" size="30" value="<?= $user->lastname ?>" /></td>
		<td/>
  <tr>
  <tr>
    <td valign="top"><label for="role">Role</label></td>
    <td><?= $role_select ?></td>
	<td/>
  </tr>
  <tr>
    <td valign="top"><label for="passwd">Password</label></td>
    <td><input type="password" name="passwd" size="30" autocomplete="off" value=""/>
		<br/><small><i>leave this blank if you don't want to change the current password</i></small>
		</td>
		<td><?=form_error('passwd')?></td>
  </tr>
  <tr>
    <td valign="top"><label for="vpasswd">Verify Password</label></td>
    <td><input type="password" name="vpasswd" size="30" autocomplete="off" value=""/></td>
		<td><?=form_error('vpasswd')?></td>
  </tr>
  <tr>
    <td valign="top"><label for="active">Active</label></td>
    <td>
      <input type="checkbox" name="active" <?=$user->active ? "checked" : ""?> />
    </td>
    <td/>
  </tr>
  <tr>
    <td valign="top"><label for="email">Email</label></td>
    <td><input name="email" size="30" value="<?=$user->email?>"/></td>
	<td><?=form_error('email')?></td>
  </tr>
</table>
</fieldset>
<input class="ok" type="submit" name="save" value="Update" />
<input type="submit" name="cancel" value="Cancel" />
&nbsp;&nbsp;
<?php if( $user->id != 1 ) { ?>
	<input style="background-color: #fbb" type="submit" name="rm" value="Delete" onclick="return confirm('Really delete this user?');" />
<?php } ?>
</form>
<p/>
