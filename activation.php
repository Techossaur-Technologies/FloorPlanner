<?php
include_once('includes/config.php');
$activation = $_GET["activation"];
$user = $_GET["user"];
$user_details = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tb_user WHERE activation_link = '".$activation."' AND user_type = '".$user."'"));
if ($user_details['user_status'] == 'InActive') {
	mysqli_query($con, "UPDATE `tb_user` SET user_status = 'Active' WHERE activation_link = '".$activation."' AND user_type = '".$user."'");
	$text = '<h2>Hello Mr. '.$user_details['fname'].' '.$user_details['lname'].'</h2><br>
			<h3>Welcome to Floorplanner</h3><br>
			<h4 style="color:green;">Your account has been activated. Please <a href="login.php">click here</a> to login.</h4>';
} 
else if ($user_details['user_status'] == 'Active') {
	$text = '<h2>Hello Mr. '.$user_details['fname'].' '.$user_details['lname'].'</h2><br>
			<h3>Welcome to Floorplanner</h3><br>
			<h4 style="color:red;">Your account has already activated. Please <a href="login.php">click here</a> to login.</h4>';
}
else {
	$text = '<h2 style="color:red;">Invalid activation code.</h2>';
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
  </head>
  <body class="hold-transition login-page">
    <!-- <div class="box" align="center">
    <?php echo $text; ?>
    </div> --><!-- /.login-box -->
    <div class="login-box" align="center">
      <div class="login-logo">
        <a href="#"><img src="logo/img/logo.png"></a>
      </div>
      <div class="login-box-body">
      <?php echo $text; ?>
      </div>
    </div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugin/jQueryUI/jquery-ui.min.js"></script>
  </body>
</html>
