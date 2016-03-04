<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CES Portal | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <?= plugin_css('bootstrap/css/bootstrap.min.css')?>
    <!-- Theme style -->
    <?= css('AdminLTE.min.css')?>
    <!-- custom style -->
    <?= css('custom.css')?>

    <style type="text/css">
    html,body{
     /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b4e391+0,61c419+50,b4e391+100;Green+3D */
background: #b4e391; /* Old browsers */
background: -moz-linear-gradient(top,  #b4e391 0%, #61c419 50%, #b4e391 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  #b4e391 0%,#61c419 50%,#b4e391 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  #b4e391 0%,#61c419 50%,#b4e391 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b4e391', endColorstr='#b4e391',GradientType=0 ); /* IE6-9 */


    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page" style="background: none;">
    <div class="row" style="margin:7% auto">
      <div class="col-md-7 hidden-sm hidden-xs" style="margin: auto;">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="
            1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
             <div class="item">
               <img  data-holder-rendered="true" src="<?= base_url('assets/img/carousel/1.jpg')?>">
              </div> 
             <div class="item"> 
              <img  data-holder-rendered="true" src="<?= base_url('assets/img/carousel/2.jpg')?>"> 
            </div> 
            <div class="item active"> 
              <img data-holder-rendered="true" src="<?= base_url('assets/img/carousel/3.jpg')?>"> 
            </div> 
          </div>

          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-5">
        <div class="login-box" style="margin:0 auto;">
     
      <div class="login-box-body" style="border-radius: 4px;background-color:rgba(180, 227, 145, 0.9)">
       <div class="login-logo" style="font-size:25px;">
        
        <img class="img-responsive img-thumbnail img-circle" src="<?= base_url('assets/img/ces.png')?>"><br>
        <a style="color:#00a65a;">CES PRE-ENROLLMENT AND TRACKING SYSTEM 2016</a>
      </div><!-- /.login-logo -->
        <?php if($error_messages):?>
          <div class="alert alert-danger"> 
            <ul class="list-unstyled">
              <?php foreach($error_messages AS $err):?>
                  <li><?= $err?></li>
              <?php endforeach;?>
            </ul>
          </div>
        <?php endif;?>
        <form action="<?= base_url('index.php/login')?>" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="ID Number / Username" name="username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <button type="submit" class="btn btn-success btn-block btn-flat">Log in</button>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
      </div>
    </div>
    

    <!-- jQuery 2.1.4 -->
    <?= plugin_script('jQuery/jQuery-2.1.4.min.js')?>
    <!-- Bootstrap 3.3.5 -->
  	<?= plugin_script('bootstrap/js/bootstrap.min.js')?>
  </body>
</html>
