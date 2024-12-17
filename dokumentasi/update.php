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
        } elseif (
            $gambar_type != "image/jpeg"
        ) {
            $gambar_err = "Format gambar tidak didukung. Hanya file JPEG yang diperbolehkan.";
        }
    }

    if (empty($keterangan_err)) {
        $sql = "UPDATE tb_dok SET keterangan = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_keterangan, $param_id);

            $param_keterangan = $keterangan;
            $param_id = $_GET["id"];

            if (mysqli_stmt_execute($stmt)) {
                if (!empty($gambar_data)) {
                    // Proses file gambar baru
                    $sql_update_gambar = "UPDATE tb_dok SET gambar = ? WHERE id = ?";
                    if ($stmt_update_gambar = mysqli_prepare($link, $sql_update_gambar)) {
                        mysqli_stmt_bind_param($stmt_update_gambar, "bi", $gambar_data, $param_id);
                        mysqli_stmt_execute($stmt_update_gambar);
                    }
                }

                header("location: index.php");
                exit();
            } else {
                echo "Ada yang tidak beres. Silahkan coba lagi nanti.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM tb_dok WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $keterangan = $row["keterangan"];

                } else {
                    header("location: ../error.php");
                    exit();
                }

            } else {
                echo "Ups! Ada yang tidak beres. Silahkan coba lagi nanti.";
            }
        }

        mysqli_stmt_close($stmt);

        mysqli_close($link);
    } else {
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
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
                        <h2>Update Dokumentasi</h2>
                    </div>
                    <p>Silahkan edit dan submit untuk memperbarui catatan.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET["id"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" name="gambar">
                            <span class="help-block"><?php echo $gambar_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($keterangan_err)) ? 'has-error' : ''; ?>">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"><?php echo $keterangan; ?></textarea>
                            <span class="help-block"><?php echo $keterangan_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Perbarui">
                        <a href="index.php" class="btn btn-default">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
