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
<div style="float: right">
</div>
<h3><a class="small" href="/admin/pages/add/"><img src="/img/admin/page_white_add.png" title="Add Page"/></a> Pages</h3>
<table>
<tr>
  <th>title</th>
  <th>order</th>
  <th>active</th>
  <th></th>
</tr>
<?php emit_tree_rows( '/admin/pages', $titles, 5, 'title' );?>
</table>
