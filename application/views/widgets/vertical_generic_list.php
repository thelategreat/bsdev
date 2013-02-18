<div id='<?=$name;?>' style='overflow:hidden;height:auto' class='ym-clearfix dash tooltip' title='<?=$name;?>'>
	<? foreach ($list as $item) { ?>
		<div class='list-item list-h' style='float:left;width:200px;height:150px;margin-bottom:10px;'>
			<h6><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h6>
			<div style='text-align:center'>
			<? if (isset($item->media) && isset($item->media[0])) { ?>
				<img src='<?=$item->media[0]['thumbnail'];?>' height='100'/>
			<? } ?>
			</div>
		</div>
	<? } ?>
</div>



<div id='<?=$name;?>' class='tooltip' title="<?=$name;?>"> 
	<? foreach ($list as $item) { ?>
		<div class='list-item list-v' style='height:150px;margin-bottom:10px;'>
			<h6><a href="/article/view/<?=$item->id;?>"><? echo $item->title; ?></a></h6>
			<div style='text-align:center'>
			<? if (isset($item->media) && isset($item->media[0])) { ?>
				<img src='<?=$item->media[0]['thumbnail'];?>' height='100'/>
			<? } ?>
			</div>
		</div>
	<? } ?>				
</div>
