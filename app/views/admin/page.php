<h2>Admin - Page</h2>

<table>
<? foreach( $page_tree as $row ) { ?>
	<tr>
		<td><?=$row['title']?></td>
	</tr>
<?  } ?>
</table>