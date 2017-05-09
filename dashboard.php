<?php
include('includes/header.php');
//createMenu(0, 1);
$date_array = array();
// $order_array = array();

createSidebar(0,1);

for ($i = 0 ; $i < 15 ; $i++) {
  $date_array[] = date("d-M", strtotime('-'.$i.' days'));
  //Run Query
}
if($_SESSION['user_type'] == 'Admin'){
  $sql1 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record`");
  $arr1 = mysqli_fetch_array($sql1);
  $sql2 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record`");
  $arr2 = mysqli_fetch_array($sql2);
  $sql3 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record` WHERE `project_status` = 'Completed'");
  $arr3 = mysqli_fetch_array($sql3);
  $sql4 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record` WHERE `project_status` = 'Completed'");
  $arr4 = mysqli_fetch_array($sql4);
  $sql5 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record` WHERE `project_status` = 'Pending'");
  $arr5 = mysqli_fetch_array($sql5);
  $sql6 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record` WHERE `project_status` = 'Pending'");
  $arr6 = mysqli_fetch_array($sql6);
} else {
  $sql1 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record` WHERE client_id = '".$_SESSION['user_id']."' ");
  $arr1 = mysqli_fetch_array($sql1);
  $sql2 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record` WHERE client_id = '".$_SESSION['user_id']."'");
  $arr2 = mysqli_fetch_array($sql2);
  $sql3 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record` WHERE `project_status` = 'Completed' AND client_id = '".$_SESSION['user_id']."'");
  $arr3 = mysqli_fetch_array($sql3);
  $sql4 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record` WHERE `project_status` = 'Completed' AND client_id = '".$_SESSION['user_id']."'");
  $arr4 = mysqli_fetch_array($sql4);
  $sql5 = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PRJ FROM `tb_record` WHERE `project_status` = 'Pending' AND client_id = '".$_SESSION['user_id']."'");
  $arr5 = mysqli_fetch_array($sql5);
  $sql6 = mysqli_query($con, "SELECT COALESCE(SUM(`blueprint`), 0) AS BLP FROM `tb_record` WHERE `project_status` = 'Pending' AND client_id = '".$_SESSION['user_id']."'");
  $arr6 = mysqli_fetch_array($sql6);
}


?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-lg-4">
            <div class="small-box bg-aqua">
              <div class="col-md-6">
                <h3><?php echo $arr1['PRJ']; ?></h3>
                <p>Projects</p>
              </div>
              <div class="col-md-6">
                <h3><?php echo $arr2['BLP']; ?></h3>
                <p>Blueprints</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <div class="small-box-footer">Total</div>
            </div>
          </div>
          <div class="col-lg-4">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="col-md-6">
                <h3><?php echo $arr3['PRJ']; ?></h3>
                <p>Projects</p>
              </div>
              <div class="col-md-6">
                <h3><?php echo $arr4['BLP']; ?></h3>
                <p>Blueprints</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <div class="small-box-footer">Completed</div>
            </div>
          </div><!-- ./col -->
          <div class="col-lg-4">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="col-md-6">
                <h3><?php echo $arr5['PRJ']; ?></h3>
                <p>Projects</p>
              </div>
              <div class="col-md-6">
                <h3><?php echo $arr6['BLP']; ?></h3>
                <p>Blueprints</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <div class="small-box-footer">Pending</div>
            </div>
          </div><!-- ./col -->
        </div>
        <div class="box-body">
        	<div class="row">
        		<div id="container"></div>
        	</div>
        </div>
        <div class="box-body">
        	<div class="row">
        		<div id="container1"></div>
        	</div>
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
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script type="text/javascript">
  $(function () {

    $('#container').highcharts({
        chart: {
            type: 'column'
        },

        title: {
            text: 'Project & Blueprint Analysis (Last 15 Days)'
        },
        xAxis: {
            categories: [<?php foreach ($date_array as $value) {
              echo "'$value',";
            } ?>]
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Number of Projects/Blueprints'
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: [
        <?php include('ajax_pages/datachart1.php'); ?>
        ]
    });
	$('#container1').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Yearly Analysis'
            },
            xAxis: {
                categories: [
                    'January','February','March','April','May','June','July','August','September','October','November','December']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Counts'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                enabled: false
              },
            colors: [
               '#2f7ed8',
               '#0d233a',
               '#910000',
               '#91e8e1'
            ],
            series: [<?php include('ajax_pages/datachart2.php'); ?>]
  });
});
  </script>
</body>
</html>