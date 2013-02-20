<?
 	 // this is a cheat for the pg_data which I haven't migrated completely yet
	if( !isset($section)) {
		$section = "";
	}
?>
<? $this->load->view('common/header'); ?>
<body class='<? if (isset($css_name)) echo $css_name;?>'>
<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>The Bookshelf</h1>
		</header>

<? $this->load->view('common/nav', $nav); ?>

<?= $content; ?>
<? $this->load->view('common/footer'); ?>