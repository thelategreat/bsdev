<nav id="nav">
	<div class="ym-wrapper">
		<div class="ym-hlist">
			<ul>
				<? if ($main == 'home') { ?>
					<li class='active'><a href="/"><strong>Main page</strong></a></li>				
				<? } else { ?>
					<li><a href="/">Main page</a></li>
				<? } ?>

				<? if ($main == 'section') { ?>
					<li class='active'><a href="/"><strong>Section</strong></a></li>
				<? } else { ?>
					<li><a href="/">Section</a></li>
				<? } ?>

				<? if ($main == 'article') { ?>
					<li class='active'><a href="/"><strong>Article</strong></a></li>				
				<? } else { ?>
					<li><a href="/">Article</a></li>
				<? } ?>

			</ul>
			<form class="ym-searchform">
				<input class="ym-searchfield" type="search" placeholder="Search..." />
				<input class="ym-searchbutton" type="submit" value="Search" />
			</form>
		</div>
	</div>
</nav>