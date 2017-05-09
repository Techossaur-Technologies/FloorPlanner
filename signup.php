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
        <a href="index.php"><img src="logo/img/logo.png"></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Create an Account</p>
        <form id="signup-form" name="signup-form" action="#" method="post">
          <div class="form-group">
            <label class="control-label" for="fname">First Name</label>
            <input type="text" placeholder="William" title="Please enter you Name" required="" value="" name="fname" id="fname" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="lname">Last Name</label>
            <input type="text" placeholder="Smith" title="Please enter you Name" required="" value="" name="lname" id="lname" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="email">Email</label>
            <input type="text" placeholder="example@domain.com" title="Please enter you email" required="" value="" name="email" id="email" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="reemail">Confirm Email</label>
            <input type="text" placeholder="example@domain.com" title="Confirm your email" required="" value="" name="reemail" id="reemail" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="password">Password</label>
            <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="password" id="password" class="form-control">
          </div>
          <div class="form-group">
            <label class="control-label" for="repassword">Confirm Password</label>
            <input type="password" title="Retype your password" placeholder="******" required="" value="" name="repassword" id="repassword" class="form-control">
          </div>
          <input class="btn btn-success btn-block" name="submit" type="submit" value="Sign Up"/>
          <a class="btn btn-block btn-info btn-sm" href="login.php" >Sign In with existing account</a>
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
    debug: true
});
$(document).ready(function(){

  $("#signup-form").validate({
      rules: {
          email: {
              required: true,
              email: true
          },
          reemail: {
              required: true,
              email: true,
              equalTo: "#email"
          },
          password: {
              required: true,
              minlength: 6
          },
          fname: {
              required: true,
          },
          lname: {
              required: true,
          },
          repassword: {
              required: true,
              equalTo: "#password"
          }
      },
      messages: {
          email: {
              required: "Specify email",
              email: "Enter a valid email"
          },
          reemail: {
              required: "Please retype your password",
              email: "Not a valid Email Id",
              equalTo: "Email Id not match"
          },
          password: {
              required: "Specify password",
              minlength: "Password must be minimum 6 charecters"
          },
          fname: {
              required: "Type your first name",
          },
          lname: {
              required: "Type your last name",
          },
          repassword: {
              required: "Please enter your password again",
              equalTo: "password not match"
          }
      },
      submitHandler: function() {
        $.ajax({
           'type':'POST',
           'dataType': 'json',
           'url':'ajax_pages/signup_valid.php',
           'beforeSend': function(){ $('.login-box').mask('Validating...'); },
           'data': $('#signup-form').serialize(),
           'async': false,
           'success':function(data){
               if(data.status == 'success'){
                    $('.login-box').unmask();
                    bootbox.alert(data.message, function() {
                      document.location = 'index.php';
                    });
                }
                else{
                    $('.login-box').unmask();
                    bootbox.alert(data.message);
                }
           }
        });
      }

  });
});
  </script>
  </body>
</html>
