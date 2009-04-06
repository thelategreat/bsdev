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
	<a href="/admin/pages/add/"><img src="/img/page_white_add.png" /> Add Page</a>
</div>
<h3><img src="/img/page_white.png" /> Pages</h3>
<table>
<tr>
  <th>title</th>
  <th>order</th>
  <th>active</th>
  <th></th>
</tr>
<?php emit_title_rows( $titles );?>
</table>
