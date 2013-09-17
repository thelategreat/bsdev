<div id="section_content">
	<h2><?= $title ?></h2>
	<?= $body ?>

<? if (isset($lists['top'])) {
	new dBug($lists['top']);
} ?>

<? if (isset($lists['middle'])) {
	new dBug($lists['middle']);
} ?>

<? if (isset($lists['sidebar'])) {
	new dBug($lists['sidebar']);
} ?>

<? if (isset($lists['bottom'])) {
	new dBug($lists['bottom']);
} ?>
</div>