<? // Liquid Slider starts here ?>
<div class="liquid-slider"  id="slider-1">
<? for ($i=0; $i<count($list); $i++) { ?>
	<? // This identifies which slider as there may be more than one slider that needs independant styles ?>
  <div class="wrapper">
    <!--every item in the slider list is wrapped-->
    <span class="title">&#xe004;</span>
    <!--this creates the nav button- the nave row is dynamically created based on the number of panels below-->
    <h2 class="navHeading"><?=$i;?></h2>
    <!--item # from the array - in this case 1.-->
    <img src="<?echo imageLinkHelper($list[$i], 100, 100); ?>" height=100 width=100 alt="Look After Mom" title="Look After Mom" />
    <!--build this link to the image- -->
    <h1 class="reading"><?=$list[$i]->category;?></h1>
    <!--category variable goes in this h1 tag-->
    <h2 class="contributor"><?=$list[$i]->author;?></h2>
    <!--contributor variable goes in this h2 tag-->
    <h3 class="itemTitle"><?=$list[$i]->title;?></h3>
    <!--title of the item variable goes in this h3 tag-->
    <p><?=$list[$i]->excerpt;?></p>
    <p><a href="#" class="readMore">read more >></a></p>
    <div class="clear"></div>
    <!--this is here to clear the image float..hopefully this fixes the issue you had on iPad-->
  </div>
<? } ?>
</div>

<? /*

<!-- Liquid Slider starts here -->
<div class="liquid-slider"  id="slider-1"><!--this identifies which slider as there may be more than one slider that needs independant styles-->
  <div class="wrapper">
    <!--every item in the slider list is wrapped-->
    <span class="title">&#xe004;</span>
    <!--this creates the nav button- the nave row is dynamically created based on the number of panels below-->
    <h2 class="navHeading">1</h2>
    <!--item # from the array - in this case 1.-->
    <img src="slider_images/look_after_mom_lg.jpg" alt="Look After Mom" title="Look After Mom"/>
    <!--build this link to the image- -->
    <h1 class="reading">Book Review</h1>
    <!--category variable goes in this h1 tag-->
    <h2 class="contributor">Reviewer Name</h2>
    <!--contributor variable goes in this h2 tag-->
    <h3 class="itemTitle">Title: A Family Crisis</h3>
    <!--title of the item variable goes in this h3 tag-->
    <p>Kyung Sook Shin's <em>Please Look After Mom</em>, is a compelling story that reaches across cultures,
      this book grips the hearts of mothers, fathers,
      sons, and daughters around the world as it
      chronicles the efforts of a family after their elderly
      mother goes missing. Although it is a story of loss,
      it made me laugh out loud in spots.<a href="#" class="readMore">read more >></a>
      <!--read more link-->
    </p>
    <div class="clear"></div>
    <!--this is here to clear the image float..hopefully this fixes the issue you had on iPad-->
  </div>
  <div class="wrapper"> <span class="title">&#xe004;</span>
    <h2 class="navHeading">Item 2</h2>
    <img src="slider_images/look_after_mom_lg.jpg" alt="Look After Mom"/>
    <h1 class="speech">Public Reading</h1>
    <h2 class="contributor">Reader's Name</h2>
    <!--contributor variable goes in this h2 tag-->
    <h3 class="itemTitle">Title: A Family Crisis Viewed Through Four Pairs of Eyes</h3>
    <!--title of the item variable goes in this h3 tag-->
    <p>Teaser: A compelling story that reaches across cultures,
      this book grips the hearts of mothers, fathers,
      sons, and daughters around the world as it
      chronicles the efforts of a family after their elderly
      mother goes missing. Although it is a story of loss,
      it made me laugh out loud in spots.</p>
    <div class="clear"></div>
    <!--this is here to clear the image float-->
  </div>
  <div class="wrapper"> <span class="title">&#xe004;</span>
    <h2 class="navHeading">Panel 3</h2>
    <img src="slider_images/look_after_mom_lg.jpg" alt="Look After Mom" />
    <h1 class="celebration">Book Launch</h1>
    <h2 class="contributor">Kyung Sook SHin</h2>
    <!--contributor variable goes in this h2 tag-->
    <h3 class="itemTitle">Please Look After Mom</h3>
    <!--title of the item variable goes in this h3 tag-->
    <p>A compelling story that reaches across cultures,
      this book grips the hearts of mothers, fathers,
      sons, and daughters around the world as it
      chronicles the efforts of a family after their elderly
      mother goes missing. Although it is a story of loss,
      it made me laugh out loud in spots.</p>
    <div class="clear"></div>
  </div>
  <div class="wrapper"> <span class="title">&#xe004;</span>
    <h2 class="navHeading">Panel 4</h2>
    <img src="slider_images/look_after_mom_lg.jpg" alt="Look After Mom" />
    <h1 class="celebration">Another Book</h1>
    <h2 class="contributor">Another Author</h2>
    <!--contributor variable goes in this h2 tag-->
    <h3 class="itemTitle">Another Title</h3>
    <!--title of the item variable goes in this h3 tag-->
    <p>More text: A compelling story that reaches across cultures,
      this book grips the hearts of mothers, fathers,
      sons, and daughters around the world as it
      chronicles the efforts of a family after their elderly
      mother goes missing. Although it is a story of loss,
      it made me laugh out loud in spots.</p>
    <div class="clear"></div>
  </div>
</div>
*/ ?>