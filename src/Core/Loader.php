<?php 

namespace App\Core;

class Loader{

    // Load library classes

    public function library($lib){

        require LIB_PATH . "$lib.php";

    }


    // loader helper functions. Naming conversion is xxx_helper.php;

    public function helper($helper){
        
        require HELPER_PATH . "{$helper}_helper.php";

    }

}
?>