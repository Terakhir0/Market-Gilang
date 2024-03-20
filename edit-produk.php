<?php
    require_once 'init/init.php';
    include 'auth.php';
    $_SESSION['order'] = [];

    // $query = mysqli_query($conn, "SELECT * FROM tb_produk");
    
    // $data = mysqli_fetch_object($query);

    // $idp = $data['id'];

    $idp = $_GET['idp'];

    $sql = "SELECT * FROM tb_produk WHERE produk_id = $idp";
    $data = $conn->query($sql);
    // $data = mysqli_query($conn, "SELECT *FROM tb_produk WHERE produk_id = $id");
    // if(mysqli_num_rows($data) == 0){
    //     echo '<script>window.location="daftar-produk.php"</script>';
    // }
    $dp = mysqli_fetch_assoc($data);

    

    if(isset($_POST['submit'])){
        $nama    = $_POST['nama'];
        $penulis = $_POST['penulis'];
        $genre   = $_POST['genre'];
        $desc    = $_POST['deskripsi'];
        $harga   = $_POST['harga'];
        $stok    = $_POST['stok'];
        $gambar  = $_POST['gambar'];      
          
        $filename = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        if($filename != ''){

        $type1 = explode('.', $filename);
        $type2 = $type1[1];

        $newname = 'produk'.time().'.'.$type2;

        $jenis_diizinkan = ['jpg', 'jpeg', 'gif', 'png'];

        $cek = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_nama = '$nama'");

        if(!in_array($type2, $jenis_diizinkan)){
            echo '<script>alert("Jenis file tidak diizinkan")</script>';
        }
        
        else {
            unlink('./gambar-p/'. $gambar);
            move_uploaded_file($tmp_name, './gambar-p/' .$newname);
           $newgambar = $newname;

        }

    }else {
        $newgambar = $gambar;

    }

    $update = mysqli_query($conn, "UPDATE tb_produk SET produk_gambar = '$newgambar', produk_nama = '$nama', produk_penulis = '$penulis', produk_deskripsi =  '$desc', produk_harga = '$harga', produk_stok = '$stok' WHERE produk_id = $idp ");
    
    if($update){
        // $last  = $conn -> insert_id;
       
        echo '<script>alert("Berhasil Mengubah Produk")</script>';
        echo "<script>window.location='edit-produk.php?idp=$idp'</script>";
        
    }

}

if(isset($_POST['edit-genre'])){
    $genre   = $_POST['genre'];
    sort($_POST['pgenre']);
    $genres  = implode(", ", $_POST['pgenre']);
    
    $jmlh  = count($genre);
    mysqli_query($conn, "DELETE FROM genre WHERE produkg_id = '$idp' ");
    for($x = 0 ; $x < $jmlh ; $x++){
    mysqli_query($conn, "INSERT INTO genre (genreg_id, produkg_id) VALUES ('$genre[$x]', '$idp')");
    }        
    mysqli_query($conn, "UPDATE tb_produk SET produk_genres = '$genres' WHERE produk_id = '$idp' ");
}

    require_once 'header.php';

?>

<head>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

</head>



<body>
    <section>
        <div class="container container-t">
            <div class="box-t">
                <h2 class="text-center">Edit Produk</h2><br>
                <form action="" method="post" enctype="multipart/form-data">
                    <img src="gambar-p/<?= $dp['produk_gambar']; ?>" alt="" width="300px">
                    <input type="hidden" name="gambar" value="<?= $dp['produk_gambar']; ?>">
                    <input type="file" class="form-control" name="foto"><br>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $dp['produk_nama'] ?>">
                        <label for="nama">Nama</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="penulis" name="penulis"
                            value="<?= $dp['produk_penulis'] ?>">
                        <label for="nama">Penulis</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <textarea type="text" class="form-control" name="deskripsi" id="deskripsi"
                            placeholder="Deskripsi" required><?= $dp['produk_deskripsi'] ?></textarea>
                        <label style="margin-top:4.5rem" for="deskripsi">Deskripsi</label>
                    </div>

                    <br>
                    <div class="form-floating">
                        <input type="number" class="form-control" name="harga" value="<?= $dp['produk_harga'] ?>"
                            required>
                        <label for="harga">Harga</label>
                    </div>
                    <br>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="stok" name="stok"
                            value="<?= $dp['produk_stok'] ?>" placeholder="Stok" required>
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
                               
                                $sql_genre = mysqli_query($conn, "SELECT produk_id, produk_gambar, produk_nama, produk_deskripsi, produk_harga, produk_stok, GROUP_CONCAT(DISTINCT genre_nama SEPARATOR ', ') AS genres FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_id = '$idp' ORDER BY genre_nama ");
                                $sql = mysqli_query($conn, "SELECT * FROM genre WHERE produkg_id = '$idp' ");
                                $cek = mysqli_fetch_array($sql_genre);
                                // print_r($cek);
                                $query = mysqli_query($conn, "SELECT * FROM tb_genre");
                                // $array = ['Action', 'Adventure'];
                                // print_r($array);

                                if(mysqli_num_rows($query) > 0){
                                    // $genres = mysqli_fetch_array($query);
                                    
                                    while($datas = mysqli_fetch_array($query)):
                                        $type = explode(', ', $cek['genres']);
                                        

                            ?>


                                <div class="form-check edit-genre-form">
                                    <input type="hidden" name="genres[]" value="<?= $type['genreg_id'] ?>">
                                    <input class="form-check-input edit-genre" name="genre[]"
                                        value="<?= $datas['genre_id'] ?>" type="checkbox"
                                        <?= (!in_array($datas['genre_nama'], $type))? '' : 'checked'  ?>>
                                    <label class="form-check-label badge bg-primary rounded-pill "
                                        for="flexCheckDefault">
                                        <?= $datas['genre_nama'] ?>
                                    </label>

                                </div>

                                <?php endwhile; } else{?>
                                <p> Genre Tidak Ada</p>
                                <?php } ?>

                            </div>
                            <div class="tombol-edit-genre"><input type="submit" class="btn btn-lg btn-primary"
                                    id="btn-edit-genre" name="edit-genre" value="Ubah genre">
                            </div>
                        </div>


                    </div><br>
                    <input type="submit" class="btn btn-lg btn-primary" name="submit" value="Edit">
                </form>
            </div>
        </div>
    </section>
    <script>
    CKEDITOR.replace('deskripsi');
    </script>
</body>