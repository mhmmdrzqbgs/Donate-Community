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

    $sql_last_id = "SELECT id FROM tb_jadwal1 ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($link, $sql_last_id);
    $row = mysqli_fetch_assoc($result);
    $last_id = $row['id'];

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

    if (empty($nama_err) && empty($tanggal_err) && empty($waktu_err) && empty($lokasi_err)) {
        $sql = "INSERT INTO tb_jadwal1 (id, nama, waktu, tanggal, lokasi) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            $param_id = $last_id + 1;
            mysqli_stmt_bind_param($stmt, "issss", $param_id, $param_nama, $param_waktu, $param_tanggal, $param_lokasi);

            $param_nama = $nama;
            $param_waktu = $waktu;
            $param_tanggal = $tanggal;
            $param_lokasi = $lokasi;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                header("location: index.php");
                exit();
            } else {
                echo "Ups! Ada yang tidak beres. Silahkan coba lagi nanti.";
            }
        }

        mysqli_close($link);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal</title>
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
                        <h2>Tambah Jadwal Bhakti Sosial</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambahkan jadwal.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
