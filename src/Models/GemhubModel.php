<?php 
/**
 *  Kalsariya Vanraj M.
 *  08 Aug 2020
 *  For Manage Gemhub modules 
 */

 namespace App\Models;

use App\Core\Model;

class GemhubModel extends Model{


     /**
   *  08 Aug 2020
   *  For filter for Diamond parameter
   */
  public function get_filter_diamond_parameter($parameters, $table_type, $fields = 0)
  {
      //$this->table = 'diamond_parameters';
      $response = "";
      $datafields = ($fields == 0 ) ? 'dp_parameters_name' : 'dp_parameters_code';
      $sql = 'SELECT '.$datafields.' AS datafields FROM diamond_parameters WHERE dp_table_type = `'.$table_type.'` AND dp_position IN('.$parameters.')';

     $res =  $this->db->getAll($sql);
     
    foreach($res as $key=>$value)
    {
        $response .= "'".trim($value['datafields'])."',";
    }
    
    $response = substr($response,0,-1);

    return $response;
  }

  /**
   * 10 Aug 2020
   * Vanraj Kalsariya
   */
  public function check_user_price_quote_diamond_params($tenent_id, $entity_id, $warehouse_id, $is_warehouse)
  {
    $qry = "SELECT `dp_carat_from`, `dp_carat_to`, `dp_shape_from`, `dp_shape_to`, `dp_color_from`, `dp_color_to`, `dp_clarity_from`, `dp_clarity_to`, `dp_cut_from`, `dp_cut_to`, `dp_polish_from`, `dp_polish_to`, `dp_symmetry_from`, `dp_symmetry_to`, `dp_pricing_system`, `dp_price`, `dp_markup`, `dp_discount_from`, `dp_discount_to`, `dp_fluorescence_from`, `dp_fluorescence_to`, `dp_certificate_from`, `dp_certificate_to`, `dp_ha_from`, `dp_ha_to`, `dp_onhand`, `dp_worldwide`,`dp_milky_from`,`dp_milky_to`,`dp_brown_from`, `dp_brown_to`,`dp_green_from`,`dp_green_to`,`dp_othertinge_from`,`dp_othertinge_to`,`dp_location` FROM diamond_price_quote AS `dufm` JOIN diamond_price_quote_parameters AS dpqp ON dpq_tenent_id = dp_tenent_id AND dp_legal_entity = dpq_legal_entity AND dpq_quote_id = dp_quote_id WHERE dpq_tenent_id = '$tenent_id' AND dpq_legal_entity = '$entity_id' AND dpq_customer_id = '$warehouse_id' AND dpq_is_warehouse = '$is_warehouse' AND dpq_action = 0";
    $fetch_warehouse = $this->db->getAll($qry);
    $arr = [];

        if(!empty($fetch_warehouse))
        {
            foreach($fetch_warehouse as $key=>$value)
            {
                $dp_carat_from = $value['dp_carat_from'];
                $dp_carat_to = $value['dp_carat_to'];
                if(!empty($dp_carat_from) && !empty($dp_carat_to) && $dp_carat_from != '0.00' && $dp_carat_to != '0.00'){
                    $carat_val = $dp_carat_from.'-'.$dp_carat_to;
                } else {
                    $carat_val = '';
                }

                $dp_discount_from = $value['dp_discount_from'];
                $dp_discount_to = $value['dp_discount_to'];
                if(!empty($dp_discount_from) && !empty($dp_discount_to) && $dp_discount_from != '0.00' && $dp_discount_to != '0.00'){
                    $discount_val = $dp_discount_from.'-'.$dp_discount_to;
                } else {
                    $discount_val = '';
                }


                $dp_shape_from = $value['dp_shape_from'];
                $dp_shape_to = $value['dp_shape_to'];
                if(!empty($dp_shape_from) && !empty($dp_shape_to)){
                    $shape_val = $this->get_filter_diamond_parameter_between($dp_shape_from, $dp_shape_to, 0, $fields = 1);
                } else {
                    $shape_val = '';
                }

                $dp_color_from = $value['dp_color_from'];
                $dp_color_to = $value['dp_color_to'];
                if(!empty($dp_color_from) && !empty($dp_color_to)){
                    $color_val = $this->get_filter_diamond_parameter_between($dp_color_from, $dp_color_to, 2, $fields = 1);
                } else {
                    $color_val = '';
                }

                $dp_clarity_from = $value['dp_clarity_from'];
                $dp_clarity_to = $value['dp_clarity_to'];
                if(!empty($dp_clarity_from) && !empty($dp_clarity_to)){
                    $clarity_val = $this->get_filter_diamond_parameter_between($dp_clarity_from, $dp_clarity_to, 3, $fields = 1);
                } else {
                    $clarity_val = '';
                }

                $dp_cut_from = $value['dp_cut_from'];
                $dp_cut_to = $value['dp_cut_to'];
                if(!empty($dp_cut_from) && !empty($dp_cut_to)){
                    $cut_val = $this->get_filter_diamond_parameter_between($dp_cut_from, $dp_cut_to, 5, $fields = 1);
                } else {
                    $cut_val = '';
                }

                $dp_polish_from = $value['dp_polish_from'];
                $dp_polish_to = $value['dp_polish_to'];
                if(!empty($dp_polish_from) && !empty($dp_polish_to)){
                    $polish_val = $this->get_filter_diamond_parameter_between($dp_polish_from, $dp_polish_to, 6, $fields = 1);
                } else {
                    $polish_val = '';
                }

                $dp_symmetry_from = $value['dp_symmetry_from'];
                $dp_symmetry_to = $value['dp_polish_to'];
                if(!empty($dp_symmetry_from) && !empty($dp_symmetry_to)){
                    $symmetry_val = $this->get_filter_diamond_parameter_between($dp_symmetry_from, $dp_symmetry_to, 7, $fields = 1);
                } else {
                    $symmetry_val = '';
                }

                $dp_fluorescence_from = $value['dp_fluorescence_from'];
                $dp_fluorescence_to = $value['dp_fluorescence_to'];
                if(!empty($dp_fluorescence_from) && !empty($dp_fluorescence_to)){
                    $fluorescence_val = $this->get_filter_diamond_parameter_between($dp_fluorescence_from, $dp_fluorescence_to, 4, $fields = 1);
                } else {
                    $fluorescence_val = '';
                }

                $dp_certificate_from = $value['dp_certificate_from'];
                $dp_certificate_to = $value['dp_certificate_to'];
                if(!empty($dp_certificate_from) && !empty($dp_certificate_to)){
                    $certificate_val = $this->get_filter_diamond_parameter_between($dp_certificate_from, $dp_certificate_to, 1, $fields = 1);
                } else {
                    $certificate_val = '';
                }

                $dp_ha_from = $value['dp_ha_from'];
                $dp_ha_to = $value['dp_ha_to'];
                if(!empty($dp_ha_from) && !empty($dp_ha_to)){
                    $ha_val = $this->get_filter_diamond_parameter_between($dp_ha_from, $dp_ha_to, 8, $fields = 1);
                } else {
                    $ha_val = '';
                }
    
                $dp_milky_from = $value['dp_milky_from'];
                $dp_milky_to = $value['dp_milky_to'];
                if(!empty($dp_milky_from) && !empty($dp_milky_to)){
                    $milky_val = $this->get_filter_diamond_parameter_between($dp_milky_from, $dp_milky_to, 9, $fields = 1);
                } else {
                    $milky_val = '';
                }

                $dp_brown_from = $value['dp_brown_from'];
                $dp_brown_to = $value['dp_brown_to'];
                if(!empty($dp_brown_from) && !empty($dp_brown_to)){
                    $brown_val = $this->get_filter_diamond_parameter_between($dp_brown_from, $dp_brown_to, 11, $fields = 1);
                } else {
                    $brown_val = '';
                }

                $dp_green_from = $value['dp_green_from'];
                $dp_green_to = $value['dp_green_to'];
                if(!empty($dp_green_from) && !empty($dp_green_to)){
                    $green_val = $this->get_filter_diamond_parameter_between($dp_green_from, $dp_green_to, 10, $fields = 1);
                } else {
                    $green_val = '';
                }

                $dp_othertinge_from = $value['dp_othertinge_from'];
                $dp_othertinge_to = $value['dp_othertinge_to'];
                if(!empty($dp_othertinge_from) && !empty($dp_othertinge_to)){
                    $othertinge_val = $this->get_filter_diamond_parameter_between($dp_othertinge_from, $dp_othertinge_to, 12, $fields = 1);
                } else {
                    $othertinge_val = '';
                }

                $dp_location = $value['dp_location'];
                if(!empty($dp_location)){
                    $location_val = $this->get_filter_diamond_parameter_between($dp_location, $dp_location, 44, $fields = 1);
                } else {
                    $location_val = '';
                }
                
                $arr[] = array(
                    "shape" => $shape_val,
                    "carat" => $carat_val,
                    "color" => $color_val,
                    "clarity" => $clarity_val,
                    "cut" => $cut_val,
                    "polish" => $polish_val,
                    "symmetry" => $symmetry_val,
                    "fluorescence" => $fluorescence_val,
                    "certificate" => $certificate_val,
                    "heart_and_arrow" => $ha_val,
                    "milky" => $milky_val,
                    "brown" => $brown_val,
                    "green" => $green_val,
                    "othertinge" => $othertinge_val,
                    "location" => $location_val,
                    "discount" => $discount_val,
                );

            }
        }

        return $arr;
    }

    private function get_filter_diamond_parameter_between($param_from, $param_to, $table_type, $fields = 0)
    {
        if($fields == 0){
            $datafields = 'dp_parameters_name';
        } else {
            $datafields = 'dp_parameters_code';
        }
        $response = '';
        $qry = "SELECT $datafields AS datafields FROM diamond_parameters WHERE dp_table_type = '$table_type' AND dp_position >= '$param_from' AND dp_position <= '$param_to'";

        $res =  $this->db->getAll($qry);
     
        foreach($res as $key=>$value)
        {
            $response .= "'".trim($value['datafields'])."',";
        }
        
        $response = substr($response,0,-1);
    
        return $response;

    
    }

    public function return_diamond_filter($getShape,$get_match,$column_name)
    {
        $arr_shape = explode(',',$get_match);
        $get_shape = explode(',',$getShape);
    
         if(count($arr_shape) > 0 && count($get_shape) > 0){     
            $find_shape = $str = '';
            $shape_not_find = 0;
            for($j=0;$j<count($get_shape);$j++){
                if (in_array($get_shape[$j], $arr_shape )) {
                    $find_shape .= $get_shape[$j].',';
                } else {
                    $shape_not_find = 1;
                    // $str .= " `$column_name` IN('okkk') AND ";
                    echo "\n not find shape = ".$get_shape[$j].'\n';
                }
            }
            if(!empty($getShape)){
                if(!empty($find_shape)){
                    $find_shape = substr($find_shape,0,-1);
                    $str .= " `$column_name` IN($find_shape) AND ";
                } else {
                    $str .= " `$column_name` IN('other') AND ";
                }
            } else {
                $find_shape = '';
                for($j=0;$j<count($arr_shape);$j++){
                    $find_shape .= $arr_shape[$j].',';
                }
                echo '***'.$find_shape.'***';
                $find_shape = substr($find_shape,0,-1);
                if($find_shape!=''){
                    $str .= " `$column_name` IN($find_shape) AND ";
                }
                //  else {
                //     $str .= "444 `$column_name` IN('ok222') AND ";
                // }
            }
        } else {
            // echo '\n test ';
            // print_r($arr_shape);
            // echo '\n test ';
            $str = " `$column_name` IN($getShape) AND "; //$arr_shape;
        }
        return $str;
    }
  
    /**
     *  10 Aug 2020
     *  Main function of live diaomnd stock update
     */
    public function getLivestockDiamond($data)
    {

        if($data['flag'] == 'data'){
            $this->table = 'stock';
            $no_of_records_per_page = $data['per_page'];
            $offset = ($data['page_no']-1) * $no_of_records_per_page;

        
            $where = "created_id = '".$data['bp_tal_id']."' and is_deleted = 1 ";
                

            $res['total_rows'] = $this->total($data['where']);
            $res['pg_value'] =  $data['page_no'] .' - '.$data['per_page'];
            $res['toal_pages'] = ceil($res['total_rows']/$data['per_page']);
            

            $res['resData'] = $this->pageRows($offset, $limit = $data['per_page'],$data['where']);

        }else{
            $res['available_stock'] = $this->db->getAll($query);
        }

      

        return $res;
    }

    /**
     *  10 Aug 2020
     *  For getting diamond parameters
     */
    public function diamond_param($data)
    {
        $filter = "";
        if($data['status'] == 1) 
            $filter = ' AND  dp_admin_filter = 1';
        else if($data['status'] == 2 )
            $filter = ' AND  dp_user_filter = 1';

        $query = "SELECT `dp_table_type`, `dp_id`, `dp_parameters_name`, `dp_parameters_code`, dp_position FROM `diamond_parameters` WHERE dp_view_status = 0 AND `dp_entity_id` = '{$data['entity_id']}' $filter ORDER BY dp_position ASC";

        $resource = $this->db->getAll($query);
        $json = [];
        if(!empty($resource))
        {
            foreach($resource as $key=>$value)
            {
                $json[DIAMOND_PARAM[$value['dp_table_type']]][] = $value;
            }
        }

        return $json;
    }

    /**
     *  10 Aug 2020
     *  Add search
     */
    public function add_search($data)
    {
        $this->table = "save_diamond_search";

        return $this->insert($data);
    }
     /**
     *  10 Aug 2020
     *  Update search
     */
    public function update_search($data)
    {
        $this->model = "save_diamond_search";
        
        return $this->update($data);

    }

    /**
     *  14 Aug 2020
     *  List of Diamond parameter
     */
    public function list_diamond_parameters($data)
    {
        $string = '';
        if ($data['dp_parameters_code'] != "" && $data['dp_parameters_code'] != 'null') {
            $string .= "AND dp_parameters_code LIKE '%{$data['dp_parameters_code']}%'";
        }
        if ($data['dp_parameters_name'] != "" && $data['dp_parameters_name'] != 'null') {
            $string .= "AND dp_parameters_name LIKE '%{$dp_parameters_name}%'";
        }
        if ($data['dp_admin_filter'] == "true" ) {
            $string .= "AND dp_admin_filter = '1'";
        }
        
        if ($data['dp_user_filter'] == "true" ) {
            $string .= "AND dp_user_filter = '1'";
        }

        $no_of_records_per_page = $data['per_page'];
        $offset = ($data['page_no']-1) * $no_of_records_per_page;

        $this->table = "diamond_parameters";
        $this->selectFields = "dp_id, dp_parameters_name, dp_parameters_code, dp_admin_filter, dp_user_filter";
        
        $where = "dp_view_status = 0 AND dp_entity_id= '".$data['entity_id']."' dp_table_type='".$data['dp_table_type']."' {$string} ";
            

        $res['total_rows'] = $this->total($where);
        $res['pg_value'] =  $data['page_no'] .' - '.$data['per_page'];
        $res['toal_pages'] = ceil($res['total_rows']/$data['per_page']);
        

        $res['resData'] = $this->pageRows($offset, $limit = $data['per_page'],$where);

       return $res;

    }

    public function add_diamond_parameter($data)
    {
        $this->table = "diamond_parameters";
        return $this->insert($data);
    }

    public function get_diamond_file_master($data)
    {
        $this->table = "diamond_upload_file_master";
        $no_of_records_per_page = $data['per_page'];
        $offset = ($data['page_no']-1) * $no_of_records_per_page;

        if ($data['table_name'] != "" && $data['table_name'] != 'null') {
            $string .= "AND {$this->table}.dm_our_table_name LIKE '%{$data['table_name']}%'";
        }
        if ($data['table_value'] != "" && $data['table_value'] != 'null') {
            $string .= "AND {$this->table}.dm_our_table_value LIKE '%{$data['table_value']}%'";
        }

        $this->selectFields = "dm_id,dm_tenent_id,dm_entity_id,dm_warehouse_id,dm_our_table_name,dm_our_table_value,dm_excel_feild_name,dm_excel_feild_value,dm_excel_feild_sub_value"; //Select fields

        $this->join = [["warehouse as wh","wh.w_short_name = {$this->table}.dm_warehouse_id AND {$this->table}.dm_tenent_id = wh.w_tenent_id AND {$this->table}.dm_entity_id = wh.w_entity_id","INNER"]]; // Join in pagination

        $where = " {$this->table}.dm_status = '0' AND  {$this->table}.dm_tenent_id = '{$data['tenent_id']}' AND  {$this->table}.dm_entity_id = '{$data['entity_id']}' AND  {$this->table}.dm_status=0 AND  wh.w_action=0 {$string}"; // Where condition


        $res['total_rows'] = $this->total($where);
        $res['pg_value'] =  $data['page_no'] .' - '.$data['per_page'];
        // echo $res['total_rows'];
    
        $res['toal_pages'] = ceil($res['total_rows']/$data['per_page']);

        $res['resData'] = $this->pageRows($offset, $limit = $data['per_page'],$where);

        return $res;


    }

    public function add_diamond_file_master($data)
    {      
       return $this->insert($data);      
    }

    public function get_pm_diamond($data)
    {
        $this->table = "diamond_price_master";
        $no_of_records_per_page = $data['per_page'];
        $offset = ($data['page_no']-1) * $no_of_records_per_page;
        
        if ($data['dp_id'] != "" && $data['dp_id'] != 'null') {
            $string .= "AND dp_id = '{$data['dp_id']}'";
        }
        if ($data['shape'] != "" && $data['shape'] != 'null') {
            $string .= "AND dp_shape LIKE '%{$data['shape']}%'";
        }
        if ($data['color'] != "" && $data['color'] != 'null') {
            $string .= "AND dp_col LIKE '%{$data['color']}%'";
        }
        if ($data['clarity'] != "" && $data['clarity'] != 'null') {
            $string .= "AND dp_cla LIKE '%{$data['clarity']}%'";
        }
        if ($data['cut'] != "" && $data['cut'] != 'null') {
            $string .= "AND dp_cut LIKE '%{$data['cut']}%'";
        }
        if ($data['polish'] != "" && $data['polish'] != 'null') {
            $string .= "AND dp_pol LIKE '%{$data['polish']}%'";
        }
        if ($data['symmetry'] != "" && $data['symmetry'] != 'null') {
            $string .= "AND dp_sym LIKE '%{$data['symmetry']}%'";
        }
        if ($data['carat'] != "" && $data['carat'] != 'null') {
            $string .= "AND dp_carat LIKE '%{$data['carat']}%'";
        }
        if ($data['markup_type'] != "" && $data['markup_type'] != 'null') {
            $string .= "AND dp_markup_type LIKE '%{$data['markup_type']}%'";
        }
        if ($data['markup_value'] != "" && $data['markup_value'] != 'null') {
            $string .= "AND dp_markup_value LIKE '%{$data['markup_value']}%'";
        }


        $this->selectFields = "`dp_id`,`dp_shape`,`dp_color`,`dp_cla`,`dp_cut`,`dp_polish`,`dp_symm`,`dp_carat`,`dp_vendor_name`,`dp_entity_id`,`dp_markup_type`,`dp_markup_value`"; //Select fields

       // $this->join = [["warehouse as wh","wh.w_short_name = {$this->table}.dm_warehouse_id AND {$this->table}.dm_tenent_id = wh.w_tenent_id AND {$this->table}.dm_entity_id = wh.w_entity_id","INNER"]]; // Join in pagination

        $where = " dp_status=0 AND dp_entity_id='{$data['entity_id']}' {$string}"; // Where condition


        $res['total_rows'] = $this->total($where);
        $res['pg_value'] =  $data['page_no'] .' - '.$data['per_page'];
        // echo $res['total_rows'];
    
        $res['toal_pages'] = ceil($res['total_rows']/$data['per_page']);

        $res['resData'] = $this->pageRows($offset, $limit = $data['per_page'],$where);

        return $res;
    }

    public function save_pm_diamond($data)
    {
        $this->table = "diamond_price_master";
        if($data['dp_id'] != ""){
          return   $this->update($data);
        }else{
          return   $this->insert($data);
        }
    }
}

?>
