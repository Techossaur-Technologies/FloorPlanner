<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
createSidebar(2, 14);
?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.1/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Assignment Details</h1>
          <ol class="breadcrumb">
            <a href="add_project.php">
            <input type="submit" name="submit" value="Add Assignment" style="float: right;" class="btn btn-primary"/>
          </a>
          </ol>
        </section>
        <section class="content">
        <div class="box">
          <div class="box-body">
            <table id="example1" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Assignment Id</th>
                  <th>Address</th>
                  <?php
                  if ($_SESSION['user_type'] == 'Admin') {
                    echo "<th>Client</th>";
                  }
                  ?>
                  <th>Company</th>
                  <th>Blueprint</th>
                  <th>Bolig App ID</th>
                  <th>Status</th>
                  <th>Created On</th>
                  <th style="width: 14%;">Action</th>
                </tr>
              </thead>
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
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.1/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>
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

    var table = $('#example1').DataTable({
          "processing": true,
          "serverSide": true,
          fixedHeader: true,
          responsive: true,
          // "order": [[ 5, "desc" ]],
          dom: 'lBfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
          "columnDefs": [ {
            "targets": 6,
            "createdCell": function (td, cellData, rowData, row, col) {
              if ( cellData == 'Pending' ) {
                $(td).css('color', 'red');
              } else {
                $(td).css('color', 'green');
              }
            }
          } ],
          "iDisplayLength": 25,
          "ajax":{
            url :"ajax_pages/projectdata.php", // json datasource
            type: "post"
          }
    });

    $('#example1').on( "click", ".fa-trash", function() {
      var id = $(this).attr('id');
      var parent = $(this).parent().parent();
      bootbox.confirm("Are you sure you want to delete this report #"+$(this).attr('value')+"?", function(result){
        if (result) {
          $.ajax({
             type: "POST",
             url: "ajax_pages/delete_row.php",
             dataType : 'json',
             data: {data_id: id},
             cache: false,
             success:function(data){
               if(data.status == 'success'){
                  parent.fadeOut('slow', function() {
                    $(this).remove();
                    bootbox.alert(data.message);
                  });
                } else {
                  bootbox.alert(data.message);
                }
              }
           });
        }
      });
    });
    $('#example1').on( "click", ".fa-magnet", function() {
      var id = $(this).attr('id');
      bootbox.dialog({
                title: "Please provide assignment ID of Boligofotografer App.",
                message: '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-4 control-label" for="name">Assignment ID</label> ' +
                    '<div class="col-md-4"> ' +
                    '<input id="app_id" name="app_id" type="number" placeholder="123" class="form-control input-md"> ' +
                    '</div> ' +
                    '</form> </div>  </div>',
                buttons: {
                    success: {
                        label: "Post",
                        className: "btn-success",
                        callback: function () {
                            var app_id = $('#app_id').val();
                            $('#example1').mask('Please Wait...');
                            $.ajax({
                               type: "POST",
                               url: "ajax_pages/post_curl.php",
                               dataType : 'json',
                               data: {data_id: id, application_id: app_id},
                               cache: false,
                               success:function(data){
                                 if(data.status == 'success'){
                                    bootbox.alert(data.message);
                                    $('#example1').unmask();
                                    location.reload();
                                  } else {
                                    bootbox.alert(data.message);
                                    $('#example1').unmask();
                                    location.reload();
                                  }
                                }
                            });
                        }
                    }
                }
            });



      // bootbox.confirm("Are you sure you want to post this report #"+$(this).attr('value')+"?", function(result){
      //   if (result) {
      //     $.ajax({
      //        type: "POST",
      //        url: "ajax_pages/post_curl.php",
      //        dataType : 'json',
      //        data: {data_id: id},
      //        cache: false,
      //        success:function(data){
      //          if(data.status == 'success'){
      //             bootbox.alert(data.message);
      //           } else {
      //             bootbox.alert(data.message);
      //           }
      //         }
      //      });
      //   }
      // });
    });
    $('#example1').on( "click", ".fa-check", function() {
      var id = $(this).attr('id');
      bootbox.confirm("Are you sure you have completed this project #"+$(this).attr('value')+"?", function(result){
        if (result) {
          $.ajax({
             type: "POST",
             url: "ajax_pages/pendingtocomplete_project.php",
             dataType : 'json',
             data: {data_id: id},
             cache: false,
             'async': true,
             success:function(data){
               if(data.status == 'success'){
                  $('#example1').dataTable().fnUpdate('Completed' ,  $('#example1 tr#'+id)[0],5 );
                  bootbox.alert(data.message);
                } else {
                  bootbox.alert(data.message);
                }
              }
           });
        }
      });
    });
    $('#example1').on( "click", ".fa-edit", function() {
      var id = $(this).attr('id');
      window.location = 'edit_project.php?id='+id;
    });
    $('table#delTable tr:odd').css('background',' #FFFFFF');
  });

</script>
  </body>
</html>