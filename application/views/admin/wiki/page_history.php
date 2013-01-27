<div id="wiki-tools">
  <a href="/admin/wiki/<?=$page->title?>" title="View Page"><img src="/img/admin/page_white.png" /></a>
  <a href="/admin/wiki/" title="Wiki Home"><img src="/img/admin/house.png" /></a>
</div>
  
<h3>Revisions for: <?=$page->title?></h3>

<ul>
<?php
foreach( $revisions as $rev ) { ?>

 <li><?=$rev->created_on?> by <?= $rev->user ?></li>

<?php
} ?>
</ul>
