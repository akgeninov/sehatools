<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "sehatools";

    // $host = "sql312.infinityfree.com";
    // $user = "if0_39801734";
    // $pass = "akgenini0911";
    // $db = "if0_39801734_sehatools";

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
