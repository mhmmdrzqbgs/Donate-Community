<?php
require_once "koneksi.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "SELECT gambar FROM tb_dok WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $gambar);

        if(mysqli_stmt_fetch($stmt)){
            header("Content-type: jpeg");
            echo $gambar;
        }
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
