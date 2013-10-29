<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_subpage.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_subpage.css');?>" />
<style type='text/css'>
    #film_data {
        width: 95%;
    }
    #film_data td,
    #film_data th { 
        background-color: #e4e4e4;
    }
</style>


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
    <div class="feature_heading film_red">
      <h1>FILM</h1>
    </div>
      
    <div id="essay_column">
    <h2>
        <?=$item->title;?> 
    </h2>
    <table id='film_data'>
        <tr>
            <th width="40%">Director</th> <td width="60%"> <?= $item->director; ?> </td>
        </tr>
        <tr>
            <th> Country </th> <td> <?= $item->country; ?> </td>
        </tr>
        <tr>
            <th> Year </th> <td> <?= $item->year; ?> </td>
        </tr>
        <tr>
            <th> Rating </th> <td> <?= $item->rating; ?> </td>
        </tr>
        <tr>
            <th> Running Time </th> <td> <?= $item->running_time; ?> </td>
        </tr>
        <tr>
            <th> IMDB Link </th> <td> <a href="<?= $item->imdb_link; ?>"><?= $item->imdb_link; ?></a> </td>
        </tr>
    </table>

  <h2>Peter's Review</h2>
  <?= $item->description; ?>

</div>
</div>
<div id="essayDetail_column">
    <img src="<? echo imageLinkHelper($item, 190, 300); ?>" alt="Les Mis" width="190" height="300">
    <div id="cinema_showtimes">
        <h1>
            Showtimes
        </h1>
        <h3 class='discrete'>
            <? foreach ($item->showtimes as $it) { ?>
              <div><? echo date('M d | g:i', strtotime($it->start_time)) . ' - ' . date('g:iA', strtotime($it->end_time)); ?></div>
            <? }
            if (count($item->showtimes == 0)) echo 'No upcoming shows';
             ?>
        <? /* </h3><a class="readMore" href="#">All Showtimes &gt;&gt;</a> */ ?>
    </div>
    <aside id="associated_data">
        <section>
            <div class="related"> Related </div>
                <? if (isset($item->associated)) {
                    foreach ($item->associated as $key=>$it) {
                        $displayed_items = 0;
                        if($it) foreach ($it as $assoc) {
                            $displayed_items++;
                            ?>
                            <h3 class="icon-heading icon-<?=$key;?>"><?=ucfirst($assoc->type);?></h3>
                            <h4><? switch($assoc->type) {
                                case 'article':
                                    echo "<a href='/article/view/{$assoc->id}'>{$assoc->title}</a>";
                                    break;
                                case 'product':
                                    echo "<a href='/product/view/{$assoc->id}'>{$assoc->title}</a>";
                                    break;
                                case 'event':
                                    echo "<a href='/events/view/{$assoc->id}'>{$assoc->title}</a>";
                                    break;
                                case 'film':
                                    echo "<a href='/films/view/{$assoc->id}'>{$assoc->title}</a>";
                                    break;
                                } ?>
                            </h4>
                            <?
                        }
                    }

                    if ($displayed_items == 0) {
                        echo '<h3 class="discrete">No related items</h3>';
                    }
                }
                ?>
        </section>

        <? /*
        <section>

            <div class="tags">
                TAGS
            </div>
            <p>
                Vampire Literature
            </p>
            <p>
                Best Young Adulat fiction of 2011
            </p>
        </section>
        */ ?>
    </aside><!--end second column-->
</div><!--end main_content dive-->
</div>

 <div class=clear></div>