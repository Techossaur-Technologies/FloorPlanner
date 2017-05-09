<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
createSidebar(2, 14);
 $id1 =  encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_GET["id"]));
 $que1 = mysqli_query($con, "SELECT * FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id` WHERE `prj_id`= '".$id1."'");
 $arr = mysqli_fetch_assoc($que1);
?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Edit Assignment Id# <?php echo $id1; ?></h1>
          <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> Report</li>
            <li><a href="project.php">Assignment</a></li>
            <li class="active">Edit Assignment</li>
          </ol>
        </section>
        <section class="content">
        <div class="box">
          <div class="box-body">
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <form id="project-form" name="project-form" action="#" method="POST">
                <div class="form-group">
                  <input class="form-control" type="hidden" id="projectid" name="projectid" value="<?php echo encryptorDecryptor('encrypt', $id1); ?>">
                </div>
                <div class="form-group">
                  <label>Project Name</label>
                  <input class="form-control" type="text" id="projectname" name="projectname" value="<?php echo $arr['project_name'] ?>" >
                </div>
                <div class="form-group">
                  
                  <?php
                  if ($_SESSION['user_type'] == 'Client') {
                    echo '<input class="form-control" type="hidden" id="client_id" name="client_id" value= "'.$_SESSION['user_id'].'">';
                  } else {
                    $result1 = mysqli_query($con, "SELECT * FROM `tb_user` WHERE `user_status` != 'Inactive' AND `user_type` = 'Client' ORDER BY `id` ASC");
                    echo "<label>Client Name</label><select name='client_id' id ='client_id' class= 'form-control select2'>";
                    while ($row = mysqli_fetch_array($result1, MYSQLI_BOTH)) {
                        if($row['id'] == $arr['id']){
                          echo "<option value='" . $row['id'] . "' selected>" . $row['fname'].' '.$row['lname'] . "</option>";
                        }
                        else{
                          echo "<option value='" . $row['id'] . "'>" . $row['fname'].' '.$row['lname'] . "</option>";
                        }
                    }
                    echo "</select>";
                  }                  ?>
                </div>
                <div class="form-group">
                  <label>Company Name</label>
                  <?php
                  if ($_SESSION['user_type'] == 'Client') {
                    $result2 = mysqli_query($con, 'SELECT * FROM tb_company WHERE assigned_client = "'.$_SESSION['user_id'].'"');
                    echo '<select name="company_id" class= "form-control select2">';
                    echo '<option value="0" selected = selected>Select Company</option>';
                    while ($row = mysqli_fetch_array($result2, MYSQLI_BOTH)) {
                        if($row['company_id'] == $arr['company_id']){
                          echo "<option value='" . $row['company_id'] . "' selected>" . $row['company_name'] . "</option>";
                        }
                        else{
                          echo "<option value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
                        }
                    }

                  } else {
                    echo '<select name="company_id" id="company_id" class= "form-control select2">';
                    echo '<option value="'.$arr['company_id'].'" selected = selected>'.$arr['company_name'].'</option>';
                  }
                  echo "</select>";
                  ?>
                </div>
                <div class="form-group">
                  <label>Attachments</label>
                  <input name="file[]" id="file" type="file" multiple="multiple" accept="image/*,application/pdf" class="span10">
                </div>
                <input type="submit" name="submit" value="Submit" class="btn btn-primary"/>
                <a href="project.php" class="btn btn-success">Back</a>
              </form>
            </div>
            <div class="col-md-4"></div>
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
    <script type="text/javascript" src= "plugin/jquery.form.js"></script>
    <script type="text/javascript">
       $(document).ready(function(){
        $("#project-form").validate({
          rules: {
            projectname: {
              required: true
            },
            client_name: {
              required: true
            },
            company_name: {
              required: true
            }
          },
          messages: {
            projectname: {
              required: "Project Name must required!"
            },
            client_name: {
              required: "Select Client"
            },
            company_name: {
              required: "Select Company"
            }
          }
        });
        $("form[name=project-form]").ajaxForm({
           'type':'POST',
           dataType : 'json',
           'url':'ajax_pages/project_edit_update.php',
           'data': $('#project-form').serialize(),
           // 'async': false,
           'beforeSend': function() {
              var percentVal = '0%';
              $('#project-form').mask('Loading '+percentVal+'');
            },
            'uploadProgress': function(event, position, total, percentComplete) {
              var percentVal = percentComplete + '%';
              $('.loadmask-msg').empty();
              $('.loadmask-msg').html('<div>Loading '+percentVal+'</div>');
            },
           'success':function(resp){
               if(resp.data == 'success'){
                    $('.box-body').unmask();
                    bootbox.alert(resp.message, function() {
                      document.location = 'project.php';
                    });
                }
                else{
                    $('.box-body').unmask();
                    bootbox.alert(resp.message);
                }
           }
        });
        $('#client_id').change(function(){
          var client_id = $(this).val();
          $.ajax({
            'type':'post',
            'url':'ajax_pages/company_list.php',
            'data':{'client':client_id},
            'dataType':'json',
            'beforeSend':function(){ $('#project-form').mask('Please Wait...'); },
            'success':function(resp){
                $('#company_id').children('option').empty().remove();
                $.each(resp,function(intex,info){
                  $('<option value="'+ info.company_id +'">'+ info.company_name +'</option>').appendTo('#company_id');
                  $('#project-form').unmask();
                });
            }
          });
        });
        $('#company_id').one("click", function(){
          var client_id = $('#client_id').val();
          $.ajax({
            'type':'post',
            'url':'ajax_pages/company_list.php',
            'data':{'client':client_id},
            'dataType':'json',
            'beforeSend':function(){ $('#project-form').mask('Please Wait...'); },
            'success':function(resp){
                $('#company_id').children('option').empty().remove();
                $.each(resp,function(intex,info){
                  $('<option value="'+ info.company_id +'">'+ info.company_name +'</option>').appendTo('#company_id');
                  $('#project-form').unmask();
                });
            }
          });
        });
      });
    </script>
  </body>
</html>