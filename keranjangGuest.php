<?php   
require_once 'init/init.php';
// include 'auth.php';

$_SESSION['order'] = [];







if(isset($_POST['beli'])){
    if(isset($_SESSION['login']) != true){
        echo '<script>alert("Silahkan login terlebih dahulu")</script>';
        echo '<script>window.location="login.php"</script>';
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
                            if(count($_SESSION['cart']) > 0){
                            foreach($_SESSION['cart'] as $data):
                                
                               
                            ?>
                                <th scope="row">
                                    <input type="hidden" name="idp[]" value="<?= $data['idc'] ?>">
                                    <?= $no++ ?>
                                </th>
                                <td><img src="gambar-p/<?= $data['gambar'] ?>" alt="" width="50px"></td>
                                <td><?= $data['nama'] ?></td>
                                <td>Rp. <?= number_format($data['harga']) ?></td>
                                <td>
                                    <span class="text-jumlah">
                                        <input type="number" class="form-control"
                                            style="width:5rem; margin-left: 0.5rem; padding-right:3px"
                                            name="jumlah_beli[]" min="1" max="<?= $data['stok'] ?>" id=""
                                            value="<?= number_format($data['jumlah']) ?>" required>

                                        <input type="hidden" name="stokp" value="<?= $data['stok'] ?>">
                                    </span>
                                </td>
                                <td>
                                    <a href="delete.php?idcgi=<?=$data['idc']?>" class="btn btn-danger"
                                        onclick="return confirm('Anda yakin ingin menghapus produk dari keranjang?')">HAPUS</a>
                                </td>
                            </tr>

                            <?php
                        $jml = $data['harga'] * $data['jumlah'];
                        $total += $jml ;
                        endforeach;} else{ ?>
                            <tr>
                                <td colspan="6">Keranjang Masih Kosong</td>
                            </tr>
                            <?php } ?>

                    </tbody>

                    <a style="float:right; margin-bottom:10px" href="delete.php?idcg" class="btn btn-danger"
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