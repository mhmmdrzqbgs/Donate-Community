<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dokmentasi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="page-header clearfix">
                            <h2 class="pull-left">Dokumentasi Kegiatan<br/>Komunitas Sosial</h2><br/><br/>
                            <br/><br/><p>Penanggung Jawab Tabel</p>
                        <p>Nama : Muhammad Rizqi Bagus Trianto</p>
                        <p>NIM : 202251088</p>
                            <a href="tambah.php" class="btn btn-success pull-right">Tambah Dokmentasi</a>
                        </div>
                        <?php
                        require_once "koneksi.php";

                        $sql = "SELECT * FROM tb_dok";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>No</th>";
                                            echo "<th>Dokumentasi</th>";
                                            echo "<th>Keterangan</th>";
                                            echo "<th>Pengaturan</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td><img src='cetak_gambar.php?id=" . $row['id'] . "' alt='Gambar'></td>";
                                            echo "<td>" . $row['keterangan'] . "</td>";
                                            echo "<td>";                                
                                                echo "<a href='aksi.php?id=". $row['id'] ."' title='Detail' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                                echo "<a href='update.php?id=". $row['id'] ."' title='Update' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                                echo "<a href='hapus.php?id=". $row['id'] ."' title='Hapus' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                echo "</table>";
                                mysqli_free_result($result);
                            } else{
                                echo "<p class='lead'><em>Tidak ada data yang ditemukan.</em></p>";
                            }
                        } else{
                            echo "ERROR: Tidak dapat memproses $sql. " . mysqli_error($link);
                        }

                        mysqli_close($link);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p>
        <a href="../reset.php" class="btn btn-warning pull-left">Reset Password</a>
        <a href="../dashboard.php" class="btn btn-info pull-right">Dashboard</a><br/><br/>
        <a href="../logout.php" class="btn btn-danger pull-left">Keluar</a>
    </p>
</body>
</html>
