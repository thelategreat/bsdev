
<?php if( $venue ) { ?>
<table style="width: 100%">
	<tr>
<td valign="top">
  <h2 class="venue_name"><?=$venue->name?></h2>

	<address class="venue_address">
		<?=$venue->address?>
		<?=$venue->city?>, <?$venue->postal?>
	<address>
	<div id="venue_description">
		<?=$venue->descrip?>
	</div>		

</td>
<td valign="top" align="right">
	<?=$venue->more_info?>
</td><!-- /More Info -->
<tr>
</table>	

<?php } else { ?>
	<p class="error">We could not find any information on that venue (<?=$id?>)</p>
<?php } ?>