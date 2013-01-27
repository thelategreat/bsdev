

<fieldset class="general">
<legend>Bookshelf Login</legend>
<form action="/profile/login" method="post">
	<table>
		<tr>
			<td>Your Email</td>
			<td><input name="user" value="" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input name="password" type="password" value="" /></td>
		</tr>
	</table>
	<input type="submit" name="login" value="Log In" />
</form>
</fieldset>

<p>Don't have a login? Why not <a href="/profile/register">register</a>? Or maybe you <a href="/profile/forgot">forgot</a> your password?</p>

<!--
<script type="text/javascript">

var oid_providers = {
	google: {
		name: 'Google',
		url: 'https://www.google.com/accounts/o8/id'
	},
	yahoo: {
		name: 'Yahoo',
		url: 'http://me.yahoo.com'
	},
	myopenid : {
		name : 'MyOpenID',
		label : 'Enter your MyOpenID username.',
		url : 'http://{username}.myopenid.com/'
	},
	openid : {
		name : 'OpenID',
		label : 'Enter your OpenID.',
		url : null
	},
	livejournal : {
		name : 'LiveJournal',
		label : 'Enter your Livejournal username.',
		url : 'http://{username}.livejournal.com/'
	},
	wordpress : {
		name : 'Wordpress',
		label : 'Enter your Wordpress.com username.',
		url : 'http://{username}.wordpress.com/'
	},
	blogger : {
		name : 'Blogger',
		label : 'Your Blogger account',
		url : 'http://{username}.blogspot.com/'
	},
	verisign : {
		name : 'Verisign',
		label : 'Your Verisign username',
		url : 'http://{username}.pip.verisignlabs.com/'
	},
	claimid : {
		name : 'ClaimID',
		label : 'Your ClaimID username',
		url : 'http://claimid.com/{username}'
	},
	clickpass : {
		name : 'ClickPass',
		label : 'Enter your ClickPass username',
		url : 'http://clickpass.com/public/{username}'
	},
	google_profile : {
		name : 'Google Profile',
		label : 'Enter your Google Profile username',
		url : 'http://www.google.com/profiles/{username}'
	}	
}

function prefill( provider )
{
	if( oid_providers[provider] ) {
		var url = oid_providers[provider].url;
		var username = '';
		if( oid_providers[provider].label ) {
			username = prompt(oid_providers[provider].label, '');
			if( !username ) {
				return;
			}
		}
		url = url.replace('{username}', username );
		$('#provider').val( url );
	}
}

</script>

<fieldset class="general">
	<legend>OpenID Login</legend>
	<button onclick="prefill('google');" title="Login with your google account"><img src="/img/openid/googleW.png" width="60px"/></button>
	<button onclick="prefill('yahoo');" title="Login with your yahoo account"><img src="/img/openid/yahooW.png" width="60px"/></button>
	<button onclick="prefill('wordpress');" title="Login with your wordpress account"><img src="/img/openid/wordpress.png" width="16px"/></button>
	<button onclick="prefill('blogger');" title="Login with your flickr account"><img src="/img/openid/blogger.png" width="16px"/></button>
	<button onclick="prefill('livejournal');" title="Login with your livejournal account"><img src="/img/openid/livejournal.png" width="16px"/></button>
	<form action="/profile/login" method="post">
		<table style=>
			<tr>
				<td>OpenID URL</td>
				<td><input id="provider" name="provider" value="" size="50"/></td>
				<td><input type="submit" name="login" value="Log In" /></td>
			</tr>
		</table>
	</form>
	<small><strong>What is this?</strong> OpenID is a way of identifying yourself with
	one login. Web sites, such as this one, allow you to use your credentials
	from other sites to login. This way you don't have to remember a million user names
	and passwords and have them spread all over the internet. You can get more info by reading 
	<a target="_blank" href="http://openidexplained.com/">openid explained</a> and see a short 
	list <a target="_blank" href="http://openid.net/get-an-openid/">providers</a> that you may
	already be signed up with. Any OpenID provider that you trust and have an account with should
	work here.</small>
</fieldset>
-->
