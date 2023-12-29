<?php
    require_once 'init/init.php';
    include 'auth.php';
    $_SESSION['order'] = [];


    // $query = mysqli_query($conn, "SELECT * FROM tb_produk");
    
    // $data = mysqli_fetch_object($query);

    // $idp = $data['id'];

    $salah = '';
    
    if(isset($_POST['submit'])){
        $nama    = $_POST['nama'];
       
        $cek = mysqli_query($conn, "SELECT * FROM tb_genre WHERE genre_nama = '$nama' ");
        
        if(mysqli_num_rows($cek) == 1){
             $salah = 'Genre sudah ada!';
        } else{
            $sql = "INSERT INTO tb_genre (genre_nama) VALUES ('$nama')";
            $conn -> query($sql);
            $last  = $conn -> insert_id;
            echo '<script>alert("Berhasil Menambah Genre")</script>';
            // print_r($last);
            echo '<script>window.location="genre.php"</script>';

        }
    }

    require_once 'header.php';

?>


<link rel="stylesheet" href="style.css">

<section>
    <div class="container container-t">
        <div class="box-t">
            <h2 class="text-center">Tambah Genre</h2><br>
            <form action="" method="post">
                <input type="text" class="input-control" name="nama" placeholder="Nama" required><br>
                <p style="color:red;"><?= $salah; unset($salah); ?></p>
                <input type="submit" class="btn btn-lg btn-primary" name="submit" value="Tambah">
            </form>

        </div>
    </div>

</section>