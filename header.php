<?php
    require_once 'init/init.php';
    error_reporting(0);

    if(isset($_SESSION['login']) == true){
        $id = $_SESSION['id'];


        $query = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");

        $data = mysqli_fetch_object($query);

        $count = mysqli_query($conn, "SELECT COUNT(produkC_id) AS isi FROM keranjang WHERE user_id = '$id'");
        $semua = mysqli_fetch_assoc($count);
        $val = $semua['isi'];
    } else {
        $val = count($_SESSION['cart']);
    }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<img src="gambar/loader.gif" alt="" id="loader">

<header>
    <nav class="navbar navbar-expand navbar-dark" style="background-color: #002f86ce;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto me-6 mb-2 mb-lg-0">
                    <li class="nav-item pe-4">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item pe-4">
                        <?php if(isset($val) > 0){
                            ?>
                        <a class="nav-link"
                            href="<?= ($_SESSION['login'] == true) ? 'keranjang.php' : 'keranjangGuest.php'; ?>">Keranjang
                            (<?= $val ?>)</a>
                        <?php } else{ ?>
                        <a class="nav-link"
                            href="<?= ($_SESSION['login'] == true) ? 'keranjang.php' : 'keranjangGuest.php'; ?>">Keranjang</a>

                        <?php } ?>
                    </li>

                    <?php if(isset($_SESSION['login']) == true) {   $id = $_SESSION['id'];
                                $user = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");                                   
                                $data = mysqli_fetch_object($user);
                        ?>


                    <li class="nav-item">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">

                                <a class="nav-link dropdown-toggle me-4" href="#" id="navbarDarkDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Tambah
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark"
                                    aria-labelledby="navbarDarkDropdownMenuLink" style="min-width:7rem">
                                    <li><a class="dropdown-item" href="tambah-produk.php">Produk</a></li>
                                    <li><a class="dropdown-item" href="genre.php">Genre</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="profil.php"><img src="gambar/<?= $data->gambar ?>" alt=""
                                class="profil-p"></a>
                    </li>

                    <li class="nav-item">

                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">

                                <a class="nav-link dropdown-toggle me-4" href="#" id="navbarDarkDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                       echo  $data->nama;
                                    ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark"
                                    aria-labelledby="navbarDarkDropdownMenuLink"
                                    style="min-width:6rem; top:2.5rem; margin-left:-2rem">
                                    <li><a class="dropdown-item" href="profil.php?idu=<?= $data->id;?>">Profile</a>
                                    </li>
                                    <li><a class="dropdown-item" href="daftar-produk.php">Daftar Produk</a></li>
                                    <li><a class="dropdown-item" href="daftar-genre.php">Daftar Genre</a></li>
                                    <li><a class="dropdown-item" href="riwayat.php">Daftar Riwayat</a></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <?php } else { ?>
                        <a href="login.php" class="nav-link" id="login-btn">Login</a>
                    </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>

</header>

<div class="search  shadow-sm" style="background-color: #002f86ce;">
    <div class="container" style="margin-left:0">
        <form class="d-flex" action="cari.php" method="get">
            <a class="logo" href="#">
                <h3>Market Gilang</h3>
            </a>
            <input type="search" class="form-control me-1 input-cari" name="search" id="cari"
                placeholder="Cari disini..." value="<?= (isset($_GET['search']) != '')? $_GET['search'] : ''  ?>"
                required>
            <input type="submit" class="btn btn-outline-primary" value="Cari">
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous">
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script> -->
<script src="script.js"></script>
<script>
$('.nav-link').on('click', function() {
    $('.nav-link').removeClass('active');
    $(this).addClass('active');
})
</script>

</html>