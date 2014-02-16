<!-- javascript -->

<link rel="stylesheet" href="/css/admin_style.css" type="text/css"  media="screen" />
<script type="text/javascript">

function selectUrl( url ) {
  top.tinyMCE.activeEditor.windowManager.getParams().oninsert(url); // Insert the selected URL
  window.parent.tinymce.activeEditor.windowManager.close(window);  // Close the window
}
</script>

<div id='main' class='nosidebar'>
<div id=content>
    <table class='media_table'>
    <tr>
      <th></th>
      <th>title/link</th>
      <th>type</th>
    </tr>
    <?php $count = 0; foreach( $items as $item ) {
      ?>
      <tr <?= ($count++ % 2 ) ? "class='odd'" : '' ?> data-link="/media/<?=$item->uuid;?>">
        <td align="center" style='vertical-align:bottom'>
          <?php
            switch( $item->type ) {
              case 'link':
                if( isset($item->thumbnail) && strlen($item->thumbnail)) {
                  echo '<img src="' . $item->thumbnail . '" width="70" />';
                } else {
                  echo '<img src="/media/logos/youtube.jpg" width="70" />';
                }
                break;
              default:
                echo "<a href=\"#\" onclick=\"selectUrl('".site_url('/media/'.$item->uuid)."');\" title=\"click to insert\">";
                echo '<img src="/media/'. $item->uuid . '" width="70" />';
                echo "</a>";
          }
          ?>
          <p/>
        </td>
        <td><?= $item->caption ?><br/><span style='font-size:0.8em'><?= $item->fname ?></span></td>
        <td><?= $item->type ?></td>
      </tr>
    <?php }?>
    </table>
</div>
</div>
</body>