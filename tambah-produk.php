<?php
    require_once 'init/init.php';
    include 'auth.php';
    // error_reporting(0);
    $_SESSION['order'] = [];


    // $query = mysqli_query($conn, "SELECT * FROM tb_produk");
    
    // $data = mysqli_fetch_object($query);

    // $idp = $data['id'];

    $pilihGenre = '';
    // $genre   = $_POST['genre'];
    // $jmlh    = count($genre);

    // for($x = 0 ; $x < count($_POST['genre']);$x++){
    //     $test = $_POST['genre'][$x];
    //     echo print_r($test);

    // }


    if(isset($_POST['submit'])){

        $nama    = $_POST['nama'];
        $penulis = $_POST['penulis'];
        $desc    = $_POST['deskripsi'];
        $harga   = $_POST['harga'];
        $stok    = $_POST['stok'];
        $genre   = $_POST['genre'];
        $gambar   = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        $genres = implode(" , ", $genre);

        $type1 = explode('.', $gambar);
        $type2 = $type1[1];

        $newname = 'produk'.time().'.'.$type2;

        $jenis_diizinkan = ['jpg', 'jpeg', 'gif', 'png'];

        $cek = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_nama = '$nama'");

        if(empty($genre)) {
            echo '<script>alert("Pilih setidaknya satu genre!")</script>';
            echo '<script>window.location="tambah-produk.php "</script>';
        }else{
        if(!in_array($type2, $jenis_diizinkan)){
            echo '<script>alert("Jenis file tidak diizinkan")</script>';
        } else if(mysqli_num_rows($cek) > 0){
            echo '<script>alert("Produk sudah terdaftar")</script>';

        }
        else {
            move_uploaded_file($tmp_name, './gambar-p/' .$newname);
            
            $insert = mysqli_query($conn, "INSERT INTO tb_produk (produk_gambar, produk_nama, produk_penulis, produk_deskripsi, produk_harga, produk_stok ) VALUES ('$newname', '$nama',  '$penulis', '$desc', '$harga', '$stok') ");
            
            
            if($insert){
                $last  = $conn -> insert_id;
                $jmlh  = count($genre);
                sort($genre);
                for($x = 0 ; $x < $jmlh ; $x++){
                mysqli_query($conn, "INSERT INTO genre (genreg_id, produkg_id) VALUES ('$genre[$x]', '$last')");
                }
                echo '<script>alert("Berhasil Menabah Produk")</script>';
                echo '<script>window.location="tambah-produk.php"</script>';

            }

            
        }


    }
    }
    require_once 'header.php';

?>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

<body>
    <section>
        <div class="container container-t">
            <div class="box-t">
                <h2 class="text-center">Tambah Produk</h2><br>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" class="form-control" name="foto" required><br>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                        <label for="nama">Nama</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Penulis">
                        <label for="penulis">Penulis</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <textarea type="text" class="form-control" name="deskripsi" id="deskripsi"
                            placeholder="Deskripsi" required> </textarea>
                        <label style="margin-top:4.5rem" for="deskripsi">Deskripsi</label>
                    </div>

                    <br>
                    <div class="form-floating">
                        <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                        <label for="harga">Harga</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" required>
                        <label for="stok">Stok</label>
                    </div>
                    <br>
                    <p style="color:red">*Pilih setidaknya satu genre!</p>
                    <p>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Pilih Genre
                        </button>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <div class="edit-genre">
                                <?php 
                            $query = mysqli_query($conn, "SELECT * FROM tb_genre ORDER BY genre_nama");
                            if(mysqli_num_rows($query) > 0 ) {
                                while($data = mysqli_fetch_assoc($query)):
                            ?>
                                <div class="form-check edit-genre-form">
                                    <input class="form-check-input edit-genre" name="genre[]" type="checkbox"
                                        value="<?= $data['genre_id'] ?>" id="flexCheckDefault">
                                    <label class="form-check-label edit-genre" for="flexCheckDefault">
                                        <?= $data['genre_nama'] ?>
                                    </label>
                                </div>
                                <?php endwhile;} else{?>
                                <p> Genre Tidak Ada</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" name="submit" value="Tambah">
                </form>
            </div>
        </div>
    </section>
    <script>
    CKEDITOR.replace('deskripsi');
    </script>
</body>