<div id="wiki-tools">
  <a href="/admin/wiki/<?=$page->title?>/edit" title="Edit Page"><img src="/img/admin/page_white_edit.png" /></a>
  <a href="/admin/wiki/<?=$page->title?>/history" title="Page History"><img src="/img/admin/page_white_stack.png" /></a>
  <a href="/admin/wiki/" title="Wiki Home"><img src="/img/admin/house.png" /></a>
</div>
  
<div class="wiki-text">
<h3><?= $page->title ?></h3>
<?= $page->body ?>
</div>