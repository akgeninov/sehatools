<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "sehatools";

    $koneksi = mysqli_connect($host, $user, $pass);
        if($koneksi){
            $buka = mysqli_select_db($koneksi,$db);
            echo "";
            if (!$koneksi){
                echo"Koneksi gagal";
            }
        } else {
            echo "Database tidak terhubung";
        }
?>
