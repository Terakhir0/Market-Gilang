<?php   
require_once 'init/init.php';
// error_reporting(0);
$_SESSION['order'] = [];


// print_r($genre);
    $cari  = '';    
    $search = '';
    $keywords = '';

    if(isset($_GET['search'])){
    $cari = $_GET['search'];
    $clean = cleaning($cari);

     if(strpos($clean, "-") > 0){
        $keywords = explode("-", $clean);
     }else{
        $keywords = $clean;
     }


    $search = "AND produk_nama LIKE '%$cari%' OR genre_nama LIKE '%$cari%' ";
    
    if(is_array($keywords)){
        foreach($keywords as $k){
            $search .= "OR produk_nama LIKE '%$k%'";
            $search .= "OR genre_nama LIKE '%$k%'";
        }
    }
    

    
    }   


    

    // var_dump($_SESSION['cari']);

// echo $ss;

// $array = implode( ", ", $_GET['genres']);
// echo $array;

// SELECT tb_produk.*, GROUP_CONCAT(tb_genre.genre_nama) FROM tb_produk INNER JOIN genre ON tb_produk.produk_id = genre.produkg_id INNER JOIN tb_genre ON genre_id = genreg_id INNER JOIN (SELECT DISTINCT genre_nama, genre_id FROM tb_genre WHERE genre_nama IN ('Action', 'Fantasy', 'Shounen')) AS GIS ON genre.genreg_id =  GIS.genre_id GROUP BY tb_produk.produk_id;

$perpage = 10;

$start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;

$page = "SELECT *, GROUP_CONCAT(genre_nama ORDER BY genre_nama SEPARATOR ' , ') AS genres FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_stok > 0 $search GROUP BY produk_nama ORDER BY produk_nama ASC";

$produk = data_pagination($page);

$pages = total_data_pagination($page);



require_once 'header.php';

?>

<link rel="stylesheet" href="style.css">

<div class="container-fluid filter">
    <div class="primary filter-btn">
        <button class="btn btn-pm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
            aria-expanded="false" aria-controls="collapseExample" style="background-color:blue">
            Filter
        </button>
    </div>
    <form action="cari_genre.php">

        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div class="edit-genre">
                    <?php 
                            $query = mysqli_query($conn, "SELECT * FROM tb_genre ORDER BY genre_nama");
                            if(mysqli_num_rows($query) > 0 ) {
                                foreach($query as $data):
                                    $checked = explode( " , ", $_SESSION['cari']);
                                    $check2 = $_SESSION['cari'];
                                    // $datase = in_array($data['genres'], $checked);
                                    // print_r($datase);
                                    // print_r ($checked);

                            ?>
                    <div class="form-check edit-genre-form">

                        <input class="form-check-input edit-genre" name="genres[]" type="checkbox"
                            value="<?= $data['genre_id'] ?>"
                            <?= (in_array($data['genre_id'], $checked) || $data['genre_id'] == $check2 )? 'checked' : ''  ?>
                            id="flexCheckDefault">
                        <label class="form-check-label edit-genre" for="flexCheckDefault">
                            <?= $data['genre_nama'] ?>
                        </label>
                    </div>
                    <?php endforeach;} else{?>
                    <p> Genre Tidak Ada</p>
                    <?php } ?>
                </div>
                <div class="tombol-edit-genre">
                    <input type="submit" class="btn btn-lg btn-primary" id="btn-edit-genre" name="filter" value="Cari">
                </div>
            </div>
        </div>

    </form>
</div>
<section>
    <div class="container-fluid">


        <div class="box-s" id="box-s">

            <?php 

                $genres = implode(', ', $_GET['genres']);

                if(mysqli_num_rows($produk) > 0){
                   


                while($data = mysqli_fetch_array($produk)): 
                //     $genres = $_GET['genres'];
                $genre_p = mysqli_query($conn, "SELECT *, GROUP_CONCAT(genre_nama ORDER BY genre_nama SEPARATOR ' , ') AS genres FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_stok > 0 AND produk_nama LIKE '%".$data['produk_nama']."%' GROUP BY produk_nama ORDER BY genre_id");

                
                $datag = mysqli_fetch_array($genre_p);

                //  $array[] = explode(" , ", $datag['genres']);
                //  print_r($array);
                //    $array = explode( ' , ', $datag['genres']);
                    // if(in_array($data['genres'], $_GET['genres'])){
                ?>




            <div class="col-5" id="col-5">

                <a href="single.php?idp=<?= $data['produk_id'] ?>"><img src="gambar-p/<?= $data['produk_gambar']; ?>"
                        class="img-ps"></a>
                <div class="item-series">
                    <a id="title" class="title" href="single.php?idp=<?= $data['produk_id'] ?>"
                        style="text-decoration:none">
                        <?= $data['produk_nama'] ?>
                    </a>
                    <div class="item-genre" id="item-genre">
                        <p>
                            <?= $datag['genres'] ?>
                        </p>
                    </div>

                    <div class="author-s">
                        <p><?= $data['produk_penulis']; ?></p>
                    </div>
                    <div class="deskripsi-s">
                        <p><?= $data['produk_deskripsi'] ?></p>
                    </div>


                    <form action="" method="post">
                        <div class="tombol-c">
                            <a>Rp. <?= number_format($data['produk_harga']) ?></a>

                            <a href="order.php?idp=<?= $data['produk_id'] ?>" name="beli" class="btn btn-success btn-c">
                                BELI</a>
                            <input type="submit" name="cart" class="btn btn-info btn-c" value="CART">
                        </div>
                    </form>
                </div>

            </div>

            <?php  endwhile; }  else{ ?>
            <h3 class="text-center" style="margin-left:29rem">Produk yang anda cari tidak ada</h3>
            <?php } ?>
        </div>

        <?php                 if(mysqli_num_rows($produk) > 0){
 ?>
        <nav aria-label="..." class="pagination-riwayat">
            <ul class="pagination ">

                <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                    <a class="page-link " href="?halaman=1<?= check_search($cari) ?>">First</a>
                </li>
                <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                    <a class="page-link " href="?halaman=<?= prev_page().''. check_search($cari) ?>">Previous</a>
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
                    <a class="page-link" href="?halaman=<?= next_page($pages) .''. check_search($cari); ?>">Next</a>
                </li>
                <li class="page-item  <?= (current_page() == $pages ? 'disabled' : '') ?>">
                    <a class="page-link" href="?halaman=<?= $pages .''. check_search($cari); ?>">Last</a>
                </li>

            </ul>
        </nav>

        <?php } ?>
    </div>
</section>

</body>