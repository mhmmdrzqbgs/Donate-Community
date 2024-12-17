<?php
require_once "koneksi.php";

$keterangan = "";
$keterangan_err = "";
$gambar_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_keterangan = trim($_POST["keterangan"]);
    if (empty($input_keterangan)) {
        $keterangan_err = "Mohon masukkan deskripsi.";
    } else {
        $keterangan = $input_keterangan;
    }

    $gambar = $_FILES['gambar']['tmp_name'];
    $gambar_name = $_FILES['gambar']['name'];
    $gambar_type = $_FILES['gambar']['type'];
    $gambar_size = $_FILES['gambar']['size'];

    if (!empty($gambar)) {
        $gambar_data = file_get_contents($gambar);

        if ($gambar_data === false) {
            $gambar_err = "Gagal membaca file gambar.";
        } elseif ($gambar_size > 1048576) {
            $gambar_err = "Ukuran gambar terlalu besar. Maksimal 1MB.";
        } elseif ($gambar_type != "image/jpeg") {
            $gambar_err = "Format gambar tidak didukung. Hanya file JPEG yang diperbolehkan.";
        }
    }

    if (empty($keterangan_err) && empty($gambar_err)) {
        $sql_select = "SELECT MAX(id) AS max_id FROM tb_dok";
        $result = mysqli_query($link, $sql_select);
        $row = mysqli_fetch_assoc($result);
        $max_id = $row['max_id'];
        mysqli_free_result($result);

        $new_id = $max_id + 1;

        $sql = "INSERT INTO tb_dok (id, keterangan, gambar) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iss", $param_id, $param_keterangan, $param_gambar);

            $param_id = $new_id;
            $param_keterangan = $keterangan;
            $param_gambar = $gambar_data;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("location: index.php");
                exit();
            } else {
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
        .wrapper {
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
                        <h2>Tambah Dokumentasi</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambah Dokumentasi.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group <?php echo (!empty($gambar_err)) ? 'has-error' : ''; ?>">
                            <label>Dokumentasi</label>
                            <input type="file" name="gambar">
                            <span class="help-block"><?php echo $gambar_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($keterangan_err)) ? 'has-error' : ''; ?>">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"><?php echo $keterangan; ?></textarea>
                            <span class="help-block"><?php echo $keterangan_err; ?></span>
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
