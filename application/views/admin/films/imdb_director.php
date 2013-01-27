<h4>Director: <?=$data->name?></h4>

<?=$bio ?  str_replace("\n", "<p/>",markdown($bio->biotext)) : "no bio"?>