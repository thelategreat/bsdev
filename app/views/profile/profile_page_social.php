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
 <li><a href="/profile/page/">Identity</a></li>
 <li><a class="selected" href="/profile/page/social">Social</a></li>
 <li><a href="/profile/page/subscribe">Subscriptions</a></li>
 <li><a href="/profile/page/purchase">Purchasing</a></li>
 <li><a href="/profile/page/history">History</a></li>
</ul>
</div>

<p>
This info is public and shared with the community.
</p>

<form method="post">

<table>
<tr>
<td><label for="nick">Nickname</label></td><td><input name="nick" value="<?=$nickname?>" ></td></tr>
<td><label for="url">Web Page</label></td><td><input name="url" value="<?=$webpage?>"></td></tr>
  <td>
    <label for="url">Chat</label></td>
    <td><input name="chatnick" value="<?=$chatnick?>" > @ 
    <select name="chatservice">
      <option value="gtalk">Google Talk</option>
      <option value="yahoo">Yahoo</option>
      <option value="msn">MSN</option>
      <option value="skype">Skype</option>
      <option value="jabber">Jabber</option>
      <option value="other">Other</option>
    </select>
  </td></tr>
</table>

<hr/>
<input type="submit" name="update" value="Save" />


</form>





