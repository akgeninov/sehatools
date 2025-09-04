<?php
    session_start();
    session_unset();      
    session_destroy();     

    echo "<script>
            alert('Terima kasih!');
            window.location='start.php';
        </script>";
    exit;
?>