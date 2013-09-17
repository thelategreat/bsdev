<?
 	 // this is a cheat for the pg_data which I haven't migrated completely yet
	if( !isset($section)) {
		$section = "";
	}
?>
<? $this->load->view('common/header.php'); ?>
<div id="content_wrapper <? if (isset($css_name)) echo $css_name;?>">

<? // Start vertical side menu ?>
<?php $this->load->view('/layouts/vertical_nav'); ?>
    
<?= $content; ?>

<? $this->load->view('/common/footer'); ?>