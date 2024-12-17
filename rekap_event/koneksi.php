<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_NAME', 'db_curd');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, '', DB_NAME);

if($link === false){
    die("ERROR: Database tidak terkoneksi. " . mysqli_connect_error());
}
?>
