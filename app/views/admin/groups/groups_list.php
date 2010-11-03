
</script>
<div style="float: right">
</div>
<h3><a class="small" href="/admin/groups/add/"><img src="/img/admin/layout_add.png" title="Add Group"/></a> Groups</h3>
<table>
<tr>
  <th>name</th>
  <th>active</th>
  <th>order</th>
  <th></th>
</tr>
<?php emit_tree_rows( '/admin/groups', $tree );?>
</table>
