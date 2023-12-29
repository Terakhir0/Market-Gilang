<?php

    function login($user, $pass){

        global $conn;

        $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$user' AND password = '".MD5($pass)."' ");

        if(mysqli_num_rows($query) != 0){
            $d = mysqli_fetch_object($query);
                $_SESSION['login']  = true;
                $_SESSION['global'] = $d;
                $_SESSION['id']     = $d ->id;
                return true;
        }else   return false;

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