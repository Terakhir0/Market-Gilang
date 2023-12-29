<?php   
require_once 'init/init.php';
include 'auth.php';

$_SESSION['order'] = [];



$id = $_SESSION['id'];

$produk = mysqli_query($conn, "SELECT * FROM tb_produk INNER JOIN keranjang ON produk_id = produkC_id INNER JOIN user ON user_id=id WHERE id = '$id'");



if(isset($_POST['beli'])){
    $idp = $_POST['idp'];
    $jmlh = $_POST['jumlah_beli'];
    $stk = $_POST['stokp'];

    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE produkC_id = $idc");

   if(mysqli_num_rows($cek) > 0){
    echo '<script>alert("Barang sudah ada di dalam keranjang!")</script>';

    }else{
        order($idp, $jmlh, $stk);
        header('Location:order_all.php');
    
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
            <h2 class="text-center">Daftar Keranjang</h2><br>
            <div class="box-k">

                <table class="table table-hover table-striped table-bordered text-center">
                    <thead>

                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="" method="post">
                            <tr>
                                <?php 
                            $no = 1; 
                            $total = 0;
                            if(mysqli_num_rows($produk) > 0){
                            while($data = mysqli_fetch_array($produk)):
                                
                               
                            ?>
                                <th scope="row">
                                    <input type="hidden" name="idp[]" value="<?= $data['produk_id'] ?>">
                                    <?= $no++ ?>
                                </th>
                                <td><img src="gambar-p/<?= $data['produk_gambar'] ?>" alt="" width="50px"></td>
                                <td><?= $data['produk_nama'] ?></td>
                                <td>Rp. <?= number_format($data['produk_harga']) ?></td>
                                <td>
                                    <span class="text-jumlah">
                                        <input type="number" class="form-control"
                                            style="width:5rem; margin-left: 0.5rem; padding-right:3px"
                                            name="jumlah_beli[]" min="1" max="<?= $data['produk_stok'] ?>" id=""
                                            value="<?= number_format($data['jumlah']) ?>" required>

                                        <input type="hidden" name="stokp" value="<?= $data['produk_stok'] ?>">
                                    </span>
                                </td>
                                <td>
                                    <a href="delete.php?idc=<?=$data['produkC_id']?>" class="btn btn-danger"
                                        onclick="return confirm('Anda yakin ingin menghapus produk dari keranjang?')">HAPUS</a>
                                </td>
                            </tr>

                            <?php
                        $jml = $data['produk_harga'] * $data['jumlah'];
                        $total += $jml ;
                        endwhile;} else{ ?>
                            <tr>
                                <td colspan="6">Keranjang Masih Kosong</td>
                            </tr>
                            <?php } ?>

                    </tbody>

                    <a style="float:right; margin-bottom:10px" href="delete.php?idca=" class="btn btn-danger"
                        onclick="return confirm('Anda yakin ingin mereset keranjang?')">Reset</a>
                </table>


                <input type="submit" style="float:right; margin-bottom:10px" name="beli" class="btn btn-success"
                    value="BELI">


                <h5 style="margin-top:2rem">Total : Rp. <?= number_format($total) ?></h4>
                    </form>
            </div>
        </div>
    </section>
</body>