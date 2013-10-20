<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_subpage.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/js/bxslider/jquery.bxslider.css');?>" />

<script src="<?echo base_url('js/bxslider/jquery.bxslider.js');?>"></script>
<script src="<?echo base_url('js/bxslider/script.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.bxslider').bxSlider({
	  minSlides: 3,
	  maxSlides: 5,
	  slideWidth: 120,
	  slideMargin: 10
	});
  });
</script>


<div id="sidebar_cinema">
	<? if (count($events) > 0) { ?>
	<div class="section_heading">
		<h2>
			Event Highlights
		</h2>
	</div>
	<? foreach ($events as $it) { ?>
      <div class="sidebar_item <? if ($it === end($events)) echo 'last' ?>">
        <h1 class="<?=$it->category;?>"><?=ucwords($it->category);?></h1>
        <a href="/events/<?=$it->id;?>"><h2><?=$it->title;?></h2></a>
        <h3><?= date('D M j, g:i A', strtotime($it->start_time)); ?></h3>
      </div>
    <? } ?>	
   	<? } ?>
	<div class="section_heading">
		<h2>
			@Bookshelf News
		</h2>
	</div>
	<div class="sidebar_item">
		<?= $tweets ?>	
	</div>
</div>

<div id="main_content" class="subpage">
        <div class="feature_heading film_red ">
          <h1>FILM</h1>
        </div>
        <ul class="bxslider">
        	<? foreach ($films_this_month as $it) { ?>
        	<li>
        		<a href="<?= base_url('/films/view/' . $it->id);?>">
              <img src="<? echo imageLinkHelper($it, $width=120, $height=false); ?>" />
        		<h1><?=$it->title;?></h1>
        		<h2></h2>
            </a>
        		<? /* <h2>Brad Pitt, Catherine Zeta Jones, Jonny Depp</h2> */ ?>
        	</li>
        <? } ?>
        </ul>
        <!---this is the red header bar-->
        <div class="feature_heading calendar-month">
          <h1><?=$month_name;?></h1>
        </div>
        <? echo draw_calendar($month,$year,$cal_events); ?>
      </div>
    </div>

 <div class=clear></div>