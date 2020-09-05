<?php
namespace App\Core;

use App\Core\NotFound as Ntfound;


Class Main{

    public static function run() {

       // echo "run()";

       self::init();

       self::autoload();

       self::dispatch();
 
    }



    private static function init() 
    {

        // Define path constants
    
        define("DS", DIRECTORY_SEPARATOR);
    
        define("ROOT", getcwd() . DS);
    
        define("APP_PATH", ROOT . 'src' . DS);
    
        define("CONFIG_PATH", APP_PATH . "Config" . DS);
    
        define("CONTROLLER_PATH", APP_PATH . "Controllers" . DS);
    
        define("MODEL_PATH", APP_PATH . "Models" . DS);

        define("CORE_PATH", APP_PATH."Core" . DS);
    
        define('DB_PATH', CORE_PATH."Database" . DS);
    
        define("LIB_PATH", APP_PATH."libraries" . DS);
    
        define("HELPER_PATH", APP_PATH."helpers" . DS);
    
        define("UPLOAD_PATH", "uploads" . DS);
        
        //Log files
        define("LOG_PATH", UPLOAD_PATH. "logs" . DS);

        define("CONTROLLER", isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'Index');

        define("ACTION", isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index');
        
        // Load configuration file
        $GLOBALS['config'] = require CONFIG_PATH . "config.php";
    }


    // Autoloading

private static function autoload()
{
    spl_autoload_register(array(__CLASS__,'load'));

}


// Define a custom load method

    private static function load($classname)
    {
        // Here simply autoload app’s controller and model classes
        if(!strpos($classname,'\\')){
           
            if(file_exists(CONTROLLER_PATH.$classname.".php")){

                require_once CONTROLLER_PATH . "$classname.php";

            }else if(file_exists(MODEL_PATH.$classname.".php")) {

                require_once MODEL_PATH . "$classname.php";

            }
        }
    }

    // Routing and dispatching
    private static function dispatch()
    {
        // var_dump(get_declared_classes());
        // Instantiate the controller class and call its action method
        $controller_name = ucfirst(CONTROLLER); 
        
        $action_name = ACTION;
        // var_dump(class_exists($controller_name));    
        if(!class_exists($controller_name))
        {
            return  Ntfound::notFound($controller_name);
        }

        $controller = new $controller_name;
        
        $controller->$action_name();
    }

}
?>