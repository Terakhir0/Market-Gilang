<?php   
require_once 'init/init.php';
error_reporting(0);


$id = $_SESSION['id'];
$_SESSION['order'] = [];


$penuh = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = $id");


if(isset($_POST['beli'])){
    $idp = $_POST['idp'];
    $jmlh = $_POST['jumlah_beli'];
    $stk = $_POST['stokp'];

 
    if(order($idp, $jmlh, $stk)){
        header('Location:order_all.php');
    };

}

$cari = '';

$semuaProduk = "SELECT * FROM tb_produk ORDER BY tanggal ASC";

$produk = data_pagination_index($semuaProduk);

$pages = total_data_pagination_index($semuaProduk);






if(isset($_POST['cart'])){
    $idc = $_POST['idc'];
    $id = $_SESSION['id'];
    $jmlh = $_POST['jumlah'];

    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE produkC_id = $idc");

    if(mysqli_num_rows($cek) > 0 ){
        echo '<script>alert("Barang sudah ada di dalam keranjang!")</script>';


    } elseif(mysqli_num_rows($penuh) == 10){
        echo '<script>alert("Keranjang sudah penuh!")</script>';

    }
    else{
    mysqli_query($conn, "INSERT INTO keranjang (produkC_id, user_id, jumlah) VALUES ('$idc', '$id', $jmlh )");
    echo '<script>alert("Berhasil Menabah Keranjang")</script>';    
    header('Location:index.php');
    }
}

require_once 'header.php';

?>

<link rel="stylesheet" href="style.css">



<body>



    <section>
        <div class="container-fluid conteiner-i">
            <div class="filter-index">
                <p id="genre-header-index">Genre</p>
                <div class="filter-genre-form">
                    <?php 
                            $query = mysqli_query($conn, "SELECT * FROM tb_genre ORDER BY genre_nama");
                            if(mysqli_num_rows($query) > 0 ) {
                                foreach($query as $data):
                                   

                            ?>
                    <a class="item-genres-index" href="cari_genre.php?genre=<?= $data['genre_id'] ?>">
                        <?= $data['genre_nama'] ?>
                    </a>

                    <?php endforeach;} else{?>
                    <p> Genre Tidak Ada</p>
                    <?php } ?>
                </div>
            </div>



            <div class=" container box-i">
                <div class="header-text">
                    <h3 class="text-center">Buku</h3><br>
                </div>

                <div class="box-item-index">
                    <?php 
                    if(mysqli_num_rows($produk) > 0){
                    while($data = mysqli_fetch_assoc($produk)){ ?>
                    <a href="single.php?idp= <?= $data['produk_id'] ?>">
                        <div class="col-4">
                            <a href="single.php?idp= <?= $data['produk_id'] ?>">
                                <img src="gambar-p/<?= $data['produk_gambar']; ?>" class="img-pr"> </a>

                            <a href="single.php?idp= <?= $data['produk_id'] ?>" class="index-produk-item text-center">
                                <?= $data['produk_nama']; ?>
                            </a>
                            <div class="harga-item-produk">
                                <p>Rp. <?= number_format($data['produk_harga']) ?></p>
                            </div>

                            <form action="" method="post">
                                <div class="tombol-beli">
                                    <button type="button" class="btn btn-success btn-beli" data-bs-toggle="modal"
                                        data-bs-target="#order-modal<?= $data['produk_id'] ?>">
                                        Beli
                                    </button>
                                    <button type="button" class="btn btn-info btn-cart" data-bs-toggle="modal"
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
                                                        <input type="hidden" name="stokp"
                                                            value="<?= $data['produk_stok'] ?>">
                                                    </span>


                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idp[]" value="<?= $data['produk_id'] ?>">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tidak</button>
                                                    <input type="submit" name="beli" class="btn btn-primary"
                                                        value="Beli">


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

                        <?php }} else{ ?>
                        <p>Data Tidak Ada</p>
                        <?php } ?>
                </div>
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
            </div>
    </section>


    <ul id="KLL">
        <li style="display:none">
            Anjing
        </li>
        <li style="display:none">
            asdsad
        </li>
        <li style="display:none">
            asdsads
        </li>
    </ul>


    <p id="KKK" style="display:none">
        asdsad
    </p>

    <script>
    k = document.getElementById('KKK');

    ul = document.getElementById('KLL');
    li = ul.getElementsByTagName('li');
    te = li.length;
    all = document.querySelectorAll("li");

    for (i = 0; i < all.length; i++) {
        if (all.every.style.display == "none") {
            alert("KONTOL");
        }
    }


    // for (i = 0; i < li.length; i++) {
    //     if (li[i].style.display == "none") {
    //         alert("KONTOL")
    //     } else {
    //         li[i].style.display = "none";
    //     }
    // }
    </script>
</body>