<?php
    require_once 'init/init.php';

    session_destroy();

    echo '<script>alert("Berhasil Logout")</script>';
    echo '<script>window.location="login.php"</script>';


?>