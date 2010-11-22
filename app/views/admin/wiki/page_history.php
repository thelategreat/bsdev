<h3>Revisions for: <?=$page->title?></h3>

<ul>
<?php
foreach( $revisions as $rev ) { ?>

 <li><?=$rev->created_on?> by <?= $rev->user ?></li>

<?php
} ?>
</ul>
