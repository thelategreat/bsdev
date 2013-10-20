<script typ"text/javascript">
function activate( id )
{
	if( confirm("Activate this page? " + id )) {
		alert('TBD');		
	}
}

function deactivate( id )
{
	if( confirm("Deactivate this page? " + id )) {
		alert('TBD');
	}
}

</script>
<div class=container>
	<header>Lists</header>

	<nav>
		<a href="/admin/pages/add">
			<button id='btn_add'>
				<i class="icon-plus icon"></i> Add Page
			</button>
		</a>
	</nav>
	<br>

<table>
<tr>
  <th>Title</th>
  <th>Order</th>
  <th>Active</th>
  <th>Delete</th>
</tr>
<?php emit_tree_rows( '/admin/pages', $titles, 5, 'title' );?>
</table>
</div>