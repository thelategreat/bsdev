
<? $this->load->view('common/header.php'); ?>


                
<div id="content_wrapper <? if (isset($css_name)) echo $css_name;?>">


    
<?= $html ?>

<? $this->load->view('/common/footer'); ?>