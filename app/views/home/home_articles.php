<h1><?=$view_title?></h1>
<hr/>
<?php foreach( $articles->result() as $article ) { ?>
	<div class="article">
		<h3><?=$article->title?></h3>
		<span class="date"><?=$article->updated_on?></span>
		<p><?= strlen(trim($article->excerpt)) ? $article->excerpt : implode(' ', array_slice(explode( ' ', $article->body),0,100)) ?>
		</p>
		<a href="/article/view/<?=$article->id?>">...read more ➤</a>
	</div>
<?php } ?>
