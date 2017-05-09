<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include('includes/header.php');
include('includes/all_functions.php');
createSidebar(2, 14);
$prj_id      = encryptorDecryptor('decrypt', $_GET['id']);
$prj_details = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id` WHERE `prj_id`= '".$prj_id."'")) ;

function raw_im($time) {
  echo '<li><i class="fa fa-camera bg-purple"></i><div class="timeline-item"><span class="time"><i class="fa fa-clock-o"></i>'.$time['time'].'</span><h3 class="timeline-header">Raw Blueprints</h3><div class="timeline-body">';
  $raw_im = explode(',', $time['image_name']);
  foreach ($raw_im as $value) {
    echo '<a class="fancybox" rel="group" href="uploads/'.$time['image_path'].$value.'"><img class="margin" src="timthumb.php?src=uploads/'.$time['image_path'].$value.'&h=100&w=150&q=30 " alt=""></a> <a class="btn btn-info fa fa-download" href="uploads/'.$time['image_path'].$value.'" download></a>';
  }
  echo "<br>".$time['comments'];
  echo '</div></div></li>';
}
function final_im($time) {
  echo '<li><i class="fa fa-envelope bg-blue"></i><div class="timeline-item"><span class="time"><i class="fa fa-clock-o"></i>'.$time['time'].'</span><h3 class="timeline-header">Output Blueprints</h3><div class="timeline-body">';
  $raw_im = explode(',', $time['image_name']);
  foreach ($raw_im as $value) {
    echo '<a class="fancybox" rel="group" href="uploads/'.$time['image_path'].$value.'"><img class="margin" src="timthumb.php?src=uploads/'.$time['image_path'].$value.'&h=100&w=150&q=30 " alt=""></a> <a class="btn btn-info fa fa-download" href="uploads/'.$time['image_path'].$value.'" download></a>';
  }
  echo "<br>".$time['comments'];
  echo '</div></div></li>';
}

function cmms($time, $con) {
  $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT fname, lname from tb_user WHERE id = '".$time['user']."'"));
  echo '<li><i class="fa fa-comments bg-yellow"></i><div class="timeline-item"><span class="time"><i class="fa fa-clock-o"></i>'.$time['time'].'</span><h3 class="timeline-header">Commented By '.$user['fname'].' '.$user['lname'].'</h3><div class="timeline-body">';
  echo $time['comments'].'<br>';
  if ($time['image_name'] != '') {
    $raw_im = explode(',', $time['image_name']);
    foreach ($raw_im as $value) {
      $ext = pathinfo($value, PATHINFO_EXTENSION);
      if ($ext == 'pdf') {
        echo '<a href="uploads/'.$time['image_path'].$value.'" target = "_blank"><img class="margin" src= "logo/img/pdf.png"></img></a>';
      } else {
        echo '<a class="fancybox" rel="group" href="uploads/'.$time['image_path'].$value.'"><img class="margin" src="timthumb.php?src=uploads/'.$time['image_path'].$value.'&h=100&w=150&q=30 " alt=""></a> <a class="btn btn-info fa fa-download" href="uploads/'.$time['image_path'].$value.'" download></a>';
      }
    }
  }
  echo '</div></div></li>';
}

function timeline($con, $prj_id){
  $date_qry = mysqli_query($con,"SELECT date_format(comment_creation_time, '%Y-%m-%d') AS date FROM `tb_comment` WHERE project_id = '".$prj_id."' GROUP BY date ORDER BY `comment_creation_time` DESC ");
  while ($date = mysqli_fetch_assoc($date_qry)) {
    echo '<li class="time-label"><span class="bg-red">'.$date['date'].'</span></li>';
    //echo "SELECT *, date_format(comment_creation_time, '%Y-%m-%d') AS date, date_format(comment_creation_time, '%H:%i:%s') AS time FROM `tb_comment` WHERE date_format(comment_creation_time, '%Y-%m-%d') = '".$date['date']."' AND  project_id = '".$prj_id."' ORDER BY `comment_creation_time` DESC ";
    $time_qry = mysqli_query($con, "SELECT *, date_format(comment_creation_time, '%Y-%m-%d') AS date, date_format(comment_creation_time, '%H:%i:%s') AS time FROM `tb_comment` WHERE date_format(comment_creation_time, '%Y-%m-%d') = '".$date['date']."' AND  project_id = '".$prj_id."' ORDER BY `comment_creation_time` DESC ");
    while ($time = mysqli_fetch_assoc($time_qry)) {
      switch ($time['comment_type']) {
        case "raw":
            raw_im($time);
            break;
        case "final":
            final_im($time);
            break;
        case "cmt":
        	cmms($time, $con);
        	break;
        default:
            break;
      }
    }
  }
}
?>
<link rel="stylesheet" href="plugin/jquery_loadmask/jquery.loadmask.css">
<link rel="stylesheet" href="plugin/fancybox/jquery.fancybox.css">

<style type="text/css">
  .fa-download{
    margin-left: 5px;
  }
</style>

      
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Assignment Description</h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-dashboard"></i> Report</li>
      <li><a href="project.php">Assignment</a></li>
      <li class="active">Assignment Details</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title" style="text-align: center;">Assignment ID# <?php echo $prj_id;?></h3>
      </div>
      <div class="box-body">
        <!-- Page content goes here -->
        <div class="row">
          <div class="box-body">
            <table class="table table-bordered">
              <tr>
                <td>
                  <span class="pull-left">Address:</span>
                  <span class="pull-right"><?php echo $prj_details['project_name']; ?></span>
                </td>
                <td>
                  <span class="pull-left">No. of Blueprints:</span>
                  <span class="pull-right"><?php echo $prj_details['blueprint']; ?></span>
                </td>
                <td>
                  <span class="pull-left">Client Name:</span>
                  <span class="pull-right"><?php echo $prj_details['fname'].' '.$prj_details['lname']; ?></span>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="pull-left">Created On:</span>
                  <span class="pull-right"><?php echo $prj_details['creation_time']; ?></span>
                </td>
                <td>
                  <span class="pull-left">Company Name:</span>
                  <span class="pull-right"><?php echo $prj_details['company_name']; ?></span>
                </td>
                <td>
                  <span class="pull-left">Status:</span>
                  <span class="pull-right"><?php echo $prj_details['project_status']; ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div><!-- /.row -->
        <div class="box-body" align="right">
          <!-- Edit Option -->
          <a class="btn btn-default" target="_blank" href="edit_project.php?id=<?php echo encryptorDecryptor('encrypt', $prj_details['prj_id']); ?>">Edit</a>

          <!-- Add Comments -->
          <button class="btn btn-default" data-toggle="modal" data-target="#addcomments">Add Comments</button>
          <!-- Modal -->
          <div class="modal fade" id="addcomments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="left">
            <div class="modal-dialog">
              <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                      Add Comments 
                    </h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body"> 
                  <form role="form" name="comment_submit_form">
                    <div class="form-group">
                      <input type="hidden" class="form-control" name="prj_id" value="<?php echo $_GET['id']; ?>">
                    </div>
                    <div class="form-group">
                      <label>Comments *</label>
                      <textarea name="comments" style="height: 100px; width: 570px"></textarea>
                    </div>
                    <div class="form-group">
                      <label>Attachments (Optional)</label>
                      <input name="file[]" id="file" type="file" multiple="multiple" accept="image/*,application/pdf" class="span10">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>  
                </div>
                <!-- Modal Footer -->
                <!-- <div class="modal-footer">
                </div> -->
              </div>
            </div>
          </div>
          <?php 
            if ($_SESSION['user_type'] != 'Client') {
              echo '<button class="btn btn-success" data-toggle="modal" data-target="#addoutputblueprints">Add Ouput Blueprints</button>
          <div class="modal fade" id="addoutputblueprints" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="left">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                      Add Ouput Blueprints 
                    </h4>
                </div>
                <div class="modal-body"> 
                  <form role="form" name="fp_submit_form">
                    <div class="form-group">
                      <input type="hidden" class="form-control" id="prj_id" name="prj_id" value="'.$_GET["id"].'">
                    </div>
                    <div class="form-group">
                      <input type="hidden" class="form-control" id="company_id" name="company_id" value="'.encryptorDecryptor('encrypt',$prj_details['company_id']).'">
                    </div>
                    <div class="form-group">
                      <label>Attachments *</label>
                      <input name="file[]" id="file" type="file" multiple="multiple" accept="image/*" class="span10">
                      <input name="collage" id="collage" type="checkbox" class="i-checks"> Make Collage
                    </div>
                    <div class="form-group">
                      <label>Comments (Optional)</label>
                      <textarea id="comments" name="comments" style="height: 100px; width: 570px"></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>  
                </div>
              </div>
            </div>
          </div>';
            }
          ?>
          
        </div>
        <div class="row"> 
          <div class="tab-pane" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">
              <!-- timeline time label -->
              <?php
                timeline($con, $prj_id);
              ?>
              <li>
                <i class="fa fa-clock-o bg-gray"></i>
              </li>
            </ul>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<footer class="main-footer">
  <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Coredigita</a>.</strong> All rights reserved.
</footer>
<!-- </div>./wrapper -->

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
<script type="text/javascript" src= "plugin/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
    $("form[name=fp_submit_form]").validate({
      rules: {
        prj_id: {
          required: true
        },
        'file[]': {
          required: true,
          accept: "image/*"
        }
      },
      messages: {
        prj_id: {
          required: "Invalid!"
        },
        'file[]': {
          required: "Select atleast one image",
          accept: "Please upload Images Only"
        }
      }
    });
    $("form[name=comment_submit_form]").validate({
      rules: {
        prj_id: {
          required: true
        },
        comments: {
          required: true
        }
      },
      messages: {
        prj_id: {
          required: "Invalid!"
        },
        comments: {
          required: "Please add comments"
        }
      }
    });
    $(".fancybox").fancybox();
    $("form[name=fp_submit_form]").ajaxForm({
     'type':'POST',
     dataType : 'json',
     'url':'ajax_pages/add_final_bp.php',
     'data': $('form[name=fp_submit_form]').serialize(),
     'beforeSend': function() {
        var percentVal = '0%';
        $('form[name=fp_submit_form]').mask('Loading '+percentVal+'');
      },
      'uploadProgress': function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        $('.loadmask-msg').empty();
        $('.loadmask-msg').html('<div>Loading '+percentVal+'</div>');
      },
     'success':function(resp){
         if(resp.data == 'success'){
              bootbox.alert(resp.message, function(){
                $('form[name=fp_submit_form]').unmask();
                location.reload();
              });
          }
          else{
              bootbox.alert(resp.message);
          }
     }
    });
    $("form[name=comment_submit_form]").ajaxForm({
     'type':'POST',
     dataType : 'json',
     'url':'ajax_pages/comment_submit_form.php',
     'data': $('form[name=comment_submit_form]').serialize(),
     'beforeSend': function() {
        var percentVal = '0%';
        $('form[name=comment_submit_form]').mask('Loading '+percentVal+'');
      },
      'uploadProgress': function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        $('.loadmask-msg').empty();
        $('.loadmask-msg').html('<div>Loading '+percentVal+'</div>');
      },
     'success':function(resp){
         if(resp.data == 'success'){
              bootbox.alert(resp.message, function(){
                $('form[name=comment_submit_form]').unmask();
                location.reload();
              });   
          }
          else{
              bootbox.alert(resp.message);
          }
     }
    });
  });
</script>
  </body>
</html>