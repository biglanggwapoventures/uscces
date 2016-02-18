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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
      	<img src="<?= base_url('assets/img/usc.png')?>">
        <a ><b>CES</b> PETS</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">CES PRE-ENROLLMENT AND TRACKING SYSTEM</p>
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
        <hr/>
        <a href="#" class="text-center">I forgot my password</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <?= plugin_script('jQuery/jQuery-2.1.4.min.js')?>
    <!-- Bootstrap 3.3.5 -->
  	<?= plugin_script('bootstrap/js/bootstrap.min.js')?>
  </body>
</html>
