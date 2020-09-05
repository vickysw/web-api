<?php
/**
 * @author Vanraj Kalsariya <sbh.user14@gmail.com>
 * @link https://tech.suranabrother.com/admin-panel/#/login 
 * 
 */
use  App\Models\CommonModel as common;
use App\Models\GemhubModel as GemhubModel;

class Gemhub extends App\Core\Controller
{
    private $mydata;
    private $model;
    public function __construct()
    {
        parent::__construct();
        
        $this->loader->helper('custom');
        $this->model = new GemhubModel("stock");
        $this->common = new common('stock');
        $this->mydata = json_decode(file_get_contents("php://input"), true);
        if (empty($this->mydata)) {
            $this->mydata   = $_POST;
        }
    }

    /**
     * Kalsariya Vanraj | 08 Aug 2020
     * For get  Proposal list
     */
    public function getLiveDiamondStock()
    {

        $keys = array("shape","clarity","color","cut","polish","symmetry","fluorescence","certificate","h_and_a","green","brown","milky","other_tinge","diamond_location","status","carat_min","carat_max","carat_group","per_dis_min","per_dis_max","inscribed","certi_number","per_carat_min","per_carat_max","total_usd_min","total_usd_max","length_min","length_max","width_min","width_max","depth_min","depth_max","depth_per_min","depth_per_max","table_per_min","table_per_max","pavilion_angle_min","pavilion_angle_max","pavilion_depth_min","pavilion_depth_max","star_length_min","star_length_max","crown_angle_min","crown_angle_max","crown_depth_min","crown_depth_max","lower_half_min","lower_half_max","gird_min","gird_max","girdle_per_min","girdle_per_max","gird_condition","culet_per_min","culet_per_max","culet","key_of_sym_type","key_of_symbol","white_in_center","white_in_side","black_in_center","black_in_side","no_open","no_black","is_admin","certi_date_from","certi_date_to","media_photo","media_video","media_lab_report","special_cut","internal_color","internal_clarity","internal_comments","size_cts_defination","country_of_origin","air_mark_for_customers","multiple_stone_group","legal_entity","sites","warehouse","war_location","certified_publish","internal_status","goods_in_transit","supplier_parcel_id","batch_no","serial_no","vendor_id","d_r","treatment_filter","treatment","brands","eligibility","flag","client_id","tenent_id","entity_id","per_page","page_no");
		$data =  check_api_keys($keys,$this->mydata);
        
        $string = '';
        if ($data['status'] != "" && $data['status'] != 'null') {
            $get_status = $this->model->get_filter_diamond_parameter($data['status'], 13);
            //echo $get_status;
            if($get_status){
                $data['status'] = " st_status IN($get_status) ";
            } else {
                $data['status'] = " st_status IN(0,1,4) ";
            }
        } else {
            $data['status'] = " st_status IN(0,1,4) ";
        }

        $get_cli_shape = [];
        $get_match = $this->model->check_user_price_quote_diamond_params($data['tenent_id'], $data['entity_id'], $data['client_id'], 0);
        if(count($get_match) > 0)
        {
            $getShape = $getClarity = $getColor = $getCut = $getPolish = $getSymmetry = $getFluorescence = $getCertificate = $getHA = $getMilky = $getBrown = $getGreen =  $getOther_tinge =  $getDiamond_location = '';

            if ($data['shape'] > 0) {
                $getShape = $this->model->get_filter_diamond_parameter($shape, 0);
            }
            if ($data['clarity'] > 0) {
                $getClarity = $this->model->get_filter_diamond_parameter($clarity, 3);
            }
            if ($data['color'] > 0) {
                $getColor = $this->model->get_filter_diamond_parameter($color, 2);
            }
            if ($data['cut'] > 0) {
                $getCut = $this->model->get_filter_diamond_parameter($cut, 5);
            }
            if ($data['polish'] > 0) {
                $getPolish = $this->model->get_filter_diamond_parameter($polish, 6);
            }
            if ($data['symmetry'] > 0) {
                $getSymmetry = $this->model->get_filter_diamond_parameter($symmetry, 7);
            } 
            if ($data['fluorescence'] > 0) {
                $getFluorescence = $this->model->get_filter_diamond_parameter($fluorescence, 4);
            }
            if ($data['certificate'] > 0) {
                $getCertificate = $this->model->get_filter_diamond_parameter($certificate, 1);
            }
            if ($data['h_and_a'] > 0) {
                $getHA = $this->model->get_filter_diamond_parameter($h_and_a, 8);
            }
            if ($data['milky'] > 0) {
                $getMilky = $this->model->get_filter_diamond_parameter($milky, 9);
            }
            if ($data['brown'] > 0) {
                $getBrown = $this->model->get_filter_diamond_parameter($brown, 11);
            }
            if ($data['green'] > 0) {
                $getGreen = $this->model->get_filter_diamond_parameter($green, 10);
            }
            if ($data['other_tinge'] > 0) {
                $getOther_tinge = $this->model->get_filter_diamond_parameter($other_tinge, 12);
            }
            if ($data['diamond_location'] > 0) {
                $getDiamond_location = $this->model->get_filter_diamond_parameter($diamond_location, 44);
            }

            $main_str = '';
            for($i=0;$i<count($get_match);$i++){
                $str = '';
                $str .= $this->model->return_diamond_filter($getShape,$get_match[$i]['shape'],'st_shape');

                $str .= $this->model->return_diamond_filter($getClarity,$get_match[$i]['clarity'],'st_cla');

                $str .= $this->model->return_diamond_filter($getColor,$get_match[$i]['color'],'st_col');

                $str .= $this->model->return_diamond_filter($getCut,$get_match[$i]['cut'],'st_cut');

                $str .= $this->model->return_diamond_filter($getPolish,$get_match[$i]['polish'],'st_pol');

                $str .= $this->model->return_diamond_filter($getSymmetry,$get_match[$i]['symmetry'],'st_sym');

                $str .= $this->model->return_diamond_filter($getFluorescence,$get_match[$i]['fluorescence'],'st_flou');

                $str .= $this->model->return_diamond_filter($getCertificate,$get_match[$i]['certificate'],'st_lab');

                $str .= $this->model->return_diamond_filter($getHA,$get_match[$i]['heart_and_arrow'],'st_heart_and_arrow');

                $str .= $this->model->return_diamond_filter($getMilky,$get_match[$i]['milky'],'st_milky');

                $str .= $this->model->return_diamond_filter($getBrown,$get_match[$i]['brown'],'st_brown');

                $str .= $this->model->return_diamond_filter($getGreen,$get_match[$i]['green'],'st_green');

                $str .= $this->model->return_diamond_filter($getOther_tinge,$get_match[$i]['othertinge'],'st_other_tinge');

                $str .= $this->model->return_diamond_filter($getDiamond_location,$get_match[$i]['location'],'st_location');


                // $per_dis_min 25
                // $per_dis_max 50

                $discount = explode('-',$get_match[$i]['discount']);
                if($discount){
                    $dis_from = $discount[0]; // 20
                    $dis_to = $discount[1]; // 30

                    echo " \n dis_from = ".$dis_from." \n dis_to = ".$dis_to." \n \n per_dis_min = ".$per_dis_min." \n per_dis_max = ".$per_dis_max." \n";
                } 

                if($dis_from!='' && $dis_to!='') {

                    $match_dis = range($dis_from, $dis_to);
                    $user_dis = range($per_dis_min, $per_dis_max);

                // print_r ($match_dis);
                // print_r ($user_dis);               
                    $result=array_intersect($match_dis,$user_dis);
                    $fis = count($result) - 1;

                    echo $fis." \n dis_from = ".$result[0]." \n dis_to = ".$result[$fis]." \n \n\n";

                    if( $dis_from >= $per_dis_min && $dis_to <= $per_dis_max ){
                        echo " \n in \n ";
                    } else {
                        echo " \n out \n ";
                    }
                    //$str .= " `st_dis` BETWEEN '$dis_from' AND '$dis_to' AND ";
                    }
                    $str = substr($str,0,-4);
            
                    if($str){
                        $main_str .= "(".$str.") OR ";
                    }
            } // end foreach

            $main_str = substr($main_str,0,-3);
            if ($carat_group != "" && $carat_group != 'null') {
                $get_carat_group = $this->model->get_filter_diamond_parameter($carat_group, 43, 1);
                    if($get_carat_group){
                        $ex_carat_group = explode(',',$get_carat_group);
                        for($i=0;$i<count($ex_carat_group);$i++){
                            $carat_separate_value = str_replace("'","",$ex_carat_group[$i]);
                            $separate_value = explode('-',$carat_separate_value);
                            $main_str .= " AND (st_size >= '$separate_value[0]' AND st_size <= '$separate_value[1]') ";
                        }   
                    }
            } else { 
                if(0 < $carat_min && 50 > $carat_max){
                    $main_str .= " AND (st_size >= '$carat_min' AND st_size <= '$carat_max') ";
                }
            }
            echo "\n*****\n".$main_str."\n*****\n";
            
            // $qry_select = "SELECT st_id FROM `stock`  ";
            $data['where'] = "st_is_delete = 0 AND $status AND $main_str";
        }else{
            if ($shape != "" && $shape != 'null') {
                $get_shape = get_filter_diamond_parameter($shape, 0);
                //echo $get_shape;
                if($get_shape){
                    $string .= " AND st_shape IN($get_shape) ";
                }
            }
        
            if ($clarity != "" && $clarity != 'null') {
                $get_clarity = get_filter_diamond_parameter($clarity, 3);
                //echo $get_clarity;
                if($get_clarity){
                    $string .= " AND st_cla IN($get_clarity) ";
                }
            }
    
            if ($color != "" && $color != 'null') {
                $get_color = get_filter_diamond_parameter($color, 2);
                //echo $get_color;
                if($get_color){
                    $string .= " AND st_col IN($get_color) ";
                }
            }
    
            if ($cut != "" && $cut != 'null') {
                $get_cut = get_filter_diamond_parameter($cut, 5);
                //echo $get_cut;
                if($get_cut){
                    $string .= " AND st_cut IN($get_cut) ";
                }
            }
    
            if ($polish != "" && $polish != 'null') {
                $get_polish = get_filter_diamond_parameter($polish, 6);
                //echo $get_polish;
                if($get_polish){
                    $string .= " AND st_pol IN($get_polish) ";
                }
            }
    
            if ($symmetry != "" && $symmetry != 'null') {
                $get_symmetry = get_filter_diamond_parameter($symmetry, 7);
                //echo $get_symmetry;
                if($get_symmetry){
                    $string .= " AND st_sym IN($get_symmetry) ";
                }
            }
    
            if ($fluorescence != "" && $fluorescence != 'null') {
                $get_fluorescence = get_filter_diamond_parameter($fluorescence, 4);
                //echo $get_fluorescence;
                if($get_fluorescence){
                    $string .= " AND st_flou IN($get_fluorescence) ";
                }
            }
    
            if ($certificate != "" && $certificate != 'null') {
                $get_certificate = get_filter_diamond_parameter($certificate, 1);
                //echo $get_certificate;
                if($get_certificate){
                    $string .= " AND st_lab IN($get_certificate) ";
                }
            }
    
            if ($green != "" && $green != 'null') {
                $get_green = get_filter_diamond_parameter($green, 10);
                //echo $get_green;
                if($get_green){
                    $string .= " AND st_green IN($get_green) ";
                }
            }
    
            if ($brown != "" && $brown != 'null') {
                $get_brown = get_filter_diamond_parameter($brown, 11);
                //echo $get_brown;
                if($get_brown){
                    $string .= " AND st_brown IN($get_brown) ";
                }
            }
        
            if ($milky != "" && $milky != 'null') {
                $get_milky = get_filter_diamond_parameter($milky, 9);
                //echo $get_milky;
                if($get_milky){
                    $string .= " AND st_milky IN($get_milky) ";
                }
            }
    
            if ($other_tinge != "" && $other_tinge != 'null') {
                $get_other_tinge = get_filter_diamond_parameter($other_tinge, 12);
                //echo $get_other_tinge;
                if($get_other_tinge){
                    $string .= " AND st_other_tinge IN($get_other_tinge) ";
                }
            }
    
            if ($h_and_a != "" && $h_and_a != 'null') {
                $get_h_and_a = get_filter_diamond_parameter($h_and_a, 8);
                //echo $get_h_and_a;
                if($get_h_and_a){
                    $string .= " AND st_h_a IN($get_h_and_a) ";
                }
            }
    
            if ($diamond_location != "" && $diamond_location != 'null') {
                $get_diamond_location = get_filter_diamond_parameter($diamond_location, 44);
                //echo $get_diamond_location;
                if($get_diamond_location){
                    $string .= " AND st_location IN($get_diamond_location) ";
                }
            }
    
            if ($culet != "" && $culet != 'null') {
                $get_culet = get_filter_diamond_parameter($culet, 17);
                //echo $get_culet;
                if($get_culet){
                    $string .= " AND st_culet IN($get_culet) ";
                }
            }
            
            if ($gird_min != "" && $gird_min != 'null') {
                $get_gird_min = get_filter_diamond_parameter($gird_min, 15);
                //echo $get_gird_min;
                if($get_gird_min){
                    $string .= " AND st_gird_min IN($get_gird_min) ";
                }
            }
    
            if ($gird_max != "" && $gird_max != 'null') {
                $get_gird_max = get_filter_diamond_parameter($gird_max, 15);
                //echo $get_gird_max;
                if($get_gird_max){
                    $string .= " AND st_gird_max IN($get_gird_max) ";
                }
            }
    
            if ($gird_condition != "" && $gird_condition != 'null') {
                $get_gird_condition = get_filter_diamond_parameter($gird_condition, 16);
                //echo $get_gird_condition;
                if($get_gird_condition){
                    $string .= " AND st_gird_cand IN($get_gird_condition) ";
                }
            }
    
            if ($inscribed != "" && $inscribed != 'null') {
                $string .= " AND st_inscribed = $inscribed ";
            }
        
            if ($certificate_number != "" && $certificate_number != 'null') {
                $certi_separate_value = '';
                $ex_certi_no = explode('\n',$certificate_number);
                for($i=0;$i<count($ex_certi_no);$i++){
                    $certi_separate_value .= "'".$ex_certi_no[$i]."',";
                }
                $certi_separate_value = substr($certi_separate_value,0,-1);
    
                $string .= " AND st_cert_no IN($certi_separate_value) ";
                
            }
    
            if ($carat_group != "" && $carat_group != 'null') {
                $get_carat_group = get_filter_diamond_parameter($carat_group, 43, 1);
                //echo $get_carat_group;
                if($get_carat_group){
                    $ex_carat_group = explode(',',$get_carat_group);
                    for($i=0;$i<count($ex_carat_group);$i++){
                        $carat_separate_value = str_replace("'","",$ex_carat_group[$i]);
                        $separate_value = explode('-',$carat_separate_value);
                        $string .= " AND (st_size >= '$separate_value[0]' AND st_size <= '$separate_value[1]') ";
                    }   
                }
            } else { // Main if condition
                if(0 < $data['carat_min'] && 50 > $data['carat_max']){
                    $string .= " AND (st_size >= '{$data['carat_min']}' AND st_size <= '{$data['carat_max']}') ";
                }
            }
    
            if (!empty($data['per_carat_min']) && !empty($data['per_carat_max'])) {
                $string .= " AND (st_price_cts >= '{$data['per_carat_min']}' AND st_price_cts <= '{$data['per_carat_max']}') ";
            }
        
            if (!empty($data['per_dis_min']) && !empty($data['per_dis_max'])) {
                $string .= " AND (st_dis >= '{$data['per_dis_min']}' AND st_dis <= '{$data['per_dis_max']}') ";
            }
            
            if (!empty($data['total_usd_min']) && !empty($data['total_usd_max'])) {
                $string .= " AND (st_rap_us_value >= '{$data['total_usd_min']}' AND st_rap_us_value <= '{$data['total_usd_max']}') ";
            }
    
            if (!empty($data['length_min']) && !empty($data['length_max'])) {
                $string .= " AND (st_length >= '{$data['length_min']}' AND st_length <= '{$data['length_max']}') ";
            }
            if (!empty($data['width_min']) && !empty($data['width_max'])) {
                $string .= " AND (st_width >= '{$data['width_min']}' AND st_width <= '{$data['width_max']}') ";
            }
            if (!empty($data['depth_min']) && !empty($data['depth_max'])) {
                $string .= " AND (st_width >= '{$data['depth_min']}' AND st_width <= '{$data['depth_max']}') ";
            }
            
            if (!empty($data['table_per_min']) && !empty($data['table_per_max'])) {
                $string .= " AND (st_table_percentage >= '{$data['table_per_min']}' AND st_table_percentage <= '{$data['table_per_max']}') ";
            }
            if (!empty($data['depth_per_min']) && !empty($data['depth_per_max'])) {
                $string .= " AND (st_depth_percentage >= '{$data['depth_per_min']}' AND st_depth_percentage <= '{$data['depth_per_max']}') ";
            }
    
            if (!empty($data['pavilion_angle_min']) && !empty($data['pavilion_angle_max'])) {
                $string .= " AND (st_pav_ang >= '{$data['pavilion_angle_min']}' AND st_pav_ang <= '{$data['pavilion_angle_max']}') ";
            }
            if (!empty($data['pavilion_depth_min']) && !empty($data['pavilion_depth_max'])) {
                $string .= " AND (st_pav_dep >= '{$data['pavilion_depth_min']}' AND st_pav_dep <= '{$data['pavilion_depth_max']}') ";
            }
            if (!empty($data['star_length_min']) && !empty($data['star_length_max'])) {
                $string .= " AND (st_star >= '{$data['star_length_min']}' AND st_star <= '{$data['star_length_max']}') ";
            }
    
            if (!empty($data['crown_angle_min']) && !empty($data['crown_angle_max'])) {
                $string .= " AND (st_cr_ang >= '{$data['crown_angle_min']}' AND st_cr_ang <= '{$data['crown_angle_max']}') ";
            }
            if (!empty($data['crown_depth_min']) && !empty($data['crown_depth_max'])) {
                $string .= " AND (st_cr_dep >= '{$data['crown_depth_min']}' AND st_cr_dep <= '{$data['crown_depth_max']}') ";
            }
            if (!empty($data['lower_half_min']) && !empty($data['lower_half_max'])) {
                $string .= " AND (st_half >= '{$data['lower_half_min']}' AND st_half <= '{$data['lower_half_max']}') ";
            }
    
            if (!empty($data['gridle_per_min']) && !empty($data['gridle_per_max'])) {
                $string .= " AND (st_gird >= '{$data['gridle_per_min']}' AND st_gird <= '{$data['gridle_per_max']}') ";
            }
        
            if (!empty($data['culet_per_min']) && !empty($data['culet_per_max'])) {
                $string .= " AND (st_culet_per >= '{$data['culet_per_min']}' AND st_culet_per <= '{$data['culet_per_max']}') ";
            }
            
            if (!empty($data['certi_date_from']) && !empty($data['certi_date_to'])) {
                $string .= " AND (st_certi_date_from >= '{$data['certi_date_from']}' AND st_certi_date_to <= '{$data['certi_date_to']}') ";
            }
    
            if ($data['special_cut'] != "" && $data['special_cut'] != 'null') {
                $get_special_cut = $this->model->get_filter_diamond_parameter($data['special_cut'], 32);
                //echo $get_special_cut;
                if($get_special_cut){
                    $string .= " AND st_special_cut IN($get_special_cut) ";
                }
            }
    
            if ($data['internal_color'] != "" && $data['internal_color'] != 'null') {
                $get_internal_color = $this->model->get_filter_diamond_parameter($data['internal_color'], 33);
                //echo $get_internal_color;
                if($get_internal_color){
                    $string .= " AND st_internal_color IN($get_internal_color) ";
                }
            }
    
            if ($data['internal_clarity'] != "" && $data['internal_clarity'] != 'null') {
                $get_internal_clarity = $this->model->get_filter_diamond_parameter($data['internal_clarity'], 34);
                //echo $get_internal_clarity;
                if($get_internal_clarity){
                    $string .= " AND st_internal_clarity IN($get_internal_clarity) ";
                }
            }
    
            if ($data['internal_commnets'] != "" && $data['internal_commnets'] != 'null') {
                $string .= " AND st_internal_commnets LIKE '%{$data['internal_commnets']}%'";
            }
        
            if ($data['size_cts_defintion'] != "" && $data['size_cts_defintion'] != 'null') {
                $get_size_cts_defintion = $this->model->get_filter_diamond_parameter($data['size_cts_defintion'], 36);
                //echo $get_size_cts_defintion;
                if($get_size_cts_defintion){
                    $string .= " AND st_size_cts_defintion IN($get_size_cts_defintion) ";
                }
            }
    
            if ($data['country_of_origin'] != "" && $data['country_of_origin'] != 'null') {
                $get_country_of_origin = $this->model->get_filter_diamond_parameter($data['country_of_origin'], 37);
                //echo $get_country_of_origin;
                if($get_country_of_origin){
                    $string .= " AND st_country_of_origin IN($get_country_of_origin) ";
                }
            }
    
            // panding in parameters master
            if ($data['air_mark_for_customers'] != "" && $data['air_mark_for_customers'] != 'null') {
                $get_air_mark_for_customers = $this->model->get_filter_diamond_parameter($data['air_mark_for_customers'], 37);
                //echo $get_air_mark_for_customers;
                if($get_air_mark_for_customers){
                    $string .= " AND st_air_mark_for_customers IN($get_air_mark_for_customers) ";
                }
            }
    
            if ($data['multiple_stone_group'] != "" && $data['multiple_stone_group'] != 'null') {
                $get_multiple_stone_group = get_filter_diamond_parameter($data['multiple_stone_group'], 38);
                //echo $get_multiple_stone_group;
                if($get_multiple_stone_group){
                    $string .= " AND st_multiple_stone_group IN($get_multiple_stone_group) ";
                }
            }
    
            if ($data['legal_entity'] != "" && $data['legal_entity'] != 'null') {
                $string .= " AND st_legal_entity IN({$data['legal_entity']})";
            }
    
            if ($data['sites'] != "" && $data['sites'] != 'null') {
                $string .= " AND st_sites IN({$data['sites']})";
            }
    
            if ($data['warehouse'] != "" && $data['warehouse'] != 'null') {
                $string .= " AND st_warehouse IN({$data['warehouse']})";
            }
    
            if ($data['war_location'] != "" && $data['war_location'] != 'null') {
                $string .= " AND st_war_location IN({$data['war_location']})";
            }
    
            if ($data['certified_publish'] != "" && $data['certified_publish'] != 'null') {
                $string .= " AND st_certified_publish = '{$data['certified_publish']}'";
            }
    
            if ($data['internal_status'] != "" && $data['internal_status'] != 'null') {
                $get_internal_status = $this->model->get_filter_diamond_parameter($data['internal_status'], 39);
                //echo $get_internal_status;
                if($get_internal_status){
                    $string .= " AND st_internal_status IN({$get_internal_status}) ";
                }
            }
    
            if ($data['goods_in_transit'] != "" && $data['goods_in_transit'] != 'null') {
                $string .= " AND st_goods_in_transit = {$data['goods_in_transit']} ";
            }
    
            if ($data['brands'] != "" && $data['brands'] != 'null') {
                $get_brands = $this->model->get_filter_diamond_parameter($data['brands'], 41);
                //echo $get_brands;
                if($get_brands){
                    $string .= " AND st_brands IN($get_brands) ";
                }
            }
    
            if ($data['eligibility'] != "" && $data['eligibility'] != 'null') {
                $get_eligibility = $this->model->get_filter_diamond_parameter($data['eligibility'], 42);
                //echo $get_eligibility;
                if($get_eligibility){
                    $string .= " AND st_eligibility IN($get_eligibility) ";
                }
            }
    
            if ($data['supplier_parcel_id'] != "" && $data['supplier_parcel_id'] != 'null') {
                $sup_separate_value = '';
                $ex_sup_no = explode('\n',$data['supplier_parcel_id']);
                for($i=0;$i<count($ex_sup_no);$i++){
                    $sup_separate_value .= "'".$ex_sup_no[$i]."',";
                }
                $sup_separate_value = substr($sup_separate_value,0,-1);
                $string .= " AND st_supplier_parcel_id IN($sup_separate_value) ";
            }
    
            if ($data['batch_no'] != "" && $data['batch_no'] != 'null') {
                $bno_separate_value = '';
                $ex_bno_no = explode('\n',$data['batch_no']);
                for($i=0;$i<count($ex_bno_no);$i++){
                    $bno_separate_value .= "'".$ex_bno_no[$i]."',";
                }
                $bno_separate_value = substr($bno_separate_value,0,-1);
                $string .= " AND st_batch_no IN($bno_separate_value) ";
            }
    
            if ($data['serial_no'] != "" && $data['serial_no'] != 'null') {
                $sno_separate_value = '';
                $ex_sno_no = explode('\n',$data['serial_no']);
                for($i=0;$i<count($ex_sno_no);$i++){
                    $sno_separate_value .= "'".$ex_sno_no[$i]."',";
                }
                $sno_separate_value = substr($sno_separate_value,0,-1);
                $string .= " AND st_serial_no IN($sno_separate_value) ";
            }
    
            if ($data['vendor_id'] != "" && $data['vendor_id'] != 'null') {
                $string .= " AND st_vendor_id IN({$data['vendor_id']})";
            }
    
            $data['where'] = "  st_is_delete = 0 AND ".$status." ".$string;

        }

        $result = $this->model->getLivestockDiamond($data);

        respond_success_to_api("success", $result);
    
    
    }

     /**
     *  10 Aug 2020
     *  Get Diamond parameter
     */
    public function getDiamondParameters()
    {
        $keys = array("entity_id","status");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->diamond_param($data);

        if($res)
            respond_success_to_api("success", $res);
         else   
            respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");
    }

    /**
     *  10 Aug 2020
     *  Save and Update Search
     */
    public function saveSearch()
    {
        $keys = array("search_id","shape","clarity","color","cut","polish","symmetry","fluorescence","certificate","h_and_a","green","brown","milky","other_tinge","diamond_location","status","carat_min","carat_max","carat_group","per_dis_min","per_dis_max","inscribed","certi_number","per_carat_min","per_carat_max","total_usd_min","total_usd_max","length_min","length_max","width_min","width_max","depth_min","depth_max","depth_per_min","depth_per_max","table_per_min","table_per_max","pavilion_angle_min","pavilion_angle_max","pavilion_depth_min","pavilion_depth_max","star_length_min","star_length_max","crown_angle_min","crown_angle_max","crown_depth_min","crown_depth_max","lower_half_min","lower_half_max","gird_min","gird_max","girdle_per_min","girdle_per_max","gird_condition","culet_per_min","culet_per_max","culet","key_of_sym_type","key_of_symbol","white_in_center","white_in_side","black_in_center","black_in_side","no_open","no_black","client_id","tenent_id","entity_id","search_id","search_title");
        $data =  check_api_keys($keys,$this->mydata);
        
        $saveData['sds_search_title'] = $data['search_title'];  
        $saveData['sds_tenent_id'] = $data['tenent_id'];
        $saveData['sds_entity_id'] = $data['entity_id'];
        $saveData['sds_customer_id'] =  $data['client_id'];
        $saveData['sds_shape'] =  $data['shape'];  
        $saveData['sds_carat_from'] =  $data['carat_min']; 
        $saveData['sds_carat_to'] =  $data['carat_max']; 
        $saveData['sds_carat_group'] =  $data['carat_group']; 
        $saveData['sds_clarity'] =  $data['clarity']; 
        $saveData['sds_color'] =  $data['color']; 
        $saveData['sds_cut'] =  $data['cut']; 
        $saveData['sds_flu'] =  $data['fluorescence']; 
        $saveData['sds_sym'] =  $data['symmetry']; 
        $saveData['sds_cert'] =  $data['certificate']; 
        $saveData['sds_polish'] =  $data['polish']; 
        $saveData['sds_milky'] =  $data['milky']; 
        $saveData['sds_location'] =  $data['diamond_location']; 
        $saveData['sds_brown'] =  $data['brown']; 
        $saveData['sds_green'] =  $data['green']; 
        $saveData['sds_ha'] =  $data['h_and_a']; 
        $saveData['sds_othertinge'] =  $data['other_tinge']; 
        $saveData['sds_bs_status'] =  $data['status']; 
        $saveData['sds_cts_pr_from'] =  $data['per_carat_max']; 
        $saveData['sds_cts_pr_to'] =  $data['per_carat_min']; 
        $saveData['sds_dis_from'] =  $data['per_dis_min']; 
        $saveData['sds_dis_to'] =  $data['per_dis_max']; 
        $saveData['sds_us_price_from'] =  $data['total_usd_min']; 
        $saveData['sds_us_price_to'] =  $data['total_usd_max']; 
        $saveData['sds_cer_no'] =  $data['certi_number']; 
        $saveData['sds_inscribed'] =  $data['inscribed']; 
        $saveData['sds_lengh_from'] =  $data['length_min']; 
        $saveData['sds_lengh_to'] =  $data['length_max']; 
        $saveData['sds_width_from'] =  $data['width_min']; 
        $saveData['sds_width_to'] =  $data['width_max']; 
        $saveData['sds_depth_from'] =  $data['depth_min']; 
        $saveData['sds_depth_to'] =  $data['depth_max']; 
        $saveData['sds_depth_per_from'] =  $data['depth_per_min']; 
        $saveData['sds_depth_per_to'] =  $data['depth_per_max']; 
        $saveData['sds_tbl_from'] =  $data['table_per_min']; 
        $saveData['sds_tbl_to'] =  $data['table_per_max']; 
        $saveData['sds_pav_ang_from'] =  $data['pavilion_angle_min']; 
        $saveData['sds_pav_ang_to'] =  $data['pavilion_angle_max']; 
        $saveData['sds_pav_dep_from'] =  $data['pavilion_depth_min']; 
        $saveData['sds_pav_dep_to'] =  $data['pavilion_depth_max']; 
        $saveData['sds_star_from'] =  $data['star_length_min']; 
        $saveData['sds_star_to'] =  $data['star_length_max']; 
        $saveData['sds_crn_ang_from'] =  $data['crown_angle_min']; 
        $saveData['sds_crn_ang_to'] =  $data['crown_angle_max']; 
        $saveData['sds_crn_dep_from'] =  $data['crown_depth_min']; 
        $saveData['sds_crn_dep_to'] =  $data['crown_depth_max']; 
        $saveData['sds_half_from'] =  $data['lower_half_min']; 
        $saveData['sds_half_to'] =  $data['lower_half_max']; 
        $saveData['sds_gir_from'] =  $data['gird_min']; 
        $saveData['sds_gir_to'] =  $data['gird_max']; 
        $saveData['sds_gir_per_from'] =  $data['girdle_per_min']; 
        $saveData['sds_gir_per_to'] =  $data['girdle_per_max']; 
        $saveData['sds_gir_con'] =  $data['gird_condition']; 
        $saveData['sds_cul_per_from'] =  $data['culet_per_min']; 
        $saveData['sds_cul_per_to'] =  $data['culet_per_max']; 
        $saveData['sds_culet'] =  $data['culet']; 
        $saveData['sds_k_to_sym_type'] =  $data['key_of_sym_type']; 
        $saveData['sds_k_to_sym'] =  $data['key_of_symbol']; 
        $saveData['sds_white_in_center'] =  $data['white_in_center']; 
        $saveData['sds_white_in_side'] =  $data['white_in_side']; 
        $saveData['sds_no_open'] =  $data['no_open']; 
        $saveData['sds_black_in_center'] =  $data['black_in_center']; 
        $saveData['sds_black_in_side'] =  $data['black_in_side']; 
        $saveData['sds_no_black'] =  $data['no_black']; 

        if($data['search_id'] != ""){
            $saveData['sds_search_id'] = $this->create_search_id($data);
            if($this->model->add_search($saveData))
              respond_success_to_api("success");
            else   
               respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");
        }else{
            $saveData['sds_id'] = $data['search_id'];
            if($this->model->update_search($saveData))
               respond_success_to_api("success");
            else   
               respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");

        }

    }

    private function  create_search_id($data)
    {
      $resData =   $this->common->getData('save_diamond_search',"sds_tenent_id='{$data['tenent_id']}' AND sds_entity_id='{$data['entity_id']}' AND sds_customer_id='{$data['client_id']}'");

      return $data['entity_id'].'-'.$data['client_id'].'-SRC-'.count($resData)+1;
    }

    /**
     *  14 Aug 2020
     *  get  List Diamond Parameters
     */
    public function getListDiamondParameter()
    {
        $keys = array("dp_id","dp_parameters_name","dp_parameters_code","entity_id","dp_table_type","dp_admin_filter","dp_user_filter","per_page","page_no");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->list_diamond_parameters($data);

        if($res)
            respond_success_to_api("success", $res);
         else   
            respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");
    }

      /**
     *  14 Aug 2020
     *  get  List Diamond Parameters
     */
    public function removeDiamondParameters()
    {
        $keys = array("dp_id","dp_table_type","entity_id");

        $data =  check_api_keys($keys,$this->mydata);
        // $upData = 
        $res = $this->common->updateData(['dp_view_status' => 1],'diamond_parameters',"`dp_id`  = {$data['dp_id']} AND `dp_entity_id` = '{$data['dp_entity_id']}' AND `dp_table_type` = '{$data['dp_table_type']}' ");

        if($res)
            respond_success_to_api("success");
         else   
            respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");
    }

    /**
     *  14 Aug 2020
     *  Update diamond parameter
     */
    public function updateDiamondParameters()
    {
        $keys = array("dp_id","dp_parameters_name","dp_parameters_code","dp_user_filter","dp_admin_filter","entity_id","dp_table_type","dp_position","user_name");

        $data =  check_api_keys($keys,$this->mydata);

        $data['dp_admin_filter'] = $data['dp_admin_filter'] == true ? 1 : 0;
        $data['dp_user_filter'] = $data['dp_user_filter'] == true ? 1 : 0;
        $updateData['dp_parameters_name'] = $data['dp_parameters_name'];
        $updateData['dp_parameters_code'] = $data['dp_parameters_code'];
        $updateData['dp_update_by'] = $data['user_name'];
        $updateData['dp_update_date'] = date('Y-m-d H:i:s');
        $updateData['dp_user_filter'] = $data['dp_user_filter'];
        $updateData['dp_admin_filter'] = $data['dp_admin_filter'];
        $updateData['dp_position'] = $data['dp_position'];
        
       $bool =  $this->common->updateData($updateData,"diamond_parameters","dp_id  = '{$data['dp_id']}' AND `dp_entity_id` = '{$data['entity_id']}' AND `dp_table_type` = '{$data['dp_table_type']}' AND `dp_view_status` = '0'");
    
        if($bool)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");        
    }

     /**
     *  14 Aug 2020
     *  Add diamond parameter
     */
    public function addDiamondParameters()
    {
        $keys = array("dp_parameters_name","dp_parameters_code","dp_user_filter","dp_admin_filter","entity_id","dp_table_type","dp_position","user_name");

        $data =  check_api_keys($keys,$this->mydata);

        $data['dp_admin_filter'] = $data['dp_admin_filter'] == true ? 1 : 0;
        $data['dp_user_filter'] = $data['dp_user_filter'] == true ? 1 : 0;
        $updateData['dp_parameters_name'] = $data['dp_parameters_name'];
        $updateData['dp_parameters_code'] = $data['dp_parameters_code'];
        $updateData['dp_update_by'] = $data['user_name'];
        $updateData['dp_update_date'] = date('Y-m-d H:i:s');
        $updateData['dp_user_filter'] = $data['dp_user_filter'];
        $updateData['dp_admin_filter'] = $data['dp_admin_filter'];
        $updateData['dp_position'] = $data['dp_position'];
        
       $bool =  $this->common->getData('diamond_parameters',"`dp_parameters_name` = '{$data['dp_parameters_name']}' AND `dp_parameters_code` = '{$data['dp_parameters_code']}' AND `dp_entity_id` = '{$data['dp_entity_id']}' AND dp_table_type='{$data['dp_table_type']}' AND dp_view_status='0'");
    
        if(!empty($bool))
           respond_error_to_api("error", "Record already exist.");       
        else{
            $this->model->add_diamond_parameter($updateData);
            respond_success_to_api("success");
        }
           
    }

    /**
     *  14 Aug 2020
     *  Get Diamond parameter table list 
     */
    public function getDiamondTableList()
    {
        respond_success_to_api("success",DIAMOND_TABLE_LIST);
    }

    /**
     *  15 Aug 2020
     *  Get Diamond file Master
     */
    public function getDiamondFileMaster()
    {
        $keys = array("table_name","table_value","entity_id","tenent_id","per_page","page_no");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->get_diamond_file_master($data);

        respond_success_to_api("success",$res);

    }

    /**
     *  15 Aug 2020
     *  Add Diamond file Master
     */
    public function addDiamondFileMaster()
    {
        $keys = array("table_name","table_value","entity_id","tenent_id","warehouse_id","field_name","field_value","sub_field_value","created_by");

        $data =  check_api_keys($keys,$this->mydata);

        $bool =  $this->common->getData('diamond_upload_file_master',"dm_tenent_id='{$data['tenent_id']}' AND dm_entity_id='{$data['entity_id']}' AND dm_warehouse_id = '{$data['warehouse_id']}' AND dm_our_table_name='{$data['table_name']}' AND dm_our_table_value='{$data['table_value']}' AND dm_excel_feild_value='{$data['field_value']}' AND dm_excel_feild_sub_value='{$data['sub_field_value']}'");
    
        if(!empty($bool))
           respond_error_to_api("error", "Record already exist.");       
        else{

            $updateData['dm_tenent_id'] = $data['tenent_id']; 
            $updateData['dm_entity_id'] = $data['entity_id']; 
            $updateData['dm_warehouse_id'] = $data['warehouse_id']; 
            $updateData['dm_our_table_name'] = $data['table_name']; 
            $updateData['dm_our_table_value'] = $data['table_value']; 
            $updateData['dm_excel_feild_name'] = $data['field_name']; 
            $updateData['dm_excel_feild_value'] = $data['field_value']; 
            $updateData['dm_excel_feild_sub_value'] = $data['sub_field_value']; 
            $updateData['dm_create_by'] = $data['created_by']; 
            $updateData['dm_create_date'] = date('Y-m-d'); 

            $this->model->add_diamond_file_master($updateData);
            respond_success_to_api("success");
        }
    }

     /**
     *  15 Aug 2020
     *  Update Diamond file Master
     */
    public function updateDiamondFileMaster()
    {
        $keys = array("dm_id","table_name","table_value","entity_id","tenent_id","warehouse_id","field_name","field_value","sub_field_value","created_by");

        $data =  check_api_keys($keys,$this->mydata);

        $bool =  $this->common->getData('diamond_upload_file_master',"dm_tenent_id='{$data['tenent_id']}' AND dm_entity_id='{$data['entity_id']}' AND dm_warehouse_id = '{$data['warehouse_id']}' AND dm_our_table_name='{$data['table_name']}' AND dm_our_table_value='{$data['table_value']}' AND dm_excel_feild_value='{$data['field_value']}' AND dm_excel_feild_sub_value='{$data['sub_field_value']}'");
    
        if(!empty($bool))
           respond_error_to_api("error", "Record already exist.");       
        else{

            // $updateData['dm_id'] = $data['dm_id']; 


            $updateData['dm_tenent_id'] = $data['tenent_id']; 
            $updateData['dm_entity_id'] = $data['entity_id']; 
            $updateData['dm_warehouse_id'] = $data['warehouse_id']; 
            $updateData['dm_our_table_name'] = $data['table_name']; 
            $updateData['dm_our_table_value'] = $data['table_value']; 
            $updateData['dm_excel_feild_name'] = $data['field_name']; 
            $updateData['dm_excel_feild_value'] = $data['field_value']; 
            $updateData['dm_excel_feild_sub_value'] = $data['sub_field_value']; 
            $updateData['dm_create_by'] = $data['created_by']; 
            $updateData['dm_create_date'] = date('Y-m-d'); 
            $where = "dm_tenent_id = '{$data['tenent_id']}' AND dm_entity_id = '{$data['entity_id']}' AND dm_id = '{$data['dm_id'] }' ";
            // $this->common->update($updateData,"diamond_upload_file_master",);
            // $this->model->add_diamond_file_master($updateData);
            respond_success_to_api("success");
        }
    }

      /**
     *  15 Aug 2020
     *  Remove  Diamond File Master
     */
    public function removeDiamondFileMaster()
    {
        $keys = array("tenent_id","entity_id","dm_id");

        $data =  check_api_keys($keys,$this->mydata);
        // $upData = 
        $res = $this->common->updateData(['dm_status' => 1],'diamond_upload_file_master',"dm_tenent_id = '{$data['tenent_id']}' AND dm_entity_id = '{$data['entity_id']}' AND dm_id = '{$data['dm_id']}'");

        if($res)
            respond_success_to_api("success");
         else   
            respond_error_to_api("error", "Opss! Someting wentwrong. Please try after sometime.");
    }

     /**
     *  15 Aug 2020
     *  Get Price Master Diamond
     */
    public function getPMDiamond()
    {
        $keys = array("dp_id","shape","color","clarity","cut","polish","symmetry","st_size","markup_type","markup_value","vendor_name","entity_id","client_id","per_page","page_no");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->get_pm_diamond($data);

        respond_success_to_api("success",$res);

    }


      /**
     *  15 Aug 2020
     *  Add/Update Price Master Diamond
     */
    public function savePMDiamond()
    {
        currency_rate();
        $keys = array("shape","color","clarity","cut","polish","symmetry","st_size","markup_type","markup_value","vendor_name","entity_id","client_id","dp_id");

        $data =  check_api_keys($keys,$this->mydata);

        $data['create_date'] = date('Y-m-d');
        $res = $this->model->save_pm_diamond($data);

        respond_success_to_api("success");

    }

}
?>