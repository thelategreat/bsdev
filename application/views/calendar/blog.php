<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_subpage.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_subpage.css');?>" />
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
        <div id="featured_column">
          <div class="feature_heading star_red">
            <h1>Featured</h1>
          </div>
          <!---add to the class line: even, then fill this column with only the even numbered items in the list array-->
          <? foreach ($articles as $it) { ?>
          <div class="columnBlock">
            <!---the is an item in the list always gien a class of columnBlock-->
            <? if ($it->films) { 
              foreach ($it->films as $film) { ?>
  
              <div class="imageFloat">
                <img src="<? echo imageLinkHelper($film, $width=148, $height=186); ?>" alt="<?= $film->title ?>">
                <h1 class="film"><?=$film->title;?></h1>
                <h2><? echo date('M j, Y', strtotime($it->publish_on));?></h2>
                <h3><?=$it->author;?></h3>
              </div>

              <? 
              }
            }
            ?> 
            <!--end of the image and caption float-->
            <h2><?=$it->title;?></h2>
            <?=$it->body;?> 
            <div class="clear"></div>
          </div>
          <? if ($it->films) { ?>
          <div id="cinema_showtimes">
            <h1>Showtimes</h1>
            <h3><? foreach ($it->films as $film) { 
              if ($film->showtimes) {
                foreach ($film->showtimes as $showtime) {
                  echo date('M j | H:i', strtotime($showtime->start_time)) . ' - ' . date('H:i A', strtotime($showtime->end_time)) . '<br>';
                }
              }
            } ?></h3>
          </div>
          <? } // showtimes ?>
          <div id="social_media">
            <div class="comments"> <a href="#" style="float:right; margin-left:5px;margin-top:5px;"><img src="images/icon_tweet_wh.png" alt="Twitter" width="21" height="17" border="0" /></a><a href="#" style="float:right; margin-top:5px;"><img src="images/icon_facebook_wh.png" alt="Fcaebook" width="17" height="17" border="0" /></a>YOUR COMMENTS </div>
            <p> Please log in to comment. 
              The following comments....</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="tags">TAGS</div>
          </div>


          <? } ?>
        </div>
        <!--start second column-->
        <div id="highlight_column">
          <div class="cinema_highlights"></div>
          <!---add to the class line: odd, then fill this column with only the odd numbered items in the list array-->
          <div class="columnBlock">
            <!--start of the first odd item-->
            <div class="imageFloat"> <img src="images/videoPlaceholder.png" alt="video placeholder">
              <h1 class="film">Title of Video</h1>
              <h2>Author of video</h2>
            </div>
            <p>Who knows why Dustin Hoffman waited so long to direct his first feature, but Quartet proves worth the wait. Within the comfortable confines of Beecham House, a retirement home that caters exclusively to former singers and musicians, we meet Reggie (Tom Courtenay), Wilf (Billy Connolly), and Cissy (Patricia Collins), three-fourths of a former quartet of operatic singers. But the tranquility of Beecham House is broken with the arrival of Jean (Maggie Smith), the remaining member of the quartet and Reggie’s ex-wife, on the eve of their annual concert to celebrate Verdi’s birthday. Combining fine music, quickwitted dialogue, and highly skilled veteran performers, Quartet is a fabulous folly of the operatic ravings of four fine diva(o)s. </p>
            <hr />
            <div id="cinema_showtimes">
              <h1>Showtimes</h1>
              <h3>APR 3 | 6:45 - 9:00PM<br />
                APR 4 | 6:45 - 9:00PM</h3>
            </div>
          </div>
          <!--end second column-->
        </div>
        <!--end main_content dive-->
      </div>
      <!--end 'content_wrapper' div-->
    </div>
    <!--end 'container' div-->
  </div>


 <div class=clear></div>