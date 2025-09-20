<?php
    session_start();
    include "../connect/connection.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/shortcut.png">
    <title>Login</title>
</head>
<body>
    <div class="hero">
        <h2>ADMIN</h2>
        <div class="form-box">
            <div class="button-box">
                <!-- <div id="btn"></div> -->
                <button type="button" class="toggle-btn active" onclick="masuk()">Masuk</button>
                <button type="button" class="toggle-btn" onclick="daftar()">Daftar</button>
            </div>
            <form method="post" id="masuk" class="input-group">
                <input type="text" name="in-username" class="kolom" placeholder="Username" required>
                <input type="password" name="in-password" class="kolom" placeholder="Password" required>
                <!-- <span><i class="fa-solid fa-eye"></i></span> -->
                <button type="submit" class="submit" name="btn-masuk">Masuk</button>
            </form>
            <form id="daftar" method="POST" action="" class="input-group">
                <input type="text" class="kolom" name="re-name" placeholder="Nama" required>
                <input type="email" class="kolom" name="re-email" placeholder="Email" required>
                <input type="text" class="kolom" name="re-username" placeholder="Username" required>
                <input type="password" class="kolom" name="re-password" placeholder="Password" required>
                <input type="password" class="kolom" name="re-password2" placeholder="Konfirmasi Password" required>
                <button type="submit" class="submit" name="btn-daftar">Daftar</button>
            </form>
        </div>
    </div>
    <script>
        var x = document.getElementById("masuk");
        var y = document.getElementById("daftar");

        function daftar(){
            x.style.left = "-400px";
            y.style.left = "50px";

            document.querySelectorAll(".toggle-btn").forEach(btn => btn.classList.remove("active"));
  document.querySelector(".toggle-btn:nth-child(2)").classList.add("active");
        }
        function masuk(){
            x.style.left = "50px";
            y.style.left = "450px";

             document.querySelectorAll(".toggle-btn").forEach(btn => btn.classList.remove("active"));
  document.querySelector(".toggle-btn:nth-child(1)").classList.add("active");
        }
    </script>
            <?php
                if(isset($_POST['btn-masuk'])){
                    $inusername = $_POST['in-username'];
                    $inpassword = $_POST['in-password'];
                    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$inusername'");
                    if(mysqli_num_rows($query) == 1){
                        $row = mysqli_fetch_assoc($query);
                        if(password_verify($inpassword, $row['password'])){
                            $_SESSION['userweb'] = $inusername;
                            echo "<script> alert('Selamat datang $_SESSION[userweb] !'); window.location='home.php'; </script>";
                            exit;
                        } else {
                            echo "<script> alert('Maaf, username dan password salah') </script>";
                        }
                    }
                    exit;
                }
                if(isset($_POST['btn-daftar'])){
                    $name = $_POST['re-name'];
                    $email = $_POST['re-email'];
                    $username = $_POST['re-username'];
                    $password = $_POST['re-password'];
                    $password2 = $_POST['re-password2'];
                    if($password !== $password2){
                        echo "<script> alert('Konfirmasi password tidak sesuai') </script>";
                    } else {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $query = mysqli_query($koneksi, "INSERT INTO admin VALUES('', '$name', '$email', '$username', '$passwordHash')");
                        if($query){
                            echo "<script> alert('Berhasil daftar') </script>";
                        }
                    }
                    exit;
                }
            ?>
</body>
</html>