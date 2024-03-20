<?php
    require_once 'init/init.php';
    // include 'auth.php';

    if(isset($_GET['idp'])){
        $id = $_GET['idp'];
        $query = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_id = $id ");
        $data  = mysqli_fetch_object($query);
        unlink('./gambar-p/'.$data->produk_gambar);
        mysqli_query($conn, "DELETE FROM tb_produk WHERE produk_id = $id");
        mysqli_query($conn, "DELETE FROM genre WHERE produk_id = $id");
        echo '<script>alert("Produk behasil dihapus")</script>';
        echo '<script>javascript:history.go(-1)</script>';
    }
  

if(isset($_GET['idg'])){
    $id = $_GET['idg'];
    $query = mysqli_query($conn, "DELETE FROM tb_genre WHERE genre_id = $id ");
    echo '<script>alert("Genre behasil dihapus")</script>';
    echo '<script>window.location="daftar-genre.php"</script>';

}

if(isseT($_GET['idc'])){
    $id = $_GET['idc'];
    $query = mysqli_query($conn, "DELETE FROM keranjang WHERE produkC_id = $id");
    echo '<script>alert("Behasil dihapus dari keranjang!")</script>';
    echo '<script>window.location="keranjang.php"</script>';
}

if(isseT($_GET['idca'])){
    $id = $_GET['idca'];
    $query = mysqli_query($conn, "DELETE FROM keranjang");
    echo '<script>alert("Behasil mereset dari keranjang!")</script>';
    echo '<script>window.location="keranjang.php"</script>';

}

if(isseT($_GET['idcg'])){
   
    $_SESSION['cart'] = [];
    echo '<script>alert("Behasil mereset dari keranjang!")</script>';
    echo '<script>window.location="keranjangGuest.php"</script>';

}
if(isseT($_GET['idcgi'])){
    $id  = $_GET['idcgi'];

    $cart = $_SESSION['cart'];
    $k = array_filter($cart, function ($var) use ($id) {
        return ($var['idc']==$id);
    });

    foreach($k as $key => $value){
        unset($_SESSION['cart'][$key]);
    }

    echo '<script>alert("Behasil dihapus dari keranjang!")</script>';
    echo '<script>window.location="keranjangGuest.php"</script>';

}




?>