<?php
    require_once 'init/init.php';
    if(isset($_SESSION['login']) == true){
        echo '<script>alert(Anda sudah login")</script>';
        echo '<script>window.location="index.php"</script>';
    }



    if(isset($_POST['submit'])){
        $user = $_POST['uname'];
        $pass = $_POST['password'];

        if(login($user, $pass) == true){
            echo '<script>alert("Berhasil Login")</script>';
            echo '<script>javascript:history.go(-2)</script>';
        }else echo '<script>alert("Username atau Password Salah")</script>';
        

    }


    require_once 'header.php';
?>



<link rel="stylesheet" href="style.css">

<body>
    <section>
        <div class="container container-log">
            <div class="box-log">
                <h2 class="text-center">Login</h2><br>
                <form action="" method="post">
                    <input type="text" class="input-control" name="uname" placeholder="Username" required><br>
                    <input type="password" class="input-control" name="password" placeholder="Password" required><br>
                    <input type="submit" class="btn btn-lg btn-primary btn-login" name="submit" value="Login">
                    <a href="register.php">Belum Punya Akun?</a>
                </form>
            </div>
        </div>
    </section>
</body>