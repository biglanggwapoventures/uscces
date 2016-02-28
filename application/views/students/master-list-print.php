<!DOCTYPE html>
<html>
	<head>
		<title>Students Master List</title>
	</head>
	<style type="text/css">
		table{
			width:100%;
			border-spacing: 0;
			border-collapse: collapse;
		}
		table > thead > tr > th{
			text-align: left;
		}
		table > tbody > tr > td{
			border: 1px solid black;
			vertical-align: middle;
		}
	</style>
	<body style="font-family: Tahoma!important;font-size:12px!important;">
		<table border="01">
			<thead>
				<tr>
					<th>ID Number</th><th>Lastname</th><th>Firstname</th><th>Middlename</th><th>Course</th><th>CES Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list AS $row):?>
					<tr>
						<td><?= $row['username']?></td>
						<td><?= $row['lastname']?></td>
						<td><?= $row['firstname']?></td>
						<td><?= $row['middlename']?></td>
						<td><?= course($row['course'], TRUE).'-'.$row['year_level']?></td>
						<td><?= intval($row['status']) ? 'Completed' : 'Lacking'?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</body>
</html>