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
 <li><a href="/profile/page/purchase">Purchasing</a></li>
 <li><a class="selected" href="/profile/page/history">History</a></li>
</ul>
</div>

<p>
You haven't placed any orders yet.
</p>

