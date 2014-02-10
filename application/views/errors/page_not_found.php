<? $this->load->view('common/header.php'); ?>
  <div id="content_wrapper">
    <? // Start vertical side menu ?>
    <?php $this->load->view('/layouts/vertical_nav'); ?>
    
	Sorry, the page you requested can't be found.

</div>
<? $this->load->view('/common/footer'); ?>
<? exit; // For some reason this is required to not show a blank page when the 404 is called manually ?>