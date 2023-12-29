<?php
    require_once 'init/init.php';

    if(isset($_SESSION['login']) != true){
        echo '<script>alert("Silahkan login terlebih dahulu")</script>';
        echo '<script>window.location="login.php"</script>';
    }

?>