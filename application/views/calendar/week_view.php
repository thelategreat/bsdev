<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/js/bxslider/jquery.bxslider.css');?>" />

<script src="<?echo base_url('js/bxslider/jquery.bxslider.js');?>"></script>
<script src="<?echo base_url('js/bxslider/scripts.js');?>"></script>


<div id="sidebar_cinema">
	<!--sidebar_home tells the sidebar to appear on the right side of the page. Can't really just go with sidebar right and sidebar left, as this one coumn appears also in the midle left and rigth on idfferent pages with some slightly idfferent properties, so I'll proceed with a different name based on page rather than location on the page-->
	<div class="section_heading">
		<h2>
			Event Highlights
		</h2>
	</div><!--the section heading provides the label in the grey bar and is just plain text. In the excell sheet you have Vert-1Column-Coming Soon, Showtimes, Related, Tags, Twitter that can all be controlled by the same styles-->
	<div class="sidebar_item">
		<!--this is the div container for an individual item in the list-->
		<h1 class="book">
			Reading
		</h1><!--this h1 assigns the type of item, it's label and icon - is red and uppercase- in the pdf's you have the icons (with the labels): twitter (follow me),book (reading, book, review), fireworks (celebration), film (film, review), microphone(speech, spoken word, concert) musical note (music), dancer (dance)  -->
		<h2>
			David Suzuki
		</h2><!--this h2 is always the author or date if it's an event - is grey and uppercase-->
		<h3>
			The Screwed Balance
		</h3><!--h3 is always a red italic title-->
		<p>
			Every second Thursday A live music night featuring female singer/songwriters. MUSIC FLASHBACK FRIDAYS
		</p>
	</div>
	<div class="sidebar_item last">
		<!---put a class of last on the last item in a section so the red bottom border doesn't appear-->
		<h1 class="fireworks">
			Celebration
		</h1>
		<h2>
			The Bookshelf's 40th Aniversary Bash
		</h2>
		<p>
			8 June 2013 The Bookshelf
		</p>
	</div>
	<div class="section_heading">
		<h2>
			@Bookshelf News
		</h2>
	</div>
	<div class="sidebar_item">
		<h1 class="twitter">
			Follow Me
		</h1>
		<h2>
			Fri, 26 Apr 2013 00:53:06
		</h2>
		<p>
			@LynnBeSocial Thanks for being awesome Lynn!
		</p>
	</div>
</div>




<div id="main_content" class="subpage">
        <div class="feature_heading film_red ">
          <h1>Playing This Month</h1>
        </div>
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
        <ul class="bxslider">
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>Movie Title</h2>
            <h1>Some Name</h1>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
          <li><img src="slider_images/film_placeholder_214_317.jpg" />
            <h2>author</h2>
          </li>
        </ul>
        <!---this is the red header bar-->
        <div class="feature_heading calendar-month">
          <h1>May</h1>
        </div>
        <!---calendar table-->
        <table cellpadding="0" cellspacing="0" border="0" class="calendar">
          <tr>
            <td><h1>MON APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>TUE APR <span class="calendar-date">29</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>WED APR <span class="calendar-date">30</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>THU APR <span class="calendar-date">31</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>FRI MAY <span class="calendar-date">1</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SAT MAY <span class="calendar-date">2</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN MAY <span class="calendar-date">3 </span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
          </tr>
          <tr>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td><h1>SUN APR <span class="calendar-date">28</span></h1>
              <h3>4:00</h3>
              <h2>The Company You Keep</h2>
              <h3>7:00</h3>
              <h2>Spring Breakers</h2></td>
            <td>&nbsp;</td>
          </tr>
          <tr bordercolor="0">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <!---end calendar-->
      </div>
    </div>