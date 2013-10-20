<? $this->load->view('common/header.php'); ?>
  <div id="content_wrapper">
    <? // Start vertical side menu ?>
    <?php $this->load->view('/layouts/vertical_nav'); ?>
    
    <? // Start of the sidebar on the right for home page ?>
    <div id="sidebar_home">

	    <div class="section_heading">Event Highlights</div>
	    <? foreach ($movies as $it) { ?>
		    <div class="sidebar_item">
		        <h1 class="film">Film</h1><!--this h1 assigns the type of item, its label and icon - is red and uppercase- in the pdf's you have the icons (with the labels): twitter (follow me),book (reading, book, review), fireworks (celebration), film (film, review), microphone(speech, spoken word, concert) musical note (music), dancer (dance)  --> 
		        <a href='/films/<?=$it->id;?>'><h2><?= $it->title; ?></h2></a> <em><?= $it->rating; ?></em><!--this h2 is always the author or date if it's an event - is grey and uppercase-->
		        <h3><?= date('D M j, g:i A', strtotime($it->start_time)); ?></h3><!--h3 is always a red italic title-->
		    </div>
	    <? } ?>

	    <? foreach ($events as $it) { ?>

	      <div class="sidebar_item <? if ($it === end($events)) echo 'last' ?>"><!---put a class of last on the last item in a section so the red bottom border doesn't appear--> 
	        <h1 class="<?=$it->category;?>"><?=ucwords($it->category);?></h1>
	        <a href="/events/<?=$it->id;?>"><h2><?=$it->title;?></h2></a>
	        <h3><?= date('D M j, g:i A', strtotime($it->start_time)); ?></h3><!--h3 is always a red italic title-->
	      </div>
	    <? } ?>

	    <div class="section_heading">@Bookshelf News</div>
			<div class="sidebar_item">
				<h1 class="twitter">Follow Me</h1>
				<? $this->load->view('/widgets/tweets', array('tweets'=>$tweets)); ?>
			</div>
		</div>
	    
	    <div id="main_content">
	      <div class="feature_heading featured"> </div>
	      <?php $this->load->view('layouts/liquid_slider', array('list'=>$lists['_section'])); ?>

	      <? if (isset($lists['serendipity'])) { 
	        $this->load->view('widgets/vertical_two_column', array('list'=>$lists['serendipity']));
	      ?>

	      <? } // serendipity block ?>

  </div>
</div>

<? $this->load->view('/common/footer'); ?>