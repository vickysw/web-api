<?php 

namespace App\Core;

class NotFound{


    public static function notFound($name = ""){

        $message = "";
        $res["success"] = 0;
        $res["status_code"]   = 404;
        $res["message"]   = $name." Class missing";
           
            header("Content-Type: application/json;");
            echo json_encode($res);
            unset($res);
            die;
    }

}
?>