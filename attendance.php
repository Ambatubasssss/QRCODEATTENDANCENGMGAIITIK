<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>QR Code | Attendance Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Styles -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <style>
    body {
      background: #eee;
    }
    #divvideo {
      box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.1);
    }
    .navbar {
      background-color: #4267B2;
      border-radius: 0;
    }
    .navbar-brand, .nav > li > a {
      color: white !important;
    }
    .navbar-right > li > a:hover,
    .nav > li > a:hover {
      background-color: #365899 !important;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><i class="glyphicon glyphicon-qrcode"></i> QR Code Attendance</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
      <li class="active"><a href="attendance.php"><span class="glyphicon glyphicon-calendar"></span> Attendance Report</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>

<!-- Main Container -->
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <?php
      if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger alert-dismissible' style='background:red;color:#fff'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-warning'></i> Error!</h4>
                ".$_SESSION['error']."
              </div>";
        unset($_SESSION['error']);
      }
      if (isset($_SESSION['success'])) {
        echo "<div class='alert alert-success alert-dismissible' style='background:green;color:#fff'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-check'></i> Success!</h4>
                ".$_SESSION['success']."
              </div>";
        unset($_SESSION['success']);
      }
      ?>
    </div>

    <!-- Attendance Table -->
    <div class="col-md-12">
      <div style="border-radius: 5px; padding:10px; background:#fff;" id="divvideo">
        <h4><i class="glyphicon glyphicon-list-alt"></i> Attendance Summary</h4>
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>NAME</th>
              <th>STUDENT ID</th>
              <th>TIME IN</th>
              <th>TIME OUT</th>
              <th>LOGDATE</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $server = "localhost";
            $username = "root";
            $password = "";
            $dbname = "qrdb";

            $conn = new mysqli($server, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM attendance LEFT JOIN student ON attendance.STUDENTID = student.STUDENTID";
            $query = $conn->query($sql);
            while ($row = $query->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['LASTNAME']}, {$row['FIRSTNAME']} {$row['MNAME']}</td>
                      <td>{$row['STUDENTID']}</td>
                      <td>{$row['TIMEIN']}</td>
                      <td>{$row['TIMEOUT']}</td>
                      <td>{$row['LOGDATE']}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
        <button onclick="Export()" class="btn btn-primary"><i class="glyphicon glyphicon-export"></i> Export to Excel</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  function Export() {
    var conf = confirm("Please confirm if you wish to proceed in exporting the attendance to an Excel file");
    if (conf == true) {
      window.open("export.php", '_blank');
    }
  }
</script>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      responsive: true,
      autoWidth: false
    });
  });
</script>

</body>
</html>
