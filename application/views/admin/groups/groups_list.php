
<div class=container>
	<header>Sections</header>

<a href="/admin/groups/add">
	<button>
		<i class="icon-plus icon"></i> Add Section 
	</button>
</a>

<table>
<tr>
  <th>Section Name</th>
  <th>Active</th>
  <th>Order</th>
  <th>Delete</th>
</tr>
<?php emit_tree_rows( '/admin/groups', $tree );?>
</table>
</div>
