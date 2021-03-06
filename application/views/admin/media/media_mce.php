<!-- javascript -->
<script type="text/javascript">

function selectUrl( url ) {
  if( url == '' ) {
    return false;
  }

  field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
  field.value = url;
  if (field.onchange != null) field.onchange();
  window.top.close();
  window.top.opener.browserWin.focus();
}

</script>
<link rel="stylesheet" href="/css/admin_style.css" type="text/css"  media="screen" />
<div id="container">

    <h3>Insert media</h3>
   <div id="content">
    <p style="clear:both;"/>
    <table class='media_table'>
    <tr>
      <th></th>
      <th>title/link</th>
      <th>type</th>
    </tr>
    <?php $count = 0; foreach( $items as $item ) {
      if( $item['type'] == 'link') continue;
      ?>
      <tr <?= ($count++ % 2 ) ? "class='odd'" : '' ?>>
        <td align="center">
          <?php
            switch( $item['type'] ) {
              case 'link':
                if( isset($item['thumbnail']) && strlen($item['thumbnail'])) {
                  echo '<img src="' . $item['thumbnail'] . '" width="70" />';
                } else {
                  echo '<img src="/media/logos/youtube.jpg" width="70" />';
                }
                break;
              default:
                echo "<a href=\"#\" onclick=\"selectUrl('".site_url('/media/'.$item['uuid'])."');\" title=\"click to insert\">";
                echo '<img src="/media/'. $item['uuid'] . '" width="70" />';
                echo "</a>";
          }
          ?>
          <p/>
          <span class="field_tip"><?= $item['uuid'] ?></span>
        </td>
        <td><?= $item['caption'] ?><br/><em><?= $item['fname'] ?></em></td>
        <td><?= $item['type'] ?></td>
      </tr>
    <?php }?>
    </table>

    <div class="pager">
      <table>
        <tr>
          <td><?=$prev_page?></td>
          <td align="right"><?=$next_page?></td>
        </tr>
      </table>
    </div>
  </div>
   <div id="footer">
   </div>
</div>
