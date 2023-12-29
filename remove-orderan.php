<?php
    require_once 'init/init.php';
        
    
if(isset($_SESSION['order'])){
    $_SESSION['order'] = [];
    header('Location:keranjang.php');

}



?>