<script type="text/javascript">

function select_role(  )
{
	$('#matrix').html( '<div style="text-align: center;"><p>loading...</p><img src="/img/ajax-loader.gif" /></div>' );
	$.post('/admin/perms/get_matrix', { role: $('#role-sel').val()},
		function(data) {
			//$('#matrix').hide();
			$('#matrix').html( data );
			//$('#matrix').show('slow');
		}
	);
}

function add_route()
{
	var name = $('#route-name').val();
	var desc = $('#route-desc').val()
	if( name.trim().length == 0 || desc.trim().length == 0 ) {
		alert('You must provide a name and a description');
		return false;
	}
	
	$.post('/admin/perms/add_route', { route: name, desc: desc },
		function(data) {
			$('#route-name').val('');
			$('#route-desc').val('');
			if( !data.ok ) {
				alert(data.message);
			}
			select_role();
		}, 'json'
	);
}

function toggle_perm( id, obj )
{
	var tmp = id.split(":")
	$.post('/admin/perms/toggle_perm', {role_id: tmp[0], route_id: tmp[1], allow: $(obj).attr('checked') ? 1 : 0 },
		function(data) {			
			select_role();
		}
	);
}

function rm_route( id )
{
	if( confirm('Really remove this route? (' + id + ')')) {
		$.post('/admin/perms/rm_route', {route_id: id});
		select_role();
	}
}

$(document).ready( function() {
	select_role();
});
</script>

<h3>Permissions</h3>

<label for="role">Role</label>
<select name="role" id="role-sel" onchange="select_role();">
	<?php foreach( $roles->result() as $role ) { ?>
		<option value="<?=$role->id?>"><?=$role->role?></option>
	<?php } ?>
</select>

<div id="matrix">
</div>

<hr/>
<label for="route-name">Route:</label>
<input name="route-name" id="route-name" />
<lable for="route-desc">Description:</label>
<input name="route-desc" id="route-desc" size="40"/>
<button onclick="add_route();">Add</button>