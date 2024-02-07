<!-- <?php
    session_start();
    include "../connect/koneksi.php";
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Login</title>
</head>
<body>
    <main class="wrapper">
        <form method="POST" class="input-group login">
            <h2>Login</h2>
            <div class="input-box">
                <input type="text" placeholder="Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="remember-forgot">
                <label for=""><input type="checkbox" name="" id="">Remember me</label>
                <a href="">Forgot password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="register-link">
                <p>
                    Don't have an account?
                    <a href="" >Register</a>
                </p>
            </div>
        </form>
        <form method="POST" class="input-group register">
            <h2>Register</h2>  
            <div class="input-box">
                <input type="email" class="kolom" name="re-email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="text" class="kolom" name="re-username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" class="kolom" name="re-password" placeholder="Password" required>
            </div>
            <div class="input-box">
                <input type="password" class="kolom" name="re-password2" placeholder="Konfirmasi Password" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="login-link">
                <p>
                    Have an account?
                    <a href="">Login</a>
                </p>
            </div>
        </form>
    </main>
    <script src="js/script.js"></script>
            <?php
                if(isset($_POST['btn-masuk'])){
                    $inusername = $_POST['in-username'];
                    $inpassword = $_POST['in-password'];
                    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$inusername'");
                    if (mysqli_num_rows($query) > 0) {
                        $row = mysqli_fetch_assoc($query);
                        
                        // Verify the password
                        if (password_verify($inpassword, $row['password'])) {
                            // Password is correct, set session variables and redirect to the home page
                            $_SESSION['username'] = $row['username'];
                            
                            // Redirect to the home page or any other page after successful login
                            header("Location: home.php");
                            exit();
                        } else {
                            // Password is incorrect
                            echo "<script>alert('Password salah');</script>";
                        }
                    } else {
                        // User does not exist
                        echo "<script>alert('User tidak ditemukan');</script>";
                    }
                    // if(mysqli_num_rows($query) == 1){
                    //     $row = mysqli_fetch_assoc($query);
                    //     if(password_verify($inpassword, $row['password'])){
                    //         $_SESSION['userweb'] = $inusername;
                    //         header("location:home.php");
                    //         exit;
                    //     } else {
                    //         echo "<script> alert('Maaf, username dan password salah') </script>";
                    //     }
                    // } 
                    // exit;
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
                        $password = password_hash($_POST['re-password'], PASSWORD_DEFAULT);
                        $query = mysqli_query($koneksi, "INSERT INTO admin VALUES('', '$name', '$email', '$username', '$password')");
                        if($query){
                            echo "<script> alert('Berhasil daftar') </script>";
                        }
                    }
                    exit;
                }
            ?>
</body>
</html>