<?php

namespace App\Core;

use App\Core\Loader as Loader;

if (isset($_SERVER["HTTP_ORIGIN"])) {
    header("Access-Control-Allow-Origin: {$_SERVER["HTTP_ORIGIN"]}");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 86400"); // cache for 1 day
}

if ( $_SERVER['REQUEST_METHOD']=='GET' ) {
    /* 
       Up to you which header to send, some prefer 404 even if 
       the files does exist for security
    */
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    /* choose the appropriate page to redirect users */
    exit( 'Bad method not allowed.' );

}
// Base Controller

class Controller{

    // Base Controller has a property called $loader, it is an instance of Loader class(introduced later)

    protected $loader;
    
    protected $controller;
    protected $action;

    protected $request;

    protected $validation = false;

    protected $postdata;

    protected $key;

    public function __construct(){
        
        $this->loader = new Loader();
        
        if(is_a($this,$_REQUEST['controller']))
        {
            $this->controller = $_REQUEST['controller'];
            $this->action = $_REQUEST['action'];
           
            if(!in_array($this->action,get_class_methods($this->controller))){
                header( '400 Not Found' );
                exit( 'Action not found' );
            }
            
            $this->request = $_REQUEST;
            
        }else{
            header( '400 Not Found' );
            exit( 'Class not found' );
        }
       
    }

    public function check_Validation($rules = [])
    {
        $this->postdata = $rules;
        if(!empty($rules)){
            foreach ($rules as $key => $val) {
                $_POST[$key] = $val['value'];
                $msg_arr = array();
                    if (array_key_exists("message_array", $val)) {
                        $msg_arr = $val["message_array"];
                    }
                    $this->set_rules($key, $val["rule"], $msg_arr);
            }
            if ($this->validation == false) {
                $data = array();
                $i = 0;
                foreach ($this->postdata as $key => $val) {
                    
                    $message[$i] = $val['error'];
                    if ($message[$i] != "") {
                        $fieldDigit = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                        $fieldName = rtrim($key, "1234567890");
                        $msg = "This filed is ".$message[$i];
                        $res["errorData"][] = array(
                            "index" => $fieldDigit ? $fieldDigit : $i,
                            "fieldName" => $fieldName,
                            "message" => $msg
                        );
                        $message[$i] = $msg;
                    }
                        $i++;
                 }
              if(!empty($res))   
                    respond_error_to_api("error", $res);
            }
          }
    }

    function set_rules($key,$rules,$msg = [])
    {
        $this->key = $key;
        
        $rules = explode('|',$rules);

        foreach($rules as $value)
        {
            if(method_exists($this,'_'.$value)){
                $this->{'_'.$value}();
            }
            else   
              respond_error_to_api("error","No validation rules defined.");

        }
    }

    /**  Make all the validation function here  */
    /**  For the Test purpose I have make a function _trim and _required. */
    /**  17 Aug 2020 */
    
    function _trim()
    {
        return trim($this->postdata[$this->key]['value']);
    }

    function _required()
    {
        $_bool = is_array($this->postdata[$this->key]['value']) ? (bool) count($this->postdata[$this->key]['value']) : (trim($this->postdata[$this->key]['value']) !== ''); 
        if(!$_bool){
            $this->validation = $this->validation ? true : false ;
            $this->postdata[$this->key]['error'] = "required";
        }

    }

    function _numeric()
    {
        $this->validation = is_numeric($this->postdata[$this->key]['value']) ? true : false ;
        $this->postdata[$this->key]['error'] = "numeric";
    }
    

  
}