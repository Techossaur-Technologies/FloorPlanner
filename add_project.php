<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
createSidebar(2, 6);
?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Add New Assignment</h1>
          <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> Report</li>
            <li><a href="project.php">Assignment</a></li>
            <li class="active">Add Assignment</li>
          </ol>
        </section>
        <section class="content">
        <div class="box">
	        <div class="box-body">
	        	<div class="col-md-4"></div>
	        	<div class="col-md-4">
	        		<form id="project-form" name="project-form" action="#" enctype="multipart/form-data" method="POST">
		                <div class="form-group">
		                  <label>Address* :</label>
		                  <input class="form-control" type="text" id="projectname" name="projectname" placeholder="Type Address">
		                </div>
		                <?php
		                if ($_SESSION['user_type'] == 'Client') {
		                  echo '<input class="form-control" type="hidden" id="client_id" name="client_id" value= "'.$_SESSION['user_id'].'">';
		                } else {
		                  $result1 = mysqli_query($con, "SELECT * FROM `tb_user` WHERE `user_status` != 'Inactive' AND `user_type` = 'Client' ORDER BY `id` ASC");
		                  echo '<div class="form-group"><label>Client Name* :</label><select name="client_id" id="client_id" class= "form-control select2"><option value="" selected = selected>Select Client</option>';
		                  while ($row = mysqli_fetch_array($result1, MYSQLI_BOTH)) {
		                      echo "<option value='" . $row['id'] . "'>" . $row['fname'] .' '. $row['lname'] . "</option>";
		                  }
		                  echo "</select></div>";
		                }
		                ?>
		                <div class="form-group">
		                  <label>Company Name :</label>
		                  <?php

		                  if ($_SESSION['user_type'] == 'Client') {
		                    $result2 = mysqli_query($con, 'SELECT * FROM tb_company WHERE assigned_client = "'.$_SESSION['user_id'].'"');
		                    echo '<select name="company_id" class= "form-control select2">';
			              	echo '<option value="0" selected = selected>None</option>';
			                while ($row = mysqli_fetch_array($result2, MYSQLI_BOTH)) {
			                    echo "<option value='" . $row['company_id'] . "'>" . $row['company_name'] . "</option>";
			                }

		                  } else {
		                  	echo '<select name="company_id" id="company_id" class= "form-control select2">';
		                  	echo '<option value="0" selected = selected>Select Company</option>';
		                  }
		                  echo "</select>";
		                  ?>
		                </div>
		                <div class="form-group">
		                  <label>Attachments* :</label>
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
    <script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
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
            },
            'file[]': {
              required: true,
              accept: "image/*,application/pdf"
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
            },
            'file[]': {
              required: "Select atleast one image",
              accept: "Please upload Images Only"
            }
          }
        });

        $("form[name=project-form]").ajaxForm({
         'type':'POST',
         dataType : 'json',
         'url':'ajax_pages/add_project_valid.php',
         'data': $('#project-form').serialize(),
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
             	$('#project-form').unmask();
                bootbox.alert(resp.message, function(){
                	window.location.href = 'http://floorplanner.techossaur.com/project.php';
                });
              }
              else{
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
      });
    </script>
  </body>
</html>