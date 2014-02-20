<div id="vertical_nav">
	<div>
	<? foreach ($nav as $it) {
		if ($it->orientation == 'vertical') { ?>
			<a class="<? if ($this->uri->segment(1) == $it->route) echo 'active'; ?>" href="<?=base_url('/' . $it->route);?>"><span><?=$it->name;?></span></a>
		<? }
	} ?>
	<a class="<? if (false && isset($active_links) && in_array('bookstore', $active_links)) echo 'active'; ?>" href="<?=base_url('');?>"><span>Bookstore</span></a>
	<a class="<? if (false && isset($active_links) && in_array('cinema', $active_links)) echo 'active'; ?>" href="<?=base_url('cinema');?>"><span>Cinema</span></a>
	<a class="<? if (false &&isset($active_links) && in_array('ebar', $active_links)) echo 'active'; ?>" href="<?=base_url('ebar');?>"><span>Ebar</span></a>
	</div>
</div>