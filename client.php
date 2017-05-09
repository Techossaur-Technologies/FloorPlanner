<?php
  header('Content-Type: text/html; charset=ISO-8859-1');
  include('includes/header.php');
  include('includes/all_functions.php');
  createSidebar(2, 12);
  if($_SESSION['user_type'] != 'Admin'){
      header("Location: dashboard.php");
      exit();
    }
  $sql = "SELECT * FROM tb_user WHERE user_delete != 'Yes' AND user_type = 'Client' ORDER BY id ASC";
  $result = mysqli_query($con, $sql);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.1/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Client Details</h1>
          <ol class="breadcrumb">
            <a href="#" style="float: right;" id="add_client" class="btn btn-primary add_client"/>Add Client</a>
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
                  <th>Email Id</th>
                  <th>Created On</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              while($row = mysqli_fetch_array($result))
              {
              echo '<tr id = "'.$row['id'].'">';
              echo "<td>" . $row['id']. "</td>";
              echo "<td>" . $row['fname'] .' '. $row['lname'] . "</td>";
              echo "<td>" . $row['email'] . "</td>";
              echo "<td>" . $row['user_creation'] . "</td>";
              echo "<td>" . $row['user_status'] . "</td>";
              echo '<td>';
              if($row['user_status'] == 'Active') {
                echo '<button type="button" title="Deactivate" name = "" value = "'.$row['id'].'" id = "'.encryptorDecryptor('encrypt', $row['id']).'" class="fa fa-close"></button>';
              }
              else {
                echo '<button type="button" title="Activate" name = "" value = "'.$row['id'].'" id = "'.encryptorDecryptor('encrypt', $row['id']).'" class="fa fa-check"></button>';
              }
              echo '<button type="button" name = "edit" value = "'.$row['id'].'" id = "'.encryptorDecryptor('encrypt', $row['id']).'" class="fa fa-edit"></button>';
              echo '<button type="button" name = "delete" value = "'.$row['id'].'" id = "'.encryptorDecryptor('encrypt', $row['id']).'" class="fa fa-trash"></button>';
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
    <script type="text/javascript">

      $(document).ready(function() {
        jQuery.validator.setDefaults({
          debug: false
        });
        var table = $('#example1').DataTable({
          fixedHeader: true,
          responsive: true
        });
        $('#example1').on( "click", ".fa-trash", function() {
          var id = $(this).attr('id');
          var parent = $(this).parent().parent();
          bootbox.confirm("Are you sure you want to delete this Client Id#"+$(this).attr('value')+"?", function(result){
            if (result) {
              $.ajax({
                 type: "POST",
                 dataType : 'json',
                 url: "ajax_pages/user_delete.php",
                 data: {data_id: id},
                 cache: false,
                 success:function(resp){
                   console.log(resp);
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
          bootbox.confirm("Are you sure you want to Deactivate this Client #"+$(this).attr('value')+"?", function(result){
          if (result) {
          $.ajax({
                 type: "POST",
                 dataType : 'json',
                 url: "ajax_pages/user_status.php",
                 beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
                 data: {data_id: id},
                 cache: false,
                 success:function(resp){
                   console.log(resp);
                   if(resp.status == 'success'){
                      $('.box-body').unmask();
                      bootbox.alert(resp.message, function() {
                        document.location = 'client.php';
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
          bootbox.confirm("Are you sure you want to Activate this Client #"+$(this).attr('value')+"?", function(result){
            if (result) {
              $.ajax({
                 type: "POST",
                 dataType : 'json',
                 url: "ajax_pages/user_status.php",
                 beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
                 data: {data_id: id},
                 cache: false,
                 success:function(resp){
                   console.log(resp);
                   if(resp.status == 'success'){
                      $('.box-body').unmask();
                      bootbox.alert(resp.message, function() {
                        document.location = 'client.php';
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
        $('#example1').on( "click", ".fa-edit", function(){
          var id = $(this).attr('id');
          var val = $(this).attr('value');
          edit_client(id, val);
        });
        function edit_client(id, val){
          bootbox.dialog({
            title: "Edit Client Id#"+val,
            buttons: {
              success: {
                label: "Save",
                className: "btn-primary",
                callback: function () {
                  $.ajax({
                    type : "POST",
                    dataType : 'json',
                    url: "ajax_pages/edit_client_valid.php",
                    beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
                    data: $('#edit-client-form').serialize(),
                    success:function(data){

                      if(data.status == 'success'){
                        $('.box-body').unmask();
                        bootbox.alert(data.message,function(){
                          document.location = 'client.php';
                        });
                      } else{
                          $('.box-body').unmask();
                          bootbox.alert(data.message, edit_client(id));
                        }
                    }
                  });
                }
              },
              cancel: {
                  label: "Cancel",
                  className: "btn-success"
              }
            },
            message: '<form id="edit-client-form" name="edit-client-form" action="#" method="POST">'+
              '<div class="form-group">'+
                '<input class="form-control" type="hidden" id="clientid" name="clientid" value="'+id+'">'+
                '<label>First Name</label>'+
                '<input class="form-control" type="text" id="fname" name="fname" placeholder="John">'+
                '<label>Last Name</label>'+
                '<input class="form-control" type="text" id="lname" name="lname" placeholder="Smith">'+
              '</div>'+
            '</form>'
          });
        }
        $('.breadcrumb').on( "click", "#add_client", add_client);
        function add_client(){
          bootbox.dialog({
            title : "Add Client",
            buttons : {
              success:{
                label: "Save",
                className: "btn-primary",
                callback: function(){
                  $.ajax({
                   'type':'POST',
                   'dataType ' : 'json',
                   'url':'ajax_pages/add_client_valid.php',
                   beforeSend: function(){ $('.box-body').mask('Please Wait...'); },
                   'data': $('#client-form').serialize(),
                   'async': false,
                   'success':function(data){
                      if(data.status == 'success'){
                        $('.box-body').unmask();
                        bootbox.alert(data.message);
                        document.location = 'client.php';
                      }
                      else {
                        $('.box-body').unmask();
                        bootbox.alert(data.message, add_client);
                      }
                    }
                  });
                }
              },
              cancel:{
                label: "Cancel",
                className: "btn-success"
              }
            },
            message: '<form id="client-form" name="client-form" action="#" method="POST">' +
                '<div class="form-group">'+
                  '<label>First Name</label>'+
                  '<input class="form-control" type="text" id="fname" name="fname" placeholder="John">'+
                  '<label>Last Name</label>'+
                  '<input class="form-control" type="text" id="lname" name="lname" placeholder="Smith">'+
                '</div>'+
                '<div class="form-group">'+
                  '<label>Client Mail Id</label>'+
                  '<input class="form-control" type="text" id="clientmail" name="clientmail" placeholder="example@domain.com">'+
                '</div>'+
              '</form>'
          });
        }
      });
    </script>
  </body>
</html>