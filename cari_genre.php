<?php   
require_once 'init/init.php';
error_reporting(0);
$_SESSION['order'] = [];


// print_r($genre);
    $cari  = '';    
    $search = '';    
    $results = array();


     if(isset($_GET['genre'])){
        $cari =  explode(" , " , $_GET['genre']);
        // $_SESSION['genre'] = explode(" , ", $cari);
        // $search = "AND genre_nama  LIKE '%$cari%' ";
        
    }
 
   
     else if(isset($_GET['genres'])){
        $cari = $_GET['genres'];
        // // = Not Used
        // // foreach($genres as $val){
        // //     echo $val;

        // // $genres = implode(', ', $_GET['genres']);
        // // =  Not Used

        // $count = count($cari);
        // for($x = 0; $x < $count; $x++){ 
        // $search = "AND genre_nama IN ('$cari[$x]') ";
        // }
     
        $_SESSION['genres'] = $cari;

    }
    else{
        $cari = $_SESSION['genres'];
        // $count = count($cari);
        // for($x = 0; $x < $count; $x++){ 
        // $search = "AND genre_nama IN ('$cariss[$x]') ";
        // }

      
    }

    if(isset($_POST['beli'])){
        $idp = $_POST['idp'];
        $jmlh = 1;
        $stk = $_POST['stokp'];
    
     
        if(order($idp, $jmlh, $stk)){
            header('Location:order_all.php');
        };
    
    }

    if(isset($_POST['cart'])){
        $idc = $_POST['idc'];
        // $id = $_SESSION['id'];
        $jmlh = $_POST['jumlah'];
    
        if($_SESSION['login'] == true){
            keranjang($idc, $id, $jmlh);
        } else {
            keranjangGuest($idc, $jmlh);
        }
        
    }


    // var_dump($search);

// echo $ss;

// $array = implode( ", ", $_GET['genres']);
// echo $array;

// SELECT tb_produk.*, GROUP_CONCAT(tb_genre.genre_nama) FROM tb_produk INNER JOIN genre ON tb_produk.produk_id = genre.produkg_id INNER JOIN tb_genre ON genre_id = genreg_id INNER JOIN (SELECT DISTINCT genre_nama, genre_id FROM tb_genre WHERE genre_nama IN ('Action', 'Fantasy', 'Shounen')) AS GIS ON genre.genreg_id =  GIS.genre_id GROUP BY tb_produk.produk_id;

$perpage = 10;

$start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;



$dataAll = mysqli_query($conn, "SELECT *, GROUP_CONCAT(genre_nama ORDER BY genre_nama SEPARATOR ' , ') AS genres FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_stok > 0 GROUP BY produk_nama ORDER BY produk_id ASC ");

foreach($dataAll as $p){
    $array = [ 
        'id'     => $p['produk_id'],
        'nama'   => $p['produk_nama'],
        'gambar' => $p['produk_gambar'],
        'harga'  => $p['produk_harga'],
        'stok'   => $p['produk_stok'],
        'genres' => explode(" , " , $p['genres'])
        
      ];


  $results[] = $array;

  

}

$getData = multiSearchGenre($results, $cari);
$convertData = implode(", " , $getData);

// for($i = 0; $i < $count; $i++){
    $search = "AND produk_id IN ($convertData)";
// }

// var_dump($count);


$page = "SELECT *, GROUP_CONCAT(genre_nama ORDER BY genre_nama SEPARATOR ' , ') AS genres FROM tb_produk INNER JOIN genre ON produkg_id=produk_id INNER JOIN tb_genre ON genre_id=genreg_id WHERE produk_stok > 0 $search GROUP BY produk_nama ORDER BY produk_id ASC ";


$produk = data_pagination($page);

$pages = total_data_pagination($page);




// $coba = array("School Life");



// $anjing = multiSearchGenre($results, $coba);
// var_dump($anjing);




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
                                    $checked = $cari;
                                    $check2 = $cari;
                                    // $datase = in_array($data['genres'], $checked);
                                    // print_r($datase);
                                    // print_r ($checked);

                            ?>
                    <div class="form-check edit-genre-form">

                        <input class="form-check-input edit-genre" name="genres[]" type="checkbox"
                            value="<?= $data['genre_nama'] ?>"
                            <?= (in_array($data['genre_nama'], $checked) ||  $data['genre_nama'] == $check2 )? 'checked' : ''  ?>
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

                            <button type="button" class="btn btn-success btn-c" data-bs-toggle="modal"
                                data-bs-target="#order-modal<?= $data['produk_id'] ?>">
                                Beli
                            </button>
                            <button type="button" class="btn btn-info btn-c" data-bs-toggle="modal"
                                data-bs-target="#cart-modal<?= $data['produk_id'] ?>">
                                CART
                            </button>

                            <div class="modal fade" id="order-modal<?= $data['produk_id'] ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Ingin membeli produk?
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="gambar-p/<?= $data['produk_gambar'] ?>" alt=""
                                                class="modal-gambar">
                                            <h4 class="modal-judul"><?= $data['produk_nama'] ?></h4>
                                            <span class="text-jumlah"> Jumlah : <input type="number"
                                                    class="form-control"
                                                    style="width:5rem; margin-left: 0.5rem; padding-right:3px"
                                                    name="jumlah_beli[]" id="" value="1" min="1"
                                                    max="<?= $data['produk_stok'] ?>" required>
                                                <input type="hidden" name="stokp" value="<?= $data['produk_stok'] ?>">
                                            </span>


                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="idp[]" value="<?= $data['produk_id'] ?>">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tidak</button>
                                            <input type="submit" name="beli" class="btn btn-primary" value="Beli">


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="cart-modal<?= $data['produk_id'] ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Masukkan dalam
                                                keranjang?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="gambar-p/<?= $data['produk_gambar'] ?>" alt=""
                                                class="modal-gambar">
                                            <h4 class="modal-judul"><?= $data['produk_nama'] ?></h4>
                                            <span class="text-jumlah"> Jumlah : <input type="number"
                                                    class="form-control"
                                                    style="width:5rem; margin-left: 0.5rem; padding-right:3px"
                                                    name="jumlah" id="" value="1" min="1"
                                                    max="<?= $data['produk_stok'] ?>" required></span>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tidak</button>
                                            <input type="hidden" name="idc" value="<?= $data['produk_id'] ?>">
                                            <input type="submit" name="cart" class="btn btn-primary"
                                                data-bs-dismiss="modal" value="Ya">

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>

            <?php  endwhile; }  else{ ?>
            <h3 class="text-center" style="margin-left:29rem">Produk yang anda cari tidak ada</h3>
            <?php } ?>
        </div>
        <?php  if(mysqli_num_rows($produk) > 0){
 ?>
        <nav aria-label="..." class="pagination-riwayat">
            <ul class="pagination ">


                <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                    <a class="page-link " href="?halaman=1">First</a>
                </li>
                <li class="page-item  <?= (current_page() == 1 ? 'disabled' : '') ?> ">
                    <a class="page-link " href="?halaman=<?= prev_page() ?>">Previous</a>
                </li>
                <?php for($i = 1; $i <=$pages; $i++ ){ ?>
                <li id="page" class="page-item <?= ($i == current_page() ? 'active' : '') ?>">
                    <?php if(is_showable($pages, $i)){  ?>
                    <a class="page-link" href="?halaman=<?= $i?>">
                        <?= $i   ?>
                    </a>
                    <?php  } ?>
                    <?php  } ?>

                </li>

                <li class="page-item  <?= (current_page() == $pages ? 'disabled' : '') ?>">
                    <a class="page-link" href="?halaman=<?= next_page($pages); ?>">Next</a>
                </li>
                <li class="page-item  <?= (current_page() == $pages ? 'disabled' : '') ?>">
                    <a class="page-link" href="?halaman=<?= $pages; ?>">Last</a>
                </li>

            </ul>
        </nav>

        <?php }?>
    </div>
</section>

</body>