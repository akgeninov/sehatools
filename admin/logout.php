<?php
    session_start();
    session_destroy();
    echo "<script> alert('Terima kasih!'); window.location='start.php';</script>";
?>