<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
createSidebar(2, 13);
if ($_SESSION['user_type'] != 'Admin') {
  $sql = "SELECT * FROM `tb_company` INNER JOIN `tb_user` ON `tb_company`.`assigned_client` = `tb_user`.`id` WHERE `company_delete` != 'Yes' AND assigned_client = '".$_SESSION['user_id']."' ORDER BY `company_id` ASC";
} else {
  $sql = "SELECT * FROM `tb_company` INNER JOIN `tb_user` ON `tb_company`.`assigned_client` = `tb_user`.`id` WHERE `company_delete` != 'Yes' ORDER BY `company_id` ASC";
}

$result = mysqli_query($con, $sql);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.1/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Company Details</h1>
          <ol class="breadcrumb">
            <a href="#" style="float: right;" class="btn btn-primary add-company" data-toggle="modal" name = "addcompany" data-target="#addcompany">Add Company</a>
            <div class="modal fade" id="addcompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="left">
              <div class="modal-dialog">
                <div class="modal-content">
                  <!-- Modal Header -->
                  <div class="modal-header">
                      <button type="button" class="close" 
                         data-dismiss="modal">
                             <span aria-hidden="true">&times;</span>
                             <span class="sr-only">Close</span>
                      </button>
                      <h4 class="modal-title" id="myModalLabel">Add Company</h4>
                  </div>
                  <!-- Modal Body -->
                  <div class="modal-body"> 
                    <form id="add-company-form" name="company-form">
                      <div class="form-group">  
                        <label>Company Name*</label> 
                        <input class="form-control" type="text" id="companyname" name="companyname" placeholder="companyname">  
                      </div>  
                      <div class="form-group">  
                        <?php
                          if ($_SESSION['user_type'] == 'Client') {
                            echo '<input class="form-control" type="hidden" id="client_id" name="client_id" readonly value= "'.encryptorDecryptor('encrypt',$_SESSION['user_id']).'">';
                          } else {
                              $result1 = mysqli_query($con, "SELECT * FROM `tb_user` WHERE `user_status` != 'Inactive' AND `user_type` = 'Client' ORDER BY `id` ASC");
                              echo '<label>Client Name*</label><select id= "client_id" name="client_id" class= "form-control select2">';
                              echo "'+'";
                              echo '<option value="" selected = selected>Select Client</option>';
                              while ($row = mysqli_fetch_array($result1, MYSQLI_BOTH)) {
                              echo '<option value="'.encryptorDecryptor('encrypt',$row['id']).'">'.$row['fname'].' '.$row['lname'].'</option>';
                              }
                              echo "</select>";
                            }
                        ?>
                      </div>
                      <div class="form-group">
                        <label>Upload Logo (Optional)</label>
                        <input name="file[]" id="file" type="file" accept="image/*" class="span10"><br>
                        - Resolution of logo must be width = 2800 and height = 600<br>
                        - Logo format should be jpg/jpeg
                      </div>
                      <input type="submit" class="btn btn-primary" value="Submit">
                    </form> 
                  </div>
                </div>
              </div>
            </div>
          </ol>
        </section>
        <section class="content">
        <div class="box">
          <div class="box-body">
            <table id="example1" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <?php
                  if ($_SESSION['user_type'] == 'Admin') {
                    echo "<th>Assigned Client</th>";
                  }
                  ?>
                  <th>Created On</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              while($row = mysqli_fetch_array($result))
              {
              echo '<tr id = "'.$row['company_id'].'">';
              echo "<td>" . $row['company_id']. "</td>";
              echo "<td>" . $row['company_name'] . "</td>";
              if ($_SESSION['user_type'] == 'Admin') {
                echo "<td>".$row['fname']." ".$row['lname']."</td>";
              }
              echo "<td>" . $row['company_creation'] . "</td>";
              echo "<td>" . $row['company_status'] . "</td>";
              echo '<td>';
              if($row['company_status'] == 'Active') {
                echo '<button type="button" title="Deactivate" name = "" value= "'.$row['company_id'].'" id = "'.encryptorDecryptor('encrypt', $row['company_id']).'" class="fa fa-close"></button>';
              }
              else {
                echo '<button type="button" title="Activate" name = "" value= "'.$row['company_id'].'" id = "'.encryptorDecryptor('encrypt', $row['company_id']).'" class="fa fa-check"></button>';
              }
              echo '<button type="button" name = "editcompany" id = "'.encryptorDecryptor('encrypt', $row['company_id']).'" class="fa fa-edit"></button>';
              echo '<button type="button" name = "delete" id = "'.encryptorDecryptor('encrypt', $row['company_id']).'" class="fa fa-trash"></button>';
              echo '</td>';
              echo "</tr>";
              }
              ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div>
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
    <script type="text/javascript" src="plugin/jQuery/jquery.validate.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.1/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
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
      jQuery.validator.setDefaults({
        debug: false
      });

      $(document).ready(function() {

        $('#example1').DataTable({
          fixedHeader: true,
          responsive: true
        });
        $('#example1').on( "click", ".fa-trash", function() {
          var id = $(this).attr('id');
          var parent = $(this).parent().parent();
          bootbox.confirm("Are you sure you want to delete this Company ID#"+$(this).attr('value')+"?", function(result){
            if (result) {
              $.ajax({
                 type: "POST",
                 url: "ajax_pages/delete_company.php",
                 dataType : 'json',
                 data: {data_id: id},
                 cache: false,
                 success:function(resp){
                   if(resp.status == 'success'){
                      parent.fadeOut('slow', function() {
                        $(this).remove();
                        bootbox.alert(resp.message);
                      });
                    } else {
                      bootbox.alert(resp.message);
                    }
                  }
               });
            }
          });
        });
        $('#example1').on( "click", ".fa-close", function() {
          var id = $(this).attr('id');
          bootbox.confirm("Are you sure you want to Deactivate this company #"+$(this).attr('value')+"?", function(result){
          if (result) {
            $.ajax({
             type: "POST",
             url: "ajax_pages/company_status.php",
             dataType : 'json',
             beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
             data: {data_id: id},
             cache: false,
             success:function(resp){
               if(resp.status == 'success'){
                  $('.box-body').unmask();
                  bootbox.alert(resp.message, function() {
                    document.location = 'company.php';
                  });
                } else{
                    $('.box-body').unmask();
                    bootbox.alert(resp.message);
                  }
              }
          });
          }
          });
        });
        $('#example1').on( "click", ".fa-check", function() {
          var id = $(this).attr('id');
          bootbox.confirm("Are you sure you want to Activate this company #"+$(this).attr('value')+"?", function(result){
          if (result) {
            $.ajax({
             type: "POST",
             url: "ajax_pages/company_status.php",
             dataType : 'json',
             beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
             data: {data_id: id},
             cache: false,
             success:function(resp){
               if(resp.status == 'success'){
                  $('.box-body').unmask();
                  bootbox.alert(resp.message, function() {
                    document.location = 'company.php';
                  });
                } else{
                    $('.box-body').unmask();
                    bootbox.alert(resp.message);
                  }
              }
          });
          }
          });
        });
        $('#example1').on( "click", ".fa-edit", function() {
          var id = $(this).attr('id');
          window.location = 'edit_company.php?id='+id;
        });
        $("#add-company-form").validate({
          rules: {
            companyname: {
              required: true
            },
            client_id: {
              required: true
            }
          },
          messages: {
            companyname: {
              required: "Add Company Name!"
            },
            client_id: {
              required: "Add Client"
            }
          }
        });
        $('form[name=company-form]').ajaxForm({
         'type':'POST',
         dataType : 'json',
         'url':'ajax_pages/add_company_valid.php',
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
                    location.reload();
                  });
              }
              else{
                  bootbox.alert(resp.message);
                  $('form[name=company-form]').unmask();
              }
          }
        });
      });
    </script>
  </body>
</html>