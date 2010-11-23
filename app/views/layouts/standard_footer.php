<!--
<p class="footer_title"><strong>The Bookshelf</strong></p>
-->
<ul>
  <?php foreach( $pages[0]->children as $page ) { ?>
    <li><a href='/page/<?=$page->title?>'/><?=$page->title?></a></li>
  <?php } ?>
</ul>
<!--
<p class="copyright">Copyright &copy; <?= date('Y') ?> <span>All Rights Reserved</span></p>
-->
<!--
<a class="credit" href="http://www.barkingdogstudios.com/"> Design: Barking Dog Studios</a>
<a class="credit" href="http://www.talonedge.com/">Powered by: TalonEdge</a>
-->