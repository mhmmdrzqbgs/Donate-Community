<?php
require_once "koneksi.php";

$nama = $keterangan = "";
$nama_err = $keterangan_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_nama = trim($_POST["nama"]);
    if(empty($input_nama)){
        $nama_err = "Mohon masukkan nama kegiatan.";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $input_nama)){
        $nama_err = "Mohon masukkan Nama kegiatan yang valid.";
    } else{
        $nama = $input_nama;
    }

    $input_keterangan = trim($_POST["keterangan"]);
    if(empty($input_keterangan)){
        $keterangan_err = "Mohon masukkan deskripsi.";
    } else{
        $keterangan = $input_keterangan;
    }

    if(empty($nama_err) && empty($keterangan_err)){
        $sql = "INSERT INTO tb_log (nama, keterangan) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_nama, $param_keterangan);

            $param_nama = $nama;
            $param_keterangan = $keterangan;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Ada yang tidak beres. Silahkan coba lagi nanti.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Tambah Catatan</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambah catatan.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nama_err)) ? 'has-error' : ''; ?>">
                            <label>Kegiatan/Event</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>">
                            <span class="help-block"><?php echo $nama_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($keterangan_err)) ? 'has-error' : ''; ?>">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"><?php echo $keterangan; ?></textarea>
                            <span class="help-block"><?php echo $keterangan_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
