<div class="feature_heading"></div> <? // The red header bar ?>
<ul class="enclosure">
  <? foreach (($list) as $item) { ?>
    <li class="colz">
      <a href="/article/view/<?=$item->id;?>">
      <div class="imageFloat">
          <img src='<?= imageLinkHelper($item, 100); ?>' height='100'/>
        <h1 class="reading"><? echo $item->title; ?></h1>
        <h2><? if (isset($item->teaser)) {echo $item->teaser;} ?></h2>
      </div>
    </a>
    <p><? if (isset($item->excerpt)) echo $item->excerpt; ?></p>
    </li>
  <? } ?> 
</ul>


<? // Stacked 2 column block ?>
<? /*
  <div class="stackedColumn_2 even">
  <div class="columnBlock">
    <? foreach (evenElements($list) as $item) { ?>
    <a href="/article/view/<?=$item->id;?>">
      <div class="imageFloat">
          <img src='<?= imageLinkHelper($item, 100); ?>' height='100'/>
        <h1 class="reading"><? echo $item->title; ?></h1>
        <h2><? if (isset($item->teaser)) {echo $item->teaser;} ?></h2>
      </div>
    </a>
    <p><? if (isset($item->excerpt)) echo $item->excerpt; ?></p>
  <? } ?>
  </div>
</div>
  <div class="stackedColumn_2 odd">
  <div class="columnBlock">
    <? foreach (oddElements($list) as $item) { ?>
    <a href="/article/view/<?=$item->id;?>">
      <div class="imageFloat">
        <? if (isset($item->media) && isset($item->media[0])) { ?>
          <img src='<?= imageLinkHelper($item, 100); ?>' height='100'/>
        <? } ?>
        <h1 class="reading"><? echo $item->title; ?></h1>
        <h2><? if (isset($item->teaser)) {echo $item->teaser;} ?></h2>
      </div>
    </a>
    <p><? if (isset($item->excerpt)) echo $item->excerpt; ?></p>
  <? } ?>
  </div>
  <!--end of the odd items --> 
</div>
</div>
*/ ?>