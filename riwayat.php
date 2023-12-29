<?php   
require_once 'init/init.php';
include 'auth.php';
error_reporting(0);
$_SESSION['order'] = [];


$cari = $_GET['search'];

$id = $_SESSION['id'];


$search = '';

if(isset($_GET['search'])){
    $search = "AND produk_nama LIKE '%$cari%' OR tanggal_beli LIKE '%$cari%' ";
}


$data = "SELECT * FROM riwayat INNER JOIN tb_produk ON produkr_id = produk_id WHERE user_id = $id $search ORDER BY tanggal_beli DESC";

$perpage = 10;
$start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;

$riwayat = data_pagination($data);

$pages = total_data_pagination($data);



// $perpage = 10;
// $start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;




// $riwayat = mysqli_query($conn, "SELECT * FROM riwayat INNER JOIN tb_produk ON produkr_id = produk_id WHERE user_id = $id $search ORDER BY tanggal_beli DESC LIMIT $start, $perpage ");


// $all = mysqli_query($conn, "SELECT * FROM riwayat INNER JOIN tb_produk ON produkr_id = produk_id WHERE user_id = $id $search ");
// $total = mysqli_num_rows($all);

// $pages = ceil($total/$perpage);

require_once 'header.php';

// $pagination = new Pagination('riwayat');


?>

<link rel="stylesheet" href="style.css">

<!-- <form action="cari.php" method="post">
    <input type="search">
</form> -->

<body>

    <section>
        <div class="container">
            <h2 class="text-center">Daftar Riwayat</h2><br>
            <div class="box-k">
                <div class="input-group">
                    <form action="riwayat.php" method="get">
                        <input type="text" class="form-control input-cari" name="search"
                            placeholder="Cari riwayat disini..."
                            value="<?= (isset($_GET['search']) != '')? $_GET['search'] : ''  ?>">
                    </form>

                </div>
                <table class="table table-hover table-striped table-bordered text-center">
                    <thead>

                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 0 + $start; 
                            $total = 0;
                            if(mysqli_num_rows($riwayat) > 0){
                            while($row = mysqli_fetch_array($riwayat)):
                                $dt = date_create($row['tanggal_beli']);
                                $no++
                            ?>
                        <tr class="baris">

                            <th scope="row">
                                <?= $no ?>
                            </th>
                            <td><?= $row['produk_nama'] ?></td>
                            <td>Rp. <?= number_format($row['produk_harga']) ?></td>
                            <td>
                                <span class="text-jumlah">
                                    <?=  $row['produk_jumlah'] ?>
                                </span>
                            </td>
                            <td>
                                <?=date_format($dt, 'Y-m-d H:i') ?>
                            </td>
                        </tr>

                        <?php
                        $jml = $row['produk_harga'] * $row['produk_jumlah'];
                        $total += $jml ;
                        endwhile;} else{ ?>
                        <tr>
                            <td colspan="6">Riwayat Masih Kosong</td>
                        </tr>
                        <?php } ?>


                    </tbody>
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

</body>