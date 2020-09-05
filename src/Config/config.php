<?php

/*
|--------------------------------------------------------------------------
| Database configuration
|--------------------------------------------------------------------------
|
*/

$config['host'] =  "localhost";
$config['user']  = "root";
$config['password'] =  "SBJuser@9895";
$config['dbname'] = "dev01";
$config['port'] =  "";
$config['charset'] =  "";



$config['base_url'] = APP_PATH;
$config['upload_path'] = UPLOAD_PATH;

/*
|--------------------------------------------------------------------------
| Constant Define
|--------------------------------------------------------------------------
|
*/

define("DIAMOND_PARAM",["Shape","Color","Clarity","Cut","Polish","Sysmmetry","Fluorescence","Certificate","H&A","Milky","Green","Brown","Other Things","Status","Key To Symbol","Girdle","Gird Condition","Culet","Table Black","Side Black","Table Spot","Side Spot","Table White","Side White","Open Inc Table","Open Inc Pavilion","Open Inc Crown","Open Inc Gridle","Ex Facet Table","Ex Facet Pavilion","Ex Facet Crown","Media","Special Cut","Internal Color","Internal Clarity","Internal Comment","Size Cts Defination","Country of Origin","Multiple Stone Group","Internal Status","Treatment","Brands","Eligibility","Size Group","Location","White In Center","White In Side","Black In Center","Black In Side","Inscribed"]);
define("MEMBER_TYPE_LIST",[["name"=> 'Employee','id' => 12],["name"=> 'Customer', 'id' => 15],["name"=> 'Vendor', 'id' => 16]]);
define("DIAMOND_TABLE_LIST",[["id" => "0", "name"=> "Shape"],["id" => "2", "name" => "Color"],["id" => "3", "name" => "Clarity"],["id" => "5", "name" => "Cut"],["id" => "6", "name" => "Polish"],["id" => "7", "name" => "Sysmmetry"],["id" => "4", "name" => "Fluorescence"],["id" => "1", "name" => "Certificate"],["id" => "8", "name" => "H&A"],["id" => "9", "name" => "Milky"],["id" => "10" , "name" => "Green"],["id" => "11" , "name" => "Brown"],["id"=> "12" , "name" => "Other Tinge"],["id" => "13" , "name" => "Status"],["id" => "14" , "name" => "Key To Symbol"],["id" => "15" , "name" => "Girdle"],["id" => "16" , "name" => "Gird Condition"],["id" => "17" , "name" => "Culet"],["id" => "18" , "name" => "Table Black"],["id" => "19" , "name" => "Side Black"],["id" => "20" , "name" => "Table Spot"],["id" => "21" , "name" => "Side Spot"],["id" => "22" , "name" => "Table White"],["id" => "23" , "name" => "Side White"],["id" =>"24" , "name" => "Open Inc Table"],["id" =>"25" , "name" => "Open Inc Pavilion"],["id" =>"26" , "name" => "Open Inc Crown"],["id" =>"27" , "name" => "Open Inc Gridle"],["id" =>"28" , "name" => "Ex Facet Table"],["id" =>"29" , "name" => "Ex Facet Pavilion"],["id" =>"30" , "name" => "Ex Facet Crown"],["id" => "31" , "name" => "Media"],["id" => "32" , "name" => "Special Cut"],["id" => "33" , "name" => "Internal Color"],["id" => "34" , "name" => "Internal Clarity"],["id" => "35" , "name" => "Internal Comment"],["id" => "36" , "name" => "Size Cts Defintion"],["id" => "37" , "name" => "Country Of Origin"],["id" => "38" , "name" => "Multiple Stone Group"],["id" => "39" , "name" => "Internal Status"],["id" => "40" , "name" => "Treatment"],["id" => "41" , "name" => "Brands"],["id" => "42" , "name" => "Eligibility"],["id" => "43" , "name" => "Size Group"],["id" => "44" , "name" => "Location"] ] );
define("PASSPHRASE","123456");
define("ENCRYTKEY","chkchk");
define("SITE_URL","http://dev02.suranabrother.com/index.php/");
define("COMPANY_STATUS",[ "Request" ,  "Approved",  "Rejected",  "Blocked" ,  "Send invitation",  "In process",  "Kyc verification"]);
?>
