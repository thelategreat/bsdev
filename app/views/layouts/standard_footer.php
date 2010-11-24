<ul>
  <?php foreach( $pages[0]->children as $page ) { ?>
    <li><a href='/page/view/<?=$page->id?>'/><?=$page->title?></a></li>
  <?php } ?>
</ul>
