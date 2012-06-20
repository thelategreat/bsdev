    <h3><a href="http://twitter.com/#!/bookshelfnews" target="_blank">
        <img src="/img/social/twitter-yellow-arrow.png" style="float: right; height: 42px;"> @Bookshelfnews
        </a>
    </h3>
    <ul class="twitter-feed">
    <?php foreach( $tweets as $tweet ) { ?>
      <li><em><?=$tweet['pubDate']?></em><br/><?=$tweet['title']?></li>
    <?php } ?>
    </ul>

