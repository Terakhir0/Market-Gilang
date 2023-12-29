<?php
    require_once 'init/init.php';
    include 'auth.php';
    $_SESSION['order'] = [];



    $id = $_SESSION['id'];
    $data = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
    $u = mysqli_fetch_object($data);
    $salah = '';

    if(isset($_POST['edit'])){
   $nama    = $_POST['nama'];
   $user    = $_POST['uname'];
   $email   = $_POST['email'];
   $hp      = $_POST['hp'];
   $alamat  = $_POST['alamat'];
   $gambar  = $_POST['gambar'];
   
   $filename = $_FILES['foto']['name'];
   $tmp_name = $_FILES['foto']['tmp_name'];

   if($filename != ''){
   $type1 = explode('.', $filename);
   $type2 = $type1[1];

   $newname = 'gambar'.time().'.'.$type2;

   $jenis_diizinkan = ['jpg', 'jpeg', 'gif', 'png'];

   $cek = mysqli_query($conn, "SELECT * FROM user WHERE nama = '$nama'");

   if(!in_array($type2, $jenis_diizinkan)){
       echo '<script>alert("Jenis file tidak diizinkan")</script>';
   }
   
   else {
       unlink('./gambar/'. $gambar);
       move_uploaded_file($tmp_name, './gambar/' .$newname);
       $newgambar = $newname; 
      
   } 

   }else {
    $newgambar = $gambar;
   }
   
   $update = mysqli_query($conn, "UPDATE user SET gambar = '$newgambar', nama = '$nama', username = '$user', email = '$email', hp = '$hp', alamat = '$alamat'  WHERE id = '$id' " );
   if($update){
       echo '<script>alert("Berhasil mengubah data profil")</script>';
       echo '<script>window.location="profil.php"</script>';

   }else echo mysqli_error($conn);
}

if(isset($_POST['tambah'])){
    $uang = $u->uang + $_POST['uang'];

    mysqli_query($conn, "UPDATE user SET uang = '$uang' WHERE id = '$id' ");
    echo '<script>alert("Berhasil menambah uang")</script>';
    echo '<script>window.location="profil.php"</script>';


}

if(isset($_POST['ubah'])){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if($pass1 == $pass2){
    mysqli_query($conn, "UPDATE user SET uang = '$uang' WHERE id = '$id' ");
    echo '<script>alert("Berhasil menambah uang")</script>';
    echo '<script>window.location="profil.php"</script>';
    } else $salah = 'Konfirmasi password tidak sesuai!';

}


    require_once 'header.php';
?>


<link rel="stylesheet" href="style.css">



<body>
    <section>
        <div class="container container-p">
            <?php
             $id = $_SESSION['id'];
             $data = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
             while($d = mysqli_fetch_assoc($data)): ?>
            <h2 class="text-center pt-3">Profile Anda</h2>
            <div class="box-p">
                <form action="" method="post" enctype="multipart/form-data">
                    <img src="gambar/<?= $d['gambar']  ?>" class="foto-profil" alt=""><br>
                    <input type="hidden" name="gambar" value="<?= $d['gambar'] ?>">
                    <input type="file" class="form-control" name="foto"><br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?= $d['nama'] ?>"
                            required>
                        <label for="nama">Nama</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="uname" value="<?= $d['username'] ?>"
                            placeholder="Username" required>
                        <label for="uname">Username</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" value="<?= $d['email'] ?>"
                            placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="hp" value="<?= $d['hp'] ?>" placeholder="No. HP"
                            required>
                        <label for="hp">No. HP</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="alamat" value="<?= $d['alamat'] ?>"
                            placeholder="Alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" name="edit" value="Edit">
                </form>
            </div>

            <h2 class="text-center pt-3">Isi Rekening</h2>
            <div class="box-p">
                <h4>Uang yang anda miliki saat ini : Rp. <?= number_format($d['uang']); ?></h4>
                <form action="" method="post">
                    <div class="form-floating">
                        <input type="number" class="form-control" name="uang" placeholder="Rp. " required>
                        <label for="uang">Uang</label>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" name="tambah" value="Tambah">
                </form>
            </div>
            <?php endwhile; ?>


            <h2 class="text-center pt-3">Ubah Password</h2>
            <div class="box-p">
                <form action="" method="post">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="pass1" name="pass1" placeholder="Password Baru "
                            required>
                        <label for="pass1">Password Baru</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="pass2" name="pass2"
                            placeholder="Konfirmasi Password Baru " required>
                        <label for="pass2">Konfirmasi Password baru</label>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" name="ubah" value="Ubah"><br>
                    <p style="color:red;"><?= $salah; unset($salah); ?></p>
                </form>
            </div>
        </div>
    </section>
</body>


<!-- <script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script>
<script src="script.js"> </script> -->