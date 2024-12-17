<?php
require_once "koneksi.php";

$nama = $waktu = $tanggal = $lokasi = "";
$nama_err = $waktu_err = $tanggal_err = $lokasi_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_nama = trim($_POST["nama"]);
    if (empty($input_nama)) {
        $nama_err = "Mohon masukkan nama kegiatan.";
    } else {
        $nama = $input_nama;
    }

    $input_waktu = trim($_POST["waktu"]);
    if (empty($input_waktu)) {
        $waktu_err = "Mohon masukkan waktu pelaksanaan.";
    } else {
        $waktu_timestamp = strtotime($input_waktu);
        if ($waktu_timestamp === false) {
            $waktu_err = "Mohon masukkan waktu yang valid dengan format HH:MM.";
        } else {
            $waktu = date("H:i", $waktu_timestamp);
        }
    }


    $input_tanggal = trim($_POST["tanggal"]);
    if (empty($input_tanggal)) {
        $tanggal_err = "Mohon masukkan tanggal.";
    } else {
        $parsed_date = date_parse_from_format("Y-m-d", $input_tanggal);
        if ($parsed_date["error_count"] > 0 || !checkdate($parsed_date["month"], $parsed_date["day"], $parsed_date["year"])) {
            $tanggal_err = "Mohon masukkan tanggal yang valid dengan format YYYY-MM-DD.";
        } else {
            $tanggal = $input_tanggal;
        }
    }


    $input_lokasi = trim($_POST["lokasi"]);
    if (empty($input_lokasi)) {
        $lokasi_err = "Mohon masukkan lokasi.";
    } else {
        $lokasi = $input_lokasi;
    }

    if (empty($nama_err) && empty($waktu_err) && empty($tanggal_err) && empty($lokasi_err)) {
        $sql = "UPDATE tb_jadwal2 SET nama = ?, waktu = ?, tanggal = ?, lokasi = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $param_nama, $param_waktu, $param_tanggal, $param_lokasi, $param_id);

            $param_nama = $nama;
            $param_waktu = $waktu;
            $param_tanggal = $tanggal;
            $param_lokasi = $lokasi;
            $param_id = $_GET["id"];

            if (mysqli_stmt_execute($stmt)) {
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

        $sql = "SELECT * FROM tb_jadwal2 WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $nama = $row["nama"];
                    $waktu = $row["waktu"];
                    $tanggal = $row["tanggal"];
                    $lokasi = $row["lokasi"];
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
    <title>Update Jadwal</title>
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
                        <h2>Update Jadwal Event</h2>
                    </div>
                    <p>Silahkan edit dan submit untuk memperbarui jadwal.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET["id"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nama_err)) ? 'has-error' : ''; ?>">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>">
                            <span class="help-block"><?php echo $nama_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($waktu_err)) ? 'has-error' : ''; ?>">
                            <label>Jam</label>
                            <input type="time" name="waktu" class="form-control" value="<?php echo $waktu; ?>">
                            <span class="help-block"><?php echo $waktu_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($tanggal_err)) ? 'has-error' : ''; ?>">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>">
                            <span class="help-block"><?php echo $tanggal_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($lokasi_err)) ? 'has-error' : ''; ?>">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="<?php echo $lokasi; ?>">
                            <span class="help-block"><?php echo $lokasi_err;?></span>
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
