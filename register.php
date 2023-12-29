<?php
    require_once 'init/init.php';
    require_once 'header.php';


    if(isset($_POST['submit'])){
        $nama    = $_POST['nama'];
        $user    = $_POST['uname'];
        $pass    = $_POST['password'];
        $email   = $_POST['email'];
        $hp      = $_POST['hp'];
        $alamat  = $_POST['alamat'];
        $uang    = $_POST['uang'];
        
        $filename = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        $type1 = explode('.', $filename);
        $type2 = $type1[1];

        $newname = 'produk'.time().'.'.$type2;

        $jenis_diizinkan = ['jpg', 'jpeg', 'gif', 'png'];

        $cek = mysqli_query($conn, "SELECT * FROM user WHERE nama = '$nama'");

        if(!in_array($type2, $jenis_diizinkan)){
            echo '<script>alert("Jenis file tidak diizinkan")</script>';
        } else if(mysqli_num_rows($cek) > 0){
            echo '<script>alert("Username sudah terdaftar")</script>';

        }
        
        else {
            move_uploaded_file($tmp_name, './gambar/' .$newname);
            
            $insert = mysqli_query($conn, "INSERT INTO user (gambar, nama, username, password, email, hp, alamat, uang) VALUES ('$newname', '$nama', '$user', '".MD5($pass)."', '$email', '$hp', '$alamat', '$uang') ");

            if($insert){
                login($user, $pass);
                echo '<script>alert("Berhasil Mendaftar")</script>';
                echo '<script>window.location="index.php"</script>';

            }

        }

    }


?>

<link rel="stylesheet" href="style.css">


<body>
    <section>
        <div class="container container-reg">
            <div class="box-reg">
                <h2 class="text-center">Register</h2><br>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" class="form-control" name="foto" required><br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                        <label for="nama">Nama</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="uname" placeholder="Username" required>
                        <label for="uname">Username</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="hp" placeholder="No. HP" required>
                        <label for="hp">No. HP</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="alamat" placeholder="Alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="number" class="form-control" name="uang" placeholder="Uang" required>
                        <label for="uang">Uang</label>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" name="submit" value="Daftar">
                    <a href="login.php" style="float:right; padding-top:10px">Sudah Punya Akun?</a>

                </form>

            </div>
        </div>
    </section>
</body>