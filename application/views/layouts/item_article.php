<div class='columnBlock'>
    <h1 class='book'><?=$item->section;?></h1>
                                                                    
    <div class='imageFloat'>
            <img src='<? echo imageLinkHelper($item, $width=170, $height=false); ?>' width=170/>
            <h2><?=date('j M Y',strtotime($item->publish_on))?></h2>
            <h3 class='byline'><?=$item->author?></h3>
    </div>
    
    <h2><?=$item->title?></h2>
    <div id='article_body'><?=$item->body;?></div>

    <div class='article-tags'>
        <? if (isset($tags) && count($tags) > 0) { ?>
            <div class='title'>FILE UNDER</div>
            <div class='tags'>
                <? foreach ($tags as $tag) { ?>
                        <a href='<? echo base_url('/search/tags/'.$tag);?>'><?=$tag;?></a> 
                <? } ?>
            </div>
        <? } ?>
    </div>
</div>