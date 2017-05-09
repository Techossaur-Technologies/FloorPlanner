<?php
include('includes/header.php');
createSidebar(1, 15);
?>
<link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Change Password</h1>
          <ol class="breadcrumb">
            <li><i class="fa fa-gear"></i> Settings</li>
            <li class="active">Change Password</li>
          </ol>
        </section>
        <section class="content">
          <div class="box">
            <div class="box-body">
              <div class="col-md-4"></div>
              <div class="col-md-4">
                <form id="pass-form" name="pass-form" action="#" method="POST">
                  <div class="form-group">
                    <label>Old Password*</label>
                    <input class="form-control" type="password" title="Please enter your Old password" placeholder="******" required="" id="oldpassword" name="oldpassword">
                  </div>
                  <div class="form-group">
                    <label>New Password*</label>
                    <input class="form-control" type="password" title="Please enter your New password" placeholder="******" required="" id="newpassword" name="newpassword">
                  </div>
                  <div class="form-group">
                    <label>Confirm New Password*</label>
                    <input class="form-control" type="password" title="Please enter your New password Again" placeholder="******" required="" id="connewpassword" name="connewpassword">
                  </div>
                  <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/>
                  <a class="btn btn-success" href="dashboard.php">Back</a>
                </form>
              </div>
              <div class="col-md-4"></div>
            </div>
          </div>
        </section>
        </div>
        <footer class="main-footer">
          <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Coredigita</a>.</strong> All rights reserved.
        </footer>
      </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugin/jQueryUI/jquery-ui.min.js"></script>
    <script type="text/javascript" src="plugin/jQuery/jquery.validate.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugin/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="plugin/bootbox.min.js"></script>
    <script src="plugin/jquery_loadmask/jquery.loadmask.min.js"></script>
    <script type="text/javascript">

      jQuery.validator.addMethod("notEqual", function(value, element, param) {
       return this.optional(element) || value != $(param).val();
      }, "This has to be different...");

      jQuery.validator.setDefaults({
      debug: false
      //success: "valid"
      });
      $(document).ready(function(){
        // event.preventDefault();
        $("#pass-form").validate({
          rules: {
              oldpassword: {
                  required: true
              },
              newpassword: {
                  required: true,
                  notEqual: "#oldpassword"
              },
              connewpassword: {
                  required: true,
                  equalTo: "#newpassword"
              }
          },
          messages: {
              oldpassword: {
                  required: "Enter Old Password"
              },
              newpassword: {
                  required: "Enter New Password",
                  notEqual: "Password should not match with previous!"
              },
              connewpassword: {
                  required: "Type Password Again",
                  equalTo: "Password Not Match"
              }
          },
        submitHandler: function() {

          $.ajax({
                 type: 'POST',
                 url: 'ajax_pages/pass_change_valid.php',
                 beforeSend: function(){ $('.box-body').mask('Validating...'); },
                 dataType : 'json',
                 data: $('#pass-form').serialize(),
                 cache: false,
                 success:function(data){
                   if(data.status == 'success'){
                    $('.box-body').unmask();
                    bootbox.alert(data.message);
                    $('#pass-form')[0].reset();
                   }
                   else{
                    $('.box-body').unmask();
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