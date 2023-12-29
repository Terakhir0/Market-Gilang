<?php   
require_once 'init/init.php';
include 'auth.php';
error_reporting(0);
$_SESSION['order'] = [];

$cari = $_GET['search'];

$search = '';

if(isset($_GET['search'])){
    $search = "AND produk_nama LIKE '%$cari%' OR tanggal LIKE '%$cari%' ";
    $_SESSION['search'] = $cari;
} else {
    $cari = $_SESSION['search'];
}

$perpage = 10;
$start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;


$data = "SELECT * FROM tb_produk WHERE produk_stok > 0 $search ORDER BY produk_id ASC";

$produk = data_pagination($data);


$pages = total_data_pagination($data);


require_once 'header.php';


?>

<link rel="stylesheet" href="style.css">

<!-- <form action="cari.php" method="post">
    <input type="search">
</form> -->


<body>
    <section>
        <div class="container">

            <h2 class="text-center">Daftar Produk</h2><br>

            <div class="box-p">
                <div class="input-group">
                    <form action="daftar-produk.php" method="get">
                        <input type="text" class="form-control input-cari" name="search" id="myInput"
                            placeholder="Cari nama disini..."
                            value="<?= (isset($_GET['search']) != '')? $_GET['search'] : ''  ?>">
                    </form>

                </div>
                <table id="myTable" class="table table-hover table-striped table-bordered text-center">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Edit</th>
                    </tr>
                    <?php 
                            $no = 0 + $start; 
                            if(mysqli_num_rows($produk) > 0){
                            
                            while($row = mysqli_fetch_array($produk)):
                                
                                $genre = mysqli_query($conn, "SELECT *, GROUP_CONCAT(genre_nama ORDER BY genre_nama SEPARATOR ' , ') AS genres, GROUP_CONCAT(genre_id ORDER BY genre_nama SEPARATOR ' , ') AS genres_id FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_stok > 0 AND produk_nama LIKE '%".$row['produk_nama']."%' GROUP BY produk_nama ORDER BY genre_nama");



                                $row_genre = mysqli_fetch_array($genre);
                               $no++


                            ?>
                    <tr class="baris">

                        <th scope="row"><?= $no ?></th>
                        <td><img src="gambar-p/<?= $row['produk_gambar'] ?>" alt="" width="50px"></td>
                        <td class="nama"><a
                                href="single.php?idp=<?= $row['produk_id'] ?>"><?= $row['produk_nama'] ?></a></td>
                        <td><?= substr($row['produk_deskripsi'] , 0 , 50)?>...</td>
                        <td>

                            <?php $array = explode(', ', $row_genre['genres']);
                                  $array2 = explode(', ', $row_genre['genres_id']);
                                  $number = count($array);    

                                for($i = 0; $i < $number; $i++){ ?>
                            <a href="cari_genre.php?genre=<?= $array2[$i] ?>" class="badge bg-primary rounded-pill"
                                style="text-decoration:none">
                                <?= $array[$i] ?>
                            </a>
                            <?php  } ?>
                        </td>
                        <td>Rp. <?= number_format($row['produk_harga']) ?></td>
                        <td><?= number_format($row['produk_stok']) ?></td>
                        <td>
                            <a href="edit-produk.php?idp=<?php echo $row['produk_id']?>"
                                class="btn btn-success">EDIT</a>
                            <a href="delete.php?idp=<?=$row['produk_id']?>" class="btn btn-danger"
                                onclick="return confirm('Anda yakin ingin menghapus produk?')">HAPUS</a>
                        </td>
                    </tr>

                    <?php  endwhile;} else{ ?>
                    <tr>
                        <td colspan="8">Produk Masih Kosong</td>
                    </tr>
                    <?php } ?>
                </table>

                <nav aria-label="..." class="pagination-riwayat">
                    <ul class="pagination ">


                        <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                            <a class="page-link " href="?halaman=1<?= check_search($cari) ?>">First</a>
                        </li>
                        <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                            <a class="page-link "
                                href="?halaman=<?= prev_page().''. check_search($cari) ?>">Previous</a>
                        </li>
                        <?php for($i = 1; $i <=$pages; $i++ ){ ?>
                        <li id="page" class="page-item <?= ($i == current_page() ? 'active' : '') ?>">
                            <?php if(is_showable($pages, $i)){  ?>
                            <a class="page-link" href="?halaman=<?= $i.''. check_search($cari) ?>">
                                <?= $i   ?>
                            </a>
                            <?php  } ?>
                            <?php  } ?>

                        </li>

                        <li class="page-item  <?= (current_page() == $pages ? 'disabled' : '') ?>">
                            <a class="page-link"
                                href="?halaman=<?= next_page($pages) .''. check_search($cari); ?>">Next</a>
                        </li>
                        <li class="page-item  <?= (current_page() == $pages ? 'disabled' : '') ?>">
                            <a class="page-link" href="?halaman=<?= $pages .''. check_search($cari); ?>">Last</a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <script>
    function myFunction() {
        var input, filter, ul, li, baris, i;
        input = document.getElementById("myInput");
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
    }
    </script>
</body>