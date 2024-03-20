<?php
class Conn{
    public function __construct()
    {
        require_once '/init/db.php';

    }
}

  function login($user, $pass){
            
    global $conn;
    
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$user' AND password = '".MD5($pass)."' ");

        if(mysqli_num_rows($query) != 0){
            $d = mysqli_fetch_object($query);
                $_SESSION['login']  = true;
                $_SESSION['global'] = $d;
                $_SESSION['id']     = $d ->id;

                $id = $_SESSION['id'];

                $produk = mysqli_query($conn, "SELECT * FROM tb_produk INNER JOIN keranjang ON produk_id = produkC_id INNER JOIN user ON user_id=id WHERE id = '$id'");

                $isi_cart = mysqli_num_rows($produk);

                if(isset($_SESSION['cart'])){
          

                    if(keranjangLogin($id, $isi_cart) == true){
                        echo '<script>alert("Berhasil Menabah Keranjang")</script>';    
        
                    };
            }
            
            return true;

                
        }else   {
            return false;
        }

      
      
 

 function keranjangLogin($id, $isi_cart){
    global $conn;
    

    
    
    foreach($_SESSION['cart'] as $data){
        $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE produkC_id = $data[idc]");

        if(mysqli_num_rows($cek) > 0 ){
            echo '<script>alert("Barang sudah ada di dalam keranjang!")</script>';
            return header('Location:index.php');


        } 
        else{
        mysqli_query($conn, "INSERT INTO keranjang (produkC_id, user_id, jumlah) VALUES ('$data[idc]', '$id', '$data[jumlah]' )");
        $isi_cart++;
        if($isi_cart == 10){
            break;
        
        }

        unset($_SESSION['cart']);
        }

        // return true;
    
}
}

}

    
    function keranjang($idc, $id, $jmlh){

        global $conn;

        $penuh = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id = $id");


        $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE produkC_id = $idc");

    if(mysqli_num_rows($cek) > 0 ){
        echo '<script>alert("Barang sudah ada di dalam keranjang!")</script>';


    } else if(mysqli_num_rows($penuh) == 10){
        echo '<script>alert("Keranjang sudah penuh!")</script>';

    }
    else{
    mysqli_query($conn, "INSERT INTO keranjang (produkC_id, user_id, jumlah) VALUES ('$idc', '$id', $jmlh )");
    header('Location:index.php');
    }
    }

    function keranjangGuest($idc, $jmlh){
        global $conn;

        $query = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_id = '$idc'");
        $fetch = mysqli_fetch_object($query);

        if(isset($_SESSION['cart'])){
        $cek = array_column($_SESSION['cart'], 'idc');
        if(in_array($idc, $cek)){
            echo '<script>alert("Barang sudah ada di dalam keranjang!")</script>';

        } else if (count($cek) == 10){
            echo '<script>alert("Keranjang sudah penuh!")</script>';

        } else {
            $keranjang = [
                'idc' => $idc,
                'gambar' => $fetch->produk_gambar,
                'nama' => $fetch->produk_nama,
                'harga' => $fetch->produk_harga,
                'stok' => $fetch->produk_stok,
                'jumlah' => $jmlh,
            ];

            $_SESSION['cart'][] = $keranjang;
            echo '<script>alert("Berhasil Menabah Keranjang")</script>';    
            header('Location:index.php');
        }
    } else {
        $keranjang = [
            'idc' => $idc,
            'gambar' => $fetch->produk_gambar,
            'nama' => $fetch->produk_nama,
            'harga' => $fetch->produk_harga,
            'stok' => $fetch->produk_stok,
            'jumlah' => $jmlh,
        ];

        $_SESSION['cart'][] = $keranjang;
        echo '<script>alert("Berhasil Menabah Keranjang")</script>';    
        header('Location:index.php');
    }
    
    }


    function order($idp, $jmlh){

        global $conn;

        
        if(isset($_SESSION['order'])){
            $ida = count($idp);
            for($i = 0; $i < $ida; $i++){
            $data = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_id IN ($idp[$i]) ");
            $p = mysqli_fetch_array($data);
            $order = [
                'id'     => $p['produk_id'],
                'nama'   => $p['produk_nama'],
                'gambar' => $p['produk_gambar'],
                'harga'  => $p['produk_harga'],
                'jumlah' => $jmlh[$i],
                'stok'   => $p['produk_stok']
            ];
            

            $_SESSION['order'][] = $order;
            
        }
            return true;

        }else{
            $ida = count($idp);
            for($i = 0; $i < $ida; $i++){
            $data = mysqli_query($conn, "SELECT * FROM tb_produk WHERE produk_id IN ($idp[$i]) ");
            $p = mysqli_fetch_array($data);
            $order = [
                'id'     => $p['produk_id'],
                'nama'   => $p['produk_nama'],
                'gambar' => $p['produk_gambar'],
                'harga'  => $p['produk_harga'],
                'jumlah' => $jmlh[$i],
                'stok'   => $p['produk_stok']

            ];
            

            $_SESSION['order'][] = $order;
            
        }
            return true;
        }
    }


    function current_page(){
        return isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
  }

    function data_pagination($data){

        global $conn;

        $perpage = 10;
        $start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;


        return mysqli_query($conn, " $data LIMIT $start, $perpage ");
    

    }

    function data_pagination_index($data){

        global $conn;

        $perpage = 15;
        $start = (current_page() > 1) ? (current_page() * $perpage) - $perpage : 0;


        return mysqli_query($conn, " $data LIMIT $start, $perpage ");
    

    }


    function total_data_pagination_index($total_data){
        
        global $conn;

        $perpage = 15;
        $all = mysqli_query($conn, $total_data);
        $total = mysqli_num_rows($all);

        return ceil($total/$perpage);
    }

    function total_data_pagination($total_data){
        
        global $conn;

        $perpage = 10;
        $all = mysqli_query($conn, $total_data);
        $total = mysqli_num_rows($all);
        // $total = count($total_data);

        return ceil($total/$perpage);
    }

    function start_page($page){
        $perpage = 10;
        if($page > 1){
             $start = ($page * $perpage) - $perpage;
             return $start;
        } else return 0;
        // $start = ($page > 1) ? ($page * $perpage) - $perpage : 0;
        // return $start;
    }

    // function pages($total){
    //     $perpage = 10;

    //     $pages = ceil($total/$perpage);

    //     return $pages;
    // }

    function check_search($cari){

        if(isset($_GET['search'])){
            return '&search='.$cari ;
        }

        if(isset($_GET['cari'])){
            return '&cari='.$cari ;
        }

        if(isset($_GET['genre'])){
            return '&genre='.$cari;
        }

        if(isset($_GET['genres'])){
            return '&genres='.$cari;
        }

        return '';
    }

    

    function prev_page(){
        return (current_page() > 1 ? current_page()-1 : 1);
    }

    function next_page($pages){
        return (current_page() < $pages ? current_page()+1 : $pages);
    }

    function is_showable($pages, $num){
        if($pages < 4 || $num == 1 || $num == $pages || current_page() == $num || (current_page()-2) <= $num && (current_page()+2) >= $num )
            return true;
        
    }



    function multiSearchGenre($data, $genres){
        $results = array();
        foreach($data as $items){
            if(array_diff($genres, $items['genres']) === array()){
                $results[] = $items['id'];
            }
        }

        return $results;
    }
    
    function cleaning($string) {
        $string = str_replace(' ', '-', $string); // ganti spasi dengan tanda baca "-".
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens(tanda baca) with single one.
     }

        // class Pagination
        // {
        //     private $db, $table, $total_records, $limit = 10;


        //     public function __construct($table) 
        //     {
        //         $this->db = require_once 'init/db.php';
        //         $this->table = $table;
        //         $this->set_total_records();
        //     }

        //     public function set_total_records()
        //     {   
        //         $stmt = $this->db->prepare("SELECT id FROM $this->table");
        //         $stmt->execute();
        //         $this->total_records = $stmt->rowCount();
        //     }


        //     public function get_data()
        //     {
        //         $start = 0;

        //         if($this->current_page() > 1 )
        //         {
        //             $start = ($this->current_page() * $this->limit) - $this->limit;
        //         }
        //     }

        //     public function current_page(){
        //         return isset($_GET['page']) ? (int)$_GET['page'] : 1;
        //     }
        // }


     

?>