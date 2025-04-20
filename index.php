<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /QRCodeAttendance/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>QR Code | Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  
  <!-- Styles -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li><a href="attendance.php"><span class="glyphicon glyphicon-calendar"></span> Attendance Report</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Scanner + Form -->
  <div class="container">
    <div class="row">
      <div class="col-md-4" style="padding:10px;background:#fff;border-radius: 5px;" id="divvideo">
        <center><p class="login-box-msg"><i class="glyphicon glyphicon-camera"></i> TAP HERE</p></center>
        <video id="preview" width="100%" height="50%" style="border-radius:10px;"></video>
        <br><br>
        <?php
        if (isset($_SESSION['error'])) {
          echo "<div class='alert alert-danger alert-dismissible' style='background:red;color:#fff'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <h4><i class='icon fa fa-warning'></i> Error!</h4>
                  ".$_SESSION['error']."
                </div>";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "<div class='alert alert-success alert-dismissible' style='background:green;color:#fff'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>
                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                  ".$_SESSION['success']."
                </div>";
          unset($_SESSION['success']);
        }
        ?>
      </div>

      <!-- Form and Attendance Table -->
      <div class="col-md-8">
        <form action="CheckInOut.php" method="post" class="form-horizontal" style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
          <i class="glyphicon glyphicon-qrcode"></i> <label>SCAN QR CODE</label> <p id="time"></p>
          <input type="text" name="studentID" id="text" placeholder="scan qrcode" class="form-control" autofocus>
        </form>

        <div style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
          <table id="example1" class="table table-bordered">
            <thead>
              <tr>
                <td>NAME</td>
                <td>STUDENT ID</td>
                <td>TIME IN</td>
                <td>TIME OUT</td>
                <td>LOGDATE</td>
              </tr>
            </thead>
            <tbody>
              <?php
              $server = "localhost";
              $username = "root";
              $password = "";
              $dbname = "qrdb";
              $conn = new mysqli($server, $username, $password, $dbname);
              $date = date('Y-m-d');

              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }

              $sql = "SELECT * FROM attendance LEFT JOIN student ON attendance.STUDENTID=student.STUDENTID WHERE LOGDATE='$date'";
              $query = $conn->query($sql);

              while ($row = $query->fetch_assoc()) {
                echo "<tr>
                        <td>".$row['LASTNAME'].', '.$row['FIRSTNAME'].' '.$row['MNAME']."</td>
                        <td>".$row['STUDENTID']."</td>
                        <td>".$row['TIMEIN']."</td>
                        <td>".$row['TIMEOUT']."</td>
                        <td>".$row['LOGDATE']."</td>
                      </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        alert('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });

    scanner.addListener('scan', function (c) {
      document.getElementById('text').value = c;
      document.forms[0].submit();
    });

    var timestamp = '<?=time();?>';
    function updateTime() {
      $('#time').html(Date(timestamp));
      timestamp++;
    }
    $(function () {
      setInterval(updateTime, 1000);
    });
  </script>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script>
    $(function () {
      $("#example1").DataTable({ responsive: true, autoWidth: false });
    });
  </script>
</body>
</html>
