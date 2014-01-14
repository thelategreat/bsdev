<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_calendar.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/slider_subpage.css');?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo base_url('/css/bookshelf_subpage.css');?>" />

<div id="main_content" class="subpage">
    <div id='featured_column'>
        <?=$name?><br>

        <?foreach($items as $item){?>
            <?=$item->html?>

        <?}?>

    </div>
</div>