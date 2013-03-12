<script type='text/javascript' src='/js/views/calendar_events.js'></script>
<div id="main" class='ym-clearfix'>
	<div class="ym-wrapper">
		<div class="ym-wbox">
			<div class="ym-g100">
				<div id='cal' style='height:auto'> 
					<h2><span style="padding: 2px; margin: 2px;">
						<a href="<?=$prev_month_url?>" title="last month">
							<img src="/img/cal/arrow_left.png" style="width: 24px; margin-bottom: -5px"/></a>&nbsp;
						<a href="<?=$next_month_url?>" title="next month">
							<img src="/img/cal/arrow_right.png" style="width: 24px; margin-bottom: -5px"/></a>
					</span> | 
					<?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?>
					</h2>
					
					<?= $view_menu ?>


					<div id='cal_month' style='height:auto'>

						<div id="details">
							Directed by <span class='director'></span>
							<br/>
							<span class='country'></span> <span class='year'></span>
							<br/>
							<span class='running_time'></span> minutes
							<div class='rating'></div>
						</div>
	
						<div class='title'>Now Playing at the Bookshelf Cinema</div>					

						<?php 
						$weeknum = 0;
						foreach( $cal as $week ) { ?>
						<div class='divider today ym-clearfix'>
							<?php for ($i=0; $i < 6; $i++) { ?>
								<div class='day-12-5 bord'>
									<div class='container'>
										<div class='date'><span><?= date('l', strtotime($cal[$weeknum][$i]['date']));?></span> <?= date('j', strtotime($cal[$weeknum][$i]['date']));?></div>	

									<?php foreach ($cal[$weeknum][$i]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>

									</div>	
								</div>
							<? } ?>
							<div class='day-12-5'>
								<div class='container'>
									<div class='date'><span><?= date('l', strtotime($cal[$weeknum][6]['date']));?></span> <?= date('j', strtotime($cal[$weeknum][6]['date']));?></div>	

									<?php foreach ($cal[$weeknum][$i]['events'] as $event) { ?>
										<article class='event' event='<?=$event['id'];?>'>
											<div class='time'><?=$event['start'];?></div>
											<div class='title'><?=$event['title'];?></div>
										</article>
									<?php }?>

								</div>	
							</div>
						</div>
						<?php $weeknum++; } ?>
					</div>

				</div>				
			</div>
		</div>
	</div>

</div>

