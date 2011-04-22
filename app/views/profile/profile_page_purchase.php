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
 <li><a href="/profile/page/social">Social</a></li>
 <li><a href="/profile/page/subscribe">Subscriptions</a></li>
 <li><a class="selected" href="/profile/page/purchase">Purchasing</a></li>
 <li><a href="/profile/page/history">History</a></li>
</ul>
</div>

<p>
If you save this info we will fill it in automatically
when you use our check out service.
</p>

<form method="post">

<table>
<tr><td><label for="ccno">Credit Card</label></td><td><input name="ccno" value="<?=$ccno?>"></td></tr>
<tr><td>Name on Card</td><td><input name="ccname" value="<?=$ccname?>"></td></tr>
<tr><td>Expiry</td><td><input name="ccexp" size="5" value="<?=$ccexp?>"> mm/yy</td></tr>
</table>

<hr/>

Bill To <br/>
<textarea name="billto" rows="5" cols="50"><?=$billto?></textarea>

<br/>

Ship To <br/>
<textarea name="shipto" rows="5" cols="50"><?=$shipto?></textarea>


<hr/>
<input type="submit" name="update" value="Save" />

</form>

