<?php 
// session_start();
include('includes/config.php');
if (isset($_COOKIE['token']) && isset($_COOKIE['token_id'])) {
	header( "Location:".$baseurl."ajax_pages/login_valid.php" );
}
if ($_SESSION['user_name'] != '') {
  header( "Location:".$baseurl."dashboard.php" );
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Coredigita</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugin/iCheck/square/blue.css">
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
    <link rel="stylesheet" href="plugin/jQuery/screen.css">

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
        <a href="#"><img src="logo/img/logo.png"></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form id="login-form" name="login-form" action="#" method="post">
          <input type="hidden" name="_action" value="_user_login" />
          <input type="hidden" name="formSubmit" value="1" />
          <div class="form-group">
            <label class="control-label" for="email">Email</label>
            <input type="text" placeholder="example@domain.com" title="Please enter you email" required="" value="" name="email" id="email" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="password">Password</label>
            <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="password" id="password" class="form-control">
          </div>
          <div class="checkbox" style="text-align:center">
              <input name="autologin" id="autologin" type="checkbox" class="i-checks" checked>
                   Remember login
              <p class="help-block small">(if this is a private computer)</p>
          </div>
          <input class="btn btn-success btn-block" name="submit" type="submit" value="Login"/>
          <a class="btn btn-block btn-primary" href="forgot-password.php">Forgotten password?</a>
          <a class="btn btn-block btn-info" href="signup.php" >Sign Up</a>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="plugin/jQuery/jquery.validate.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugin/iCheck/icheck.min.js"></script>
  <script src="plugin/jQueryUI/jquery-ui.min.js"></script>
  <script src="plugin/jquery_loadmask/jquery.loadmask.min.js"></script>
  <script src="plugin/bootbox.min.js"></script>
  <script type="text/javascript">

jQuery.validator.setDefaults({
    debug: false
    //success: "valid"
});
$(document).ready(function(){

  $("#login-form").validate({
      rules: {
          email: {
              required: true,
              email: true
          },
          password: {
              required: true
          }
      },
      messages: {
          email: {
              required: "Specify email",
              email: "Enter a valid email"
          },
          password: {
              required: "Specify password"
          }
      },
      submitHandler: function() {
        $.ajax({
           'type':'POST',
           'url':'ajax_pages/login_valid.php',
           'beforeSend': function(){ $('.login-box').mask('Validating...'); },
           'data': $('#login-form').serialize(),
           'async': false,
           'success':function(resp){
              console.log(resp);
               if(resp == 'success'){
                    document.location = 'dashboard.php';
                } else if (resp == 'inactive'){
                    document.location = 'account_inactive.php';
                } else{
                    $('.login-box').unmask();
                    bootbox.alert('Invalid Email or Password');
                }
           }
        });
      }

  });
});
  </script>
  </body>
</html>
