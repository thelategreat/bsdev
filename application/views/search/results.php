<? $this->load->view('common/header.php'); ?>
<body>

<div class="ym-wrapper">
	<div class="ym-wbox">
		<header>
			<h1>The Bookshelf</h1>
		</header>

		<? $this->load->view('common/nav', $nav); ?>

<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g100">
				<div id='results' style='height:auto' class='tooltip' title='Search Results'>
					<? foreach ($results as $result) { ?>
						<ul id='search_results'>
							<li>
								<?  $this->load->view('search/item_'.$result->type, array('result'=>$result)) ?>
							</li>
						</ul>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<? $this->load->view('/common/footer'); ?>
</body>
</html>
