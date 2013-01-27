<script type="text/javascript">
function vote( )
{
  var id = $("input[@name='poll']:checked").attr("value");
  if( !id ) {
    alert("Please choose an option");
    return false;
  }
  
  $.post("/admin/polls/vote", { id: id },
		function( data ) {
      if( data.ok ) {
        //alert(data.msg);
        window.location.reload(true);
			} else {
				alert(data.msg);
			}
    }, 'json');

  return false;
}
</script>

<h2>Poll</h2>
<div id="poll-container">
<h3><?=$poll->question?></h3>
<form id="poll" action="" method="post" >
  <?php for($i = 1; $i <= count($poll->answers); $i++ ) { ?>
    <input type="radio" name="poll" value="<?=$poll->answers[$i-1]->id?>" id="opt$i"><label for="opt$i"><?=$poll->answers[$i-1]->answer?></label><br/>
  <?php } ?>
  <input type="submit" value="Vote" onclick="return vote();"/>
</form>
</div>

<?php
$total = 0;
foreach($poll->answers as $ans ) {
  $total += $ans->count;
} 
?>

<hr/>

<h4>Results</h4>
<div id="poll-results">
<dl class="graph">
<?php foreach($poll->answers as $ans ) {
  $pct = round(($ans->count / ($total+0.00001)) * 100);
  ?>
  <dt class="bar-title"><?=$ans->answer?></dt>
  <dd class="bar-container">
  <div style="width:<?=$pct?>%;background-color:rgb(0,102,204);display:block"></div>
  &nbsp;<strong><?=$pct?>%</strong>
  <dd>
<?php } ?>
</dl>
</div>
<!--
<p><b><?=$poll->question?></b></p>
<ol>
<?php foreach( $poll->answers as $ans ) { ?>
  <li>
    <?= $ans->answer ?> (<?=$ans->count?>) <a href="javascript:vote(<?=$ans->id?>)">vote</a>
  </li>
<?php } ?>
</ol>
-->
