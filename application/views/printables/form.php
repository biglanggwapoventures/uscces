<!DOCTYPE html>
<html>
<head>
	<title>CES Form</title>
	 <?= plugin_css('bootstrap/css/bootstrap.min.css')?>
	 <style type="text/css">
	 	p.min{
	 		margin:0;
	 		text-align: center;
	 		font-family: Calibri;
	 	}
	 	table td{
	 		border: 1px solid black;
	 		padding:3px;
	 	}
	 	.address address{
	 		margin-top:35px;
	 	}
	 </style>
</head>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 header-block">
			<img class="center-block" style="height:50px" src="<?= base_url('assets/img/dcis-logo.png')?>"/>
			<p class="min">Department of Computer and Information Science</p>
			<p class="min">School of Arts and Sciences</p>
			<p class="min">in collaboration with</p>
			<img class="center-block"  style="height:80px" src="<?= base_url('assets/img/ces-logo.png')?>"/>
			<p class="text-center"><b>STUDENT CES REPORT</b></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table  style="width:100%;font-size: 10px;border: 1px solid black;text-align: center;">
				<tr><td rowspan="2">Outline of Program/Project/Activity</td><td colspan="3">Beneficiary(ies)<br>(List down all the names of the beneficiaries)</td></tr>
				<tr><td>Last Name</td><td >First Name</td><td  class="text-center">Middle Initial</td></tr>
				<?php $participants_count = count($participants);?>

				<?php $num = 20;?>
				<?php $diff = 0;?>
				
				<?php if($participants_count > $num):?>
					<?php $diff = $participants_count-$num ?>
					<?php $num = $participants_count ?>

				<?php endif?>

				<?php for($x = 0; $x<$num; $x++):?>
					<tr>
						<?php if($x === 0):?>
							<td rowspan="4" style="vertical-align: middle">
								<dl class="dl-horizontal">
								  <dt>Activity name</dt>
								  <dd><?= $activity['name']?></dd>
								</dl>
							</td>
						<?php elseif($x === 4):?>
							<td rowspan="7" style="vertical-align: middle">
								<dl class="dl-horizontal">
								  <dt>Date &amp; time</dt>
								  <dd><?= date('d-M-Y h:i:s A', strtotime($activity['datetime']))?></dd>
								  <dt>Location</dt>
								  <dd><?= $activity['location']?></dd>
								  <dt>Nature of the activity</dt>
								  <dd><?= $activity['nature']?></dd>
								  <dt>Area of the activity</dt>
								  <dd><?= $activity['area']?></dd>
								</dl>
							</td>
						<?php elseif($x === 11):?>
							<td rowspan="<?= 9 + $diff?>" style="vertical-align: top">Please write the outline below with detailed Outline of Program/Project/Activity of your CES:</td>
						<?php endif;?>
						<td><?= isset($participants[$x]['lastname']) ? $participants[$x]['lastname'] : '&nbsp;' ?></td>
						<td><?= isset($participants[$x]['firstname']) ? $participants[$x]['firstname'] : '&nbsp;' ?></td>
						<td><?= isset($participants[$x]['middlename']) ? $participants[$x]['middlename'][0] : '&nbsp;' ?></td>
					</tr>
				<?php endfor;?>
			</table>
		</div>
	</div>
	<div class="row address" style="<?= $num > 20 ? 'margin-top:20px': 'position: fixed;bottom: 0'?>">
		<div class="col-xs-3">
			Noted by:
			<address>
			  <strong>Marilou M. Iway</strong><br>
			  CS Faculty, CES <br>
			  Representative
			</address>
		</div>
		<div class="col-xs-3">
			Noted by:
			<address>
			  <strong>Daisy Salve </strong><br>
			  CAS (Science Division) <br>
			  Coordinator
			</address>
		</div>
		<div class="col-xs-3">
			Approved by:
			<address>
			  <strong>Mary Jane G. Sabellano</strong><br>
			  Chair, CS Deparment
			</address>
		</div>
		<div class="col-xs-3">
			Noted by:
			<address>
			  <strong>Brenette L. Abrenica</strong><br>
			  USC-CES Director
			</address>
		</div>
	</div>
</div>

</body>
</html>
