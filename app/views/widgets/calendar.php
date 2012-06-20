    <table style="width: 100%; table-layout: fixed;">
    <tr>
      <th>Su</th>
      <th>Mo</th>
      <th>Tu</th>
      <th>We</th>
      <th>Th</th>
      <th>Fr</th>
      <th>Sa</th>
    </tr>
<?php 
      for( $i = 0; $i < 5; $i++ ) {
        echo '<tr>'; 
        for( $j = 0; $j < 7; $j++ ) { 
          $style = "border: 1px solid #666; height: 30px; text-align: center; border-radius: 3px;";
          if( ($i == 0 && $cal[$i][$j]['num'] > 6) || ($i == 4 && $cal[$i][$j]['num'] < 22) )
            $style .= ' background-color: #ddd;';

?>
          <td style="<?=$style?>"> 
            <a href="#" tooltip="Events for day <?=$cal[$i][$j]['num']?>"><?=$cal[$i][$j]['num']?></a> 
          </td>
<?php
      }
      echo '</tr>';
    }
?>
    <tr>
      <td>&lt;</td>
      <td colspan="5" style="text-align: center"></td>
      <td style="text-align: right">&gt;</td>
    </tr>
    </table>

