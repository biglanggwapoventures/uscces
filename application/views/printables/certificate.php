<!DOCTYPE html>
<html>
<head>
	<title>CES Certificate</title>
	 <?= plugin_css('bootstrap/css/bootstrap.min.css')?>

	 <style type="text/css">
		 .image { 
		   position: relative; 
		   width: 100%; /* for IE 6 */
		}

		h2#name { 
		   position: absolute; 
		   top: 280px; 
		   width: 100%; 
		}

		p#details { 
		   position: absolute; 
		   top: 390px; 
		   width: 100%; 
		   padding-left:70px;
		   padding-right:70px;
		   margin: 0px;
		   line-height: 0.4;
		}

		h2#name span { 
		   font: bold 27px/48px Helvetica, Sans-Serif; 
		   letter-spacing: -1px; 
		   padding: 10px; 
		}
		p#details span { 
		   font: italic 19px/40px Helvetica, Sans-Serif; 
		   letter-spacing: -1px; 
		   padding: 10px; 
		}

		span.spacer {
		   padding:0 5px;
		}
	 </style>
</head>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="image">

     <img class="center-block img-responsive" src="<?= base_url('assets/img/ces-cert.png')?>"/>
      
     <h2 class="text-center" id="name"><span><?= "{$user['firstname']} {$user['middlename']} {$user['lastname']}"?><span class='spacer'></span></h2>

     <p class="text-center" id="details"><span>For serving as volunteer during the "<?= $activity['name']?>" held last <?= date('F d, Y', strtotime($activity['datetime']))?> at <?= $activity['location']?>.<span class='spacer'></span></h2>


</div>
			
		</div>
	</div>
</div>

</body>
</html>
