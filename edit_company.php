<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
createSidebar(2, 13);
$company_id =  encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_GET["id"]));
$company_details = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tb_company WHERE company_id = '".$company_id."'"));
?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
    <link rel="stylesheet" href="plugin/fancybox/jquery.fancybox.css">

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Edit Company ID #<?php echo $company_id; ?></h1>
          <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> Report</li>
            <li><a href="company.php">Company</a></li>
            <li class="active">Edit Company</li>
          </ol>
        </section>
        <section class="content">
        <div class="box">
          <div class="box-body">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <form id="add-company-form" name="company-form">
                <div class="form-group"> 
                  <input class="form-control" type="hidden" id="company_id" name="company_id" value="<?php echo $_GET["id"]; ?>">
                  <input class="form-control" type="hidden" id="company_logo_name" name="company_logo_name" value="<?php echo $company_details['logo_path'].$company_details['company_logo']; ?>">
                </div>
                <div class="form-group">  
                  <label>Company Name</label> 
                  <input class="form-control" type="text" id="companyname" name="companyname" value="<?php echo $company_details['company_name']; ?>" placeholder="companyname">  
                </div>  
                <div class="form-group">  
                  <?php
                    if ($_SESSION['user_type'] == 'Client') {
                      echo '<input class="form-control" type="hidden" id="client_id" name="client_id" readonly value= "'.encryptorDecryptor('encrypt',$_SESSION['user_id']).'">';
                    } else {
                        $result1 = mysqli_query($con, "SELECT * FROM `tb_user` WHERE `user_status` != 'Inactive' AND `user_type` = 'Client' ORDER BY `id` ASC");
                        echo '<label>Client Name</label><select id= "client_id" name="client_id" class= "form-control select2">';
                        while ($row = mysqli_fetch_array($result1, MYSQLI_BOTH)) {
                          if($row['id'] == $company_details['assigned_client']){
                            echo "<option value='" .encryptorDecryptor('encrypt',$row['id']). "' selected>" . $row['fname'].' '.$row['lname'] . "</option>";
                          }
                          else{
                            echo '<option value="'.encryptorDecryptor('encrypt',$row['id']).'">'.$row['fname'].' '.$row['lname'].'</option>';
                          }
                        }
                        echo "</select>";
                      }
                  ?>
                </div>
                <div class="form-group">  
                  <label>Company Logo</label> 
                  <?php
                    echo '<a class="fancybox" rel="group" href="logo/'.$company_details['logo_path'].$company_details['company_logo'].'"><img src="timthumb.php?src='.$baseurl.'logo/'.$company_details['logo_path'].$company_details['company_logo'].'&h=120&w=560&q=30 "></a>';
                  ?>
                </div>
                <div class="form-group">
                  <label>Upload Logo (Optional)**</label>
                  <input name="file[]" id="file" type="file" accept="image/*" class="span10">
                  <div>
                    <ul>
                      <li>** Resolution of logo must be width = 2800 and height = 600</li>
                    </ul>
                  </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="company.php" class="btn btn-success">Back</a>
              </form>
            </div>
            <div class="col-md-3"></div>
          </div><!-- /.col -->
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
    <script type="text/javascript" src= "plugin/jquery.form.js"></script>
    <script type="text/javascript" src= "plugin/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript">
       $(document).ready(function(){
          $(".fancybox").fancybox();
          $('form[name=company-form]').ajaxForm({
           'type':'POST',
           dataType : 'json',
           'url':'ajax_pages/edit_company_valid.php',
           'data': $('form[name=company-form]').serialize(),
           'beforeSend': function() {
              var percentVal = '0%';
              $('form[name=company-form]').mask('Loading '+percentVal+'');
            },
            'uploadProgress': function(event, position, total, percentComplete) {
              var percentVal = percentComplete + '%';
              $('.loadmask-msg').empty();
              $('.loadmask-msg').html('<div>Loading '+percentVal+'</div>');
            },
           'success':function(resp){
               if(resp.data == 'success'){
                    bootbox.alert(resp.message, function(){
                      $('form[name=company-form]').unmask();
                      document.location = 'company.php';
                    });
                }
                else{
                    bootbox.alert(resp.message);
                    $('form[name=company-form]').unmask();
                    $('#file').val('');
                }
            }
          });
      });
    </script>
  </body>
</html>