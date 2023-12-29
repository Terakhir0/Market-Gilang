<?php   
require_once 'init/init.php';
include 'auth.php';
error_reporting(0);
$_SESSION['order'] = [];


$genre = mysqli_query($conn, "SELECT * FROM tb_genre ORDER BY genre_id");
$salah = '';
$salah2 = '';
$id = '';

if(isset($_POST['edit'])){
    $nama = $_POST['nama'];
    $id = $_POST['id'];

    $cek = mysqli_query($conn, "SELECT * FROM tb_genre WHERE genre_nama = '$nama' ");

    if(mysqli_num_rows($cek) == 1){
        $salah = 'Genre sudah ada!';

    }else{
    mysqli_query($conn, "UPDATE tb_genre SET genre_nama = '$nama' WHERE genre_id = '$id'  ");
    echo '<script>alert("Berhasil Mengubah Genre")</script>';
    echo '<script>window.location="daftar-genre.php"</script>';

    }
}



if(isset($_POST['tambah'])){
    $name    = $_POST['namag'];
   
    $cek = mysqli_query($conn, "SELECT * FROM tb_genre WHERE genre_nama = '$name' ");
    
    if(mysqli_num_rows($cek) == 1){
         $salah2 = 'Genre sudah ada!';
    } else{
        $sql = "INSERT INTO tb_genre (genre_nama) VALUES ('$name')";
        $conn -> query($sql);
        // $last  = $conn -> insert_id;
        echo '<script>alert("Berhasil Menambah Genre")</script>';
        // print_r($last);
        echo '<script>window.location="daftar-genre.php"</script>';

    }
}



require_once 'header.php';


?>

<link rel="stylesheet" href="style.css">

<!-- <form action="cari.php" method="post">
    <input type="search">
</form> -->


<body>



    <section>
        <div class="container">
            <div class="box-t-daftar-genre">
                <h2 class="text-center">Tambah Genre</h2><br>
                <form action="" method="post">
                    <input type="text" class="input-control" name="namag" placeholder="Nama" required><br>
                    <p style="color:red;"><?= $salah2; unset($salah2); ?></p>
                    <input type="submit" class="btn btn-lg btn-primary" name="tambah" value="Tambah">
                </form>

            </div>
        </div>

    </section>
    <section>
        <div class="container">
            <h2 class="text-center">Daftar Genre</h2><br>
            <div class="box-daftar-genre">
                <div class="input-group pt-4">
                    <input type="text" class="form-control input-cari" placeholder="Cari disini..." name="search"
                        id="input" onkeyup="filterFunction()">
                </div>

                <table class="table table-hover table-striped table-bordered text-center" id="table">
                    <thead>

                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                            $no = 1; 
                            if(mysqli_num_rows($genre) > 0){
                            while($data = mysqli_fetch_array($genre)):



                            ?>
                        <form action="" method="post">

                            <tr class="baris">
                                <th scope="row"><?= $no++ ?></th>
                                <td>
                                    <p style="display:none"> <?= $data['genre_nama'] ?></p>
                                    <input type="text" name="nama" value="<?= $data['genre_nama'] ?>"
                                        class="form-control"> <input type="hidden" value="<?=$data['genre_id']?>"
                                        name="id"><?= ($data['genre_id'] == $_POST['id'])? "<p style='color:red;'> $salah  
                                    </p>" : '' ?>
                                </td>
                                <td>
                                    <input type="submit" name="edit" value="EDIT" class="btn btn-primary">
                                    <a href="delete.php?idg=<?=$data['genre_id']?>" class="btn btn-danger"
                                        onclick="return confirm('Anda yakin ingin menghapus produk?')">HAPUS</a>
                                </td>
                            <tr class="last" style="display:none">
                                <td col="6">Genre tidak ditemukan!</td>
                            </tr>
                            </tr>
                        </form>

                        <?php  endwhile;} else{ ?>
                        <tr>
                            <td col="6">Genre Masih Kosong</td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <button onclick="topFunction()" id="myBtn" title="Go to top">
        <h2>^</h2>
    </button>


    <script>
    function filterFunction() {
        var input, filter, ul, li, baris, i;
        input = document.getElementById("input");
        filter = input.value.toUpperCase();
        baris = document.getElementsByClassName("baris");
        for (i = 0; i < baris.length; i++) {
            txtValue = baris[i].textContent || baris[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                baris[i].style.display = "";
            } else {
                baris[i].style.display = "none";
            }


        }
        p

        x = baris.style.display = "none";
        if (anjing == true) {
            test = document.getElementsByClassName("last");
            test.style.display = "";
        }
    }


    // Get the button:
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
    </script>

    <style>
    #myBtn {
        width: 3rem;
        height: 3.5rem;
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Fixed/sticky position */
        bottom: 20px;
        /* Place the button at the bottom of the page */
        right: 30px;
        /* Place the button 30px from the right */
        z-index: 99;
        /* Make sure it does not overlap */
        border: none;
        /* Remove borders */
        outline: none;
        /* Remove outline */
        background-color: blue;
        /* Set a background color */
        color: white;
        /* Text color */
        cursor: pointer;
        /* Add a mouse pointer on hover */
        padding: 10px;
        /* Some padding */
        border-radius: 10px;
        /* Rounded corners */
        font-size: 18px;
        /* Increase font size */
        opacity: 50%;
    }

    #myBtn:hover {
        opacity: 70%;

        /* Add a dark-grey background on hover */
    }
    </style>
</body>