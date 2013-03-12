<script type='text/javascript' src='/js/views/calendar_events.js'></script>
<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g100">
				<div id='cal_week' style='height:auto'> 
					<div id="details">
						<div class='title'></div>
						<img class='poster' src=''></img><br>
						Directed by <span class='director'></span><br/>
						<span class='country'></span> <span class='year'></span><br>
						<span class='running_time'></span> minutes<br>
						<span class='rating'></span><br>
						<div class='description'></div>
					</div>
					
					<div class='title'>Now Playing at the Bookshelf Cinema</div>					

					<div class='divider today ym-clearfix'>
						<div class='date'><span>Today</span> <?= date('l j', strtotime($cal[0][0]['date']));?></div>
						<?php foreach ($cal[0][0]['events'] as $event) { ?>
							<article class='event' event='<?=$event['id'];?>'>
								<div class='time'><?=$event['start'];?></div>
								<div class='image'><img src='/i/size/o/<?=$event['media'];?>/w/140' /></div>
								<div class='title'><?=$event['title'];?></div>
							</article>
						<?php }?>
					</div>
					<div class='divider next-two ym-clearfix'>
						<div class='day-50 bord'>
						<div class='date'><span><?= date('l', strtotime($cal[0][1]['date']));?></span> <?= date('j', strtotime($cal[0][1]['date']));?></div>
						<?php foreach ($cal[0][1]['events'] as $event) { ?>
							<article class='event' event='<?=$event['id'];?>'>
								<div class='time'><?=$event['start'];?></div>
								<div class='image'><img src='/i/size/o/<?=$event['media'];?>/w/100' /></div>
								<div class='title'><?=$event['title'];?></div>
							</article>
						<?php }?>
						</div>
						<div class='day-50'>
						<div class='date'><span><?= date('l', strtotime($cal[0][2]['date']));?></span> <?= date('j', strtotime($cal[0][2]['date']));?></div>
						<?php foreach ($cal[0][2]['events'] as $event) { ?>
							<article class='event' event='<?=$event['id'];?>'>
								<div class='time'><?=$event['start'];?></div>
								<div class='image'><img src='/i/size/o/<?=$event['media'];?>/w/100' /></div>
								<div class='title'><?=$event['title'];?></div>
							</article>
						<?php }?>
						</div>
					</div>
					<div class='divider last ym-clearfix'>
						<div class='day-50 bord ym-clearfix'>
							<div class='day-33 bord'>
								<div class='container'>
									<div class='date'><?= date('l j', strtotime($cal[0][3]['date']));?></div>
									<?php foreach ($cal[0][3]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>
								</div>
							</div>
							<div class='day-33 bord'>
								<div class='container'>
									<div class='date'><?= date('l j', strtotime($cal[0][4]['date']));?></div>
									<?php foreach ($cal[0][4]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>
								</div>
							</div>
							<div class='day-33'>
								<div class='container'>
									<div class='date'><?= date('l j', strtotime($cal[0][5]['date']));?></div>
									<?php foreach ($cal[0][5]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>
								</div>
							</div>
						</div>
						<div class='day-50 ym-clearfix'>
							<div class='day-33'>
								<div class='container'>
									<div class='date'><?= date('l j', strtotime($cal[0][6]['date']));?></div>
									<?php foreach ($cal[0][6]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>
								</div>
							</div>
							<div class='day-33'></div>
							<div class='day-33'>
								<?= $view_menu ?>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>
