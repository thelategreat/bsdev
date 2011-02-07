<!--
<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>
-->

<style type="text/css">
#q-container {
	background-color: #333;
}
#s-container {
	clear: both;
	height: 36px;
	margin: 2px 0 1em;
	width: 100%;
}
#s-query {
	font-size: 12px;
	height: 18px;
	overflow-x: hidden;
	padding-left: 5px;
	padding-top: 2px;
	position: relative;
	width: 95%;
}
.issueContainer {
	border-bottom: 1px solid #999;
	padding: 0;
	margin: 0;
}
div.issueContainer table {
	padding: 0;
  margin: 0;
}
div.issueContainer table.properties {
	table-layout: fixed;
	width: 80%;
  margin-left: 20px
}

div.issueContainer table.properties td.attr {
	border-left: 1px solid #fff;
	border-right: 1px solid #d4d5d4;
	overflow: hidden;
	color: #444;
	text-align: center;
	font-size: 80%;
}
.new {
	background-color: #9ff;
	color: #f00;
}
.open {
	background-color: #9f9;
	color: #0f0;	
}
.fixed {
	background-color: #FC6;
}
.wontfix {
	background-color: #FF9FeF;
}
.closed {
	text-decoration: line-through;
}
.big {
	font-size: 115%;
}
</style>

<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/bugs/add"><img src="/img/admin/bug_add.png" title="Add an Issue"/></a> Issues</h3>

<?= $tabs ?>

<?php foreach( $bugs->result() as $bug ) { ?>
<div class='issueContainer'>
	<table>
		<tr>
			<td><a class='big' href="/admin/bugs/edit/<?= $bug->id ?>"><?= $bug->summary ?></td>
		</tr>
		<tr>
			<td>
				<table class="properties">
					<tr>
					  <td class='attr <?= $bug->status ?>'><?= $bug->status ?></td>
					  <td class='attr'><?= $bug->type ?></td>
					  <td class='attr'>by: <?= $bug->submitted_by ?></td>
					  <td class='attr'><?= date('Y-m-d',strtotime($bug->created_on)) ?></td>
					  <td class='attr'><?= $bug->assigned_to ?></td>
					  <td class='attr'><?= $bug->comment_count ? $bug->comment_count : '' ?> <img src="/img/admin/comment.png" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<?php } ?>

<?= $pager ?>
