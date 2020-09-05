<?php 

use App\Models\CommonModel as common;

function check_api_keys($keys, $mydata, $is_filter = false)
{
    $data = array();
    //filter keys array
    $keys = filter_input_param($keys);
    foreach ($keys as $index => $key) {
        if (array_key_exists($key, $mydata)) {
            if (is_array($mydata[$key])) {
                $data[$key] = $mydata[$key];
            } else {
                $data[$key] = encode_php_tags(strip_tags(trim($mydata[$key])));
                if ($is_filter)
                    $data[$key] = encode_php_tags((trim($mydata[$key])));
            }
        } else {
            $arr = array($key);
            $message = "key missing";
            $res["errorData"][] = array(
                "index" => $index,
                "fieldName" => $key,
                "message" =>  ucfirst(strtolower(str_replace("_", " ", $message)))
            );
            logto_debuglog("key missing" . json_encode($res, true));
            // pr($res,true);
            respond_error_to_api($message, $res);
        }
    }

    unset($keys);
    return $data;
}

function encode_php_tags($str)
{
    return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);
}
/**
 * @param $array
 * @return array
 * common function for filter inputted data
 */
function filter_input_param($array)
{
    $ret = array();
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $ret[$key] = filter_input_param($value);
            } else {
                $ret[$key] = trim($value);
                $ret[$key] = remove_strip_unsafe($value);
                $ret[$key] = strip_tags($value);
                $ret[$key] = stripslashes($value);
                $ret[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
                $ret[$key] = urldecode($value);
                $ret[$key] = htmlspecialchars($value);
                $ret[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }
    } else {
        $ret = trim($array);
        $ret = remove_strip_unsafe($array);
        $ret = strip_tags($array);
        $ret = stripslashes($array);
        $ret = htmlentities($array, ENT_QUOTES, 'UTF-8');
        $ret = urldecode($array);
        $ret = htmlspecialchars($array);
        $ret = filter_var($array, FILTER_SANITIZE_STRING);
    }

    return $ret;
}

/**
 * @param $string
 * @return string
 * common function for filter xss
 */
function remove_strip_unsafe($string, $img = false)
{
    $unsafe = array(
        '/<iframe(.*?)<\/iframe>/is',
        '/<title(.*?)<\/title>/is',
        '/<pre(.*?)<\/pre>/is',
        '/<frame(.*?)<\/frame>/is',
        '/<frameset(.*?)<\/frameset>/is',
        '/<object(.*?)<\/object>/is',
        '/<script(.*?)<\/script>/is',
        '/<embed(.*?)<\/embed>/is',
        '/<applet(.*?)<\/applet>/is',
        '/<meta(.*?)>/is',
        '/<!doctype(.*?)>/is',
        '/<link(.*?)>/is',
        '/<body(.*?)>/is',
        '/<\/body>/is',
        '/<head(.*?)>/is',
        '/<\/head>/is',
        '/onload="(.*?)"/is',
        '/onclick="(.*?)"/is',
        '/onunload="(.*?)"/is',
        '/ondblclick="(.*?)"/is',
        '/onmouseover="(.*?)"/is',
        '/on[^=]+="(.*?)"/is',
        '/<html(.*?)>/is',
        '/<script(.*?)>/is',
        '/<\/html>/is'
    );
    if ($img == true) {
        $unsafe[] = '/<img(.*?)>/is';
    }

    $string = preg_replace($unsafe, "", $string);
    return $string;
}


/**
 * @param $message message sent with success
 * @param $return_array array sent with success response
 * @return array
 * common function to respond as success
 */
function respond_success_to_api($message, $return_array = array(), $applyFilter = false)
{
    logto_debuglog("Respond Success To API [Message: " . $message . "]");
    $res["success"]       = 1;
    $res["status_code"]   = 200;
    $res["message"]       = str_replace("otp", "OTP", ucfirst(strtolower(str_replace("_", " ", $message))));
    $res["data"]          = $return_array;

    respond_to_api($res, $applyFilter);
}

function respond_error_to_api($message, $return_array = array(), $error_code = 400, $applyFilter = false)
{
    logto_debuglog("Respond Error To API [Message: " . $message . "]");
    $res["success"] = 0;
    $res["status_code"]   = $error_code;
    // Uppercase after dot
    preg_match_all("/\.\s*\w/", $message, $matches);
    foreach ($matches[0] as $match) {
        $message = str_replace($match, strtoupper($match), $message);
    }
    $res["message"] = str_replace("otp", "OTP", ucfirst(strtolower(str_replace("_", " ", $message))));
    $res["error"]   = empty($return_array) ? (object)[] : $return_array;
    respond_to_api($res, $applyFilter);
}

/**
 * @param $res
 * common function to send response and exit
 */
function respond_to_api($res, $applyFilter = false)
{
    //filter tags added in validation
    if ($applyFilter) {
        $res = filter_input_param($res);
    }

    // pr($res);
    logto_debuglog("Respond To API");
    header("Content-Type: application/json;");
    echo json_encode($res);
    unset($res);
    die;
}

/**
 *  For logging System
 */
function logto_debuglog($message)
{
    $str = $message . "  [". date("Y-m-d H:i:s") ."]" . PHP_EOL;

    $logfile = LOG_PATH.'debug_log_'.date("y-m-d").".php";

    if(file_exists($logfile))
        error_log($str, 3, $logfile);
    else{
        $logfile =fopen($logfile, "w");
        error_log($str, 3, $logfile);
    }     
}

/**
 *  17 Aug 2020
 *  Decrypt 
 */
function decrypt($string)
{
    $enc = explode(ENCRYTKEY, $string);
	
		$salt = hex2bin($enc[2]);
		$iv  = hex2bin($enc[1]);
	
	$ciphertext = base64_decode($enc[0]);
	$iterations = 999;
	$key = hash_pbkdf2("sha512",PASSPHRASE , $salt, $iterations, 64);
	$decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

    return $decrypted;
}

/**
 *  17 Aug 2020
 *  Encrypt 
 */
function encrypt($string)
{
    $salt = openssl_random_pseudo_bytes(256);
	$iv = openssl_random_pseudo_bytes(16);
	$iterations = 999;
	$key = hash_pbkdf2("sha512", PASSPHRASE, $salt, $iterations, 64);
	$encrypted_data = openssl_encrypt($string, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);
	$data = array("ciphertext" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "salt" => bin2hex($salt));

	$data = base64_encode($encrypted_data) . ENCRYTKEY . bin2hex($iv) . ENCRYTKEY . bin2hex($salt);
	// 0=ciphertext,1=iv,2=salt
	return $data;
}

function ConvertDate($date)
{
    $co_to_date='';
    if($date!='')
    {
        $co_to_date = explode("GMT", $date);
        $co_to_date = strtotime($co_to_date[0]);
        $co_to_date = date("Y-m-d", $co_to_date);
     
    }
    return $co_to_date;
}
?>
