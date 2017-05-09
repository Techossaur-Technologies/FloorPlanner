<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
if($_SESSION['user_type'] != 'Admin'){
  header("Location: dashboard.php");
  exit();
}
createSidebar(1, 17);
$template_id = encryptorDecryptor('decrypt', $_GET['id']);
$qry = "SELECT * FROM email_templates WHERE id = '".$template_id."'";
$qrs = @mysqli_query($con, $qry);
$tpl_info = mysqli_fetch_array($qrs, MYSQLI_BOTH);
?>
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
    <link rel="stylesheet" type="text/css" href="plugin/summernote/summernote.css">
      <div class="content-wrapper">
        <section class="content-header">
          <h1 style="color: #000000;">Email Template</h1>
          <ol class="breadcrumb">
            <li><i class="fa fa-gear"></i> Settings</li>
            <li><a href="email_template.php"> Email Template</a></li>
            <li class="active">Edit Email Template</a></li>
          </ol>
        </section>
        <section class="content">
        <div class="box">
          <div class="box-body">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <form name="edit_template" id="edit_template" action="" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="templateId" id="templateId" value="<?php echo encryptorDecryptor('encrypt', $template_id); ?>">
                  <div class="form-group">
                      <label>Email Type: </label>
                      <input type="text" readonly="readonly" class="form-control" value="<?php echo $tpl_info['tpltype'];?>">
                  </div>

                  <div class="form-group">
                      <label>Description: </label>
                      <input type="text" readonly="readonly" class="form-control" value="<?php echo $tpl_info['description'];?>">
                  </div>

                  <div class="form-group">
                      <label>Recepient Type: </label>
                      <input type="text" readonly="readonly" class="form-control" value="<?php echo $tpl_info['mailtype'];?>">
                  </div>

                  <div class="form-group">
                      <label>Subject*: </label>
                      <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $tpl_info['subject'];?>">
                  </div>

                  <div class="form-group">
                      <label>Mail To/From*: </label>
                      <input type="text" class="form-control" id="mailfrom" name="mailfrom" value="<?php echo $tpl_info['mailfrom'];?>">
                  </div>

                  <div class="form-group">
                      <label>Mail To/From Name*: </label>
                      <input type="text" class="form-control" id="mailfromname" name="mailfromname" value="<?php echo $tpl_info['mailfromname'];?>">
                  </div>

                  <div class="form-group">
                      <label>Template:</label>
                      <textarea name="message" class="summernote" id="summernote"><?php echo base64_decode($tpl_info['messagebody']);?></textarea>
                  </div>

                  <div class="form-group">
                    <center>
                      <input type="submit" class="btn btn-primary" name="submit" value="Update">&nbsp;
                      <a class="btn btn-success" href="email_template.php">Back</a>
                    </center>
                  </div>
              </form>
            </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div>
        </div><!-- /.row -->
        </section>
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Coredigita</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugin/jQueryUI/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script type="text/javascript" src="plugin/jQuery/jquery.validate.min.js"></script>
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
    <script src="plugin/summernote/summernote.min.js"></script>
    <script type="text/javascript">
       $(document).ready(function(){
        // Create Base64 Object
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

        // Initialize summernote plugin
        $('.summernote').summernote();
        // Validation
        $("form[name=edit_template]").validate({
            event: "keyup",
            rules: {
                subject: {
                    required: true
                },
                mailfrom: {
                    required: true,
                    email: true
                },
                mailfromname: {
                    required: true
                },
            },
            submitHandler: function() {
                var message_code = $('.summernote').summernote('code');
                var message_encode = Base64.encode(message_code);
              $.ajax({
                  'type':'post',
                  'url':'ajax_pages/submit_template.php',
                  'beforeSend':function(){ $('#edit_template').mask('Updating...'); },
                  'dataType':'json',
                  'data':{'templateId':$("#templateId").val(), 'subject':$("#subject").val(), 'mailfrom':$("#mailfrom").val(), 'mailfromname':$("#mailfromname").val(), 'message': message_encode},
                  'success':function(resp){
                    if(resp.status == 'success') {
                      $('#edit_template').unmask();
                      bootbox.alert(resp.message, function() {
                        document.location = 'http://floorplanner.techossaur.com/email_template.php';
                      });
                    } else {
                      $('#edit_template').unmask();
                      bootbox.alert(resp.message);
                    }
                  }
              });
            }
        });
       });
    </script>
  </body>
</html>