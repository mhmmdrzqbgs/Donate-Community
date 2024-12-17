<?php
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    require_once "koneksi.php";

    $id = trim($_POST["id"]);

    $sql_delete = "DELETE FROM tb_log WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            // Mengatur ulang nomor ID
            $sql_alter = "ALTER TABLE tb_log AUTO_INCREMENT = 1";
            if (mysqli_query($link, $sql_alter)) {
                // Mendapatkan data ID yang tersisa setelah penghapusan
                $sql_select = "SELECT id FROM tb_log ORDER BY id ASC";
                $result = mysqli_query($link, $sql_select);
                if ($result) {
                    $new_id = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $old_id = $row['id'];
                        $sql_update = "UPDATE tb_log SET id = ? WHERE id = ?";
                        if ($stmt = mysqli_prepare($link, $sql_update)) {
                            mysqli_stmt_bind_param($stmt, "ii", $new_id, $old_id);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
                        $new_id++;
                    }
                    mysqli_free_result($result);
                }

                header("location: index.php");
                exit();
            } else {
                echo "Ups! Ada yang tidak beres. Silahkan coba lagi nanti.";
            }
        } else {
            echo "Ups! Ada yang tidak beres. Silahkan coba lagi nanti.";
        }
    }

    mysqli_close($link);
} else {
    if (empty(trim($_GET["id"]))) {
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus Rekap</title>
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
                        <h1>Hapus Rekap Kegiatan</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Apakah anda yakin untuk menghapus rekap kegiatan ini?</p><br>
                            <p>
                                <input type="submit" value="Yakin" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">Tidak</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
