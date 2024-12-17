<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .container { padding-top: 50px; }
        .card { margin-bottom: 20px; }

body::before {
    content: "";
    background-image: url('assets/4897887.jpg');
    opacity: 0.5;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Hallo, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Selamat Datang di Website Kegiatan Sosial</h1>
            <p>Komunitas Sosial</p>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="card">
                    <h3>Bhakti Sosial</h3>
                    <p>
                        <a href="rekap_baksos/index.php" class="btn btn-success">Rekap Bhakti Sosial</a><br/><br/>
                        <a href="jadwal_baksos/index.php" class="btn btn-primary">Jadwal Bhakti Sosial</a>
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card">
                    <h3>Event</h3>
                    <p>
                        <a href="rekap_event/index.php" class="btn btn-success">Rekap Event</a><br/><br/>
                        <a href="jadwal_event/index.php" class="btn btn-primary">Jadwal Event</a>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h3>Lainnya</h3>
                    <p>
                        <a href="log_kegiatan/index.php" class="btn btn-success">Log-book</a><br/><br/>
                        <a href="dokumentasi/index.php" class="btn btn-primary">Dokumentasi</a>
                    </p>
                </div>
            </div>
        </div>
        <br /><br /><br /><br/>
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="reset.php" class="btn btn-warning btn-block">Reset Password</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="logout.php" class="btn btn-danger btn-block">Keluar</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
