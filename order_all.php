<?php   
require_once 'init/init.php';
include 'auth.php';


if(($_SESSION['order'] == [])){
    header('location:index.php');
}

$id = $_SESSION['id'];
$total = 0;

if(isset($_POST['beli'])){
    $ido  = $_POST['ido'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jmlh = $_POST['jumlah_order'];
    $uang = $_POST['uang'];
    $total = $_POST['total'];


    $hitung = count($ido);
    echo $hitung;
    
    if($uang < $total){
    echo '<script>alert("Uang yang anda miliki tidak cukup!")</script>';

    }else{

    for($x = 0; $x < $hitung; $x++){
        mysqli_query($conn, "UPDATE tb_produk SET produk_stok = (SELECT produk_stok - '$jmlh[$x]') WHERE produk_id = '$ido[$x]'  " );
        mysqli_query($conn, "UPDATE user SET uang = (SELECT uang - '$total') WHERE id = '$id' ");
        mysqli_query($conn, "INSERT INTO riwayat (produkr_id, user_id, produk_jumlah ) VALUES ('$ido[$x]', '$id', '$jmlh[$x]') ");


    };


    $_SESSION['order'] = [];
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $id");

    echo '<script>alert("Berhasil membeli Produk!")</script>';
    echo "<script>window.location='index.php'</script>";
}
}

$user = mysqli_query($conn, "SELECT * FROM user WHERE id = '$id'");

$uang_user = mysqli_fetch_object($user);


require_once 'header.php';


?>

<link rel="stylesheet" href="style.css">

<!-- <form action="cari.php" method="post">
    <input type="search">
</form> -->


<body>
    <section>
        <div class="container">
            <h2 class="text-center">Beli Produk</h2>
            <div class="box-k">
                <form action="" method="post">
                    <table class="table table-hover table-striped table-bordered text-center">
                        <thead>

                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php 
                            $no = 1; 
                            $total = 0;
                            if(isset($_SESSION['order']) > 0){
                            foreach($_SESSION['order'] as $key => $val):
                                $total += $val['harga']*$val['jumlah'];
                            ?>
                                <th scope="row"><?= $no++ ?> <input type="hidden" name="ido[]"
                                        value="<?= $val['id'] ?>">
                                </th>
                                <td><img src="gambar-p/<?= $val['gambar'] ?>" alt="" width="50px"></td>
                                <td><input type="hidden" name="nama" value="<?= $val['nama'] ?>"> <?= $val['nama'] ?>
                                </td>
                                <td><input type="hidden" name="harga" value="<?= $val['harga'] ?>"> Rp.
                                    <?= number_format($val['harga']) ?></td>
                                <td>
                                    <span class="text-jumlah">
                                        <p> <?= $val['jumlah'] ?></p>
                                        <input type="hidden" class="form-control"
                                            style="width:5rem; margin-left: 0.5rem; padding-right:3px"
                                            name="jumlah_order[]" id="" value="<?= number_format($val['jumlah']) ?>"
                                            min="1" max="<?= number_format($val['stok']) ?>">

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach;} else header('Location:index.php');  ?>
                        </tbody>


                    </table>

                    <input type="submit" name="beli" style="float:right; margin-bottom:10px; margin-left:1rem"
                        class=" btn btn-success" value="BELI">
                    <a style="float:right; margin-bottom:10px" href="remove-orderan.php" class="btn btn-danger"
                        onclick="return confirm('Anda yakin ingin membatalkan pesanan?')">BATAL</a>
                    <h5 style="margin-top:2rem">Total : Rp. <?= number_format($total) ?></h4>
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <input type="hidden" name="uang" value="<?= $uang_user->uang ?>">

                </form>

            </div>
        </div>
        <h3 style="margin-top:1rem; margin-left:7.9rem;">Rekening Anda : Rp. <?= number_format($uang_user->uang) ?></h3>

    </section>
</body>