<?php
include('includes/header.php');
include('includes/all_functions.php');
if($_SESSION['user_type'] != 'Admin'){
  header("Location: dashboard.php");
  exit();
}
createSidebar(1, 2);
?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <section class="content-header">
        <h1>Email Template</h1>
        <ol class="breadcrumb">
          <li><i class="fa fa-gear"></i> Settings</li>
          <li class="active">Email Template</li>
        </ol>
      </section>
      <section class="content">
        <div class="box">
          <div class="box-body">
            <table class="table table-hover" id="example">
              <thead>
                  <tr>
                      <th class="span3" data-class="expand">
                          <span class="line"></span>ID#
                      </th>
                    <th class="span3" data-hide="phone,tablet">
                          <span class="line"></span>Email Type
                      </th>
                      <th class="span3" data-hide="phone">
                          <span class="line"></span>Description
                      </th>
                      <th class="span3">
                          <span class="line"></span>Recepient Type
                      </th>
                      <th class="span3">
                          <span class="line"></span>Handlinger
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php
                  $qry = "SELECT id,tpltype,mailtype,description,mailfrom FROM email_templates ORDER BY id ASC";
                  $qrs = @mysqli_query($con, $qry);
                  $total = @mysqli_num_rows($qrs);
                  if($total>0){
                    while($tmp = @mysqli_fetch_assoc($qrs)){
                      $id = encryptorDecryptor('encrypt', $tmp['id']);
                    ?>
                    <tr>
                       <td class="description"><?php echo $tmp['id'];?></td>
                       <td class="description"><?php echo stripslashes($tmp['tpltype']);?></td>
                       <td class="description"><?php echo $tmp['description'];?></td>
                       <td class="description"><?php echo stripslashes($tmp['mailtype']);?></td>
                       <td>
                       <ul class="actions">
                        <a href="edit-email-template.php?id=<?php echo $id;?>"><i class="fa fa-cog"></i></a>
                       </ul>
                       </td>
                    </tr>

                    <?php }
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
        <footer class="main-footer">
          <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Coredigita</a>.</strong> All rights reserved.
        </footer>
      </section>
    </div><!-- ./wrapper -->

  <!-- jQuery 2.1.4 -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugin/jQueryUI/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="plugin/fastclick/fastclick.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script type="text/javascript">
  $(function () {

  });
  </script>
</body>
</html>