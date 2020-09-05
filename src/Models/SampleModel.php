<?php 
/**
 *  13 July 2020
 *  For Manage Orders 
 */

 namespace App\Models;

use App\Core\Model;

class SampleModel extends Model{


    public function sample_list($data){

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

    /** 
     *  Add function 
     */
    public function add_data($data)
    {
        if($data['dm_id'])
            return $this->insert($data);
        else
            return $this->insert($data);      
    }
    /**
     *  Example: Rollback
     * 
     */
    public function insert_order($data)
    {
        $this->db->rollback_transaction();

        $orderQuery = "INSERT INTO order_list (`o_type`,`o_date`,`o_order_id`,`o_tenant_id`,`o_entity_id`,`o_customer_id`,`o_transcation_id`,`o_qty`,`o_total_price`,`o_total_price_usd`,`o_currency`,`o_convert_rate`) VALUES ".$data['order_list'];
        // echo 'orderQuery>>'.$orderQuery; die;
        $this->db->query($query);
        
        $orderItemQuery = "INSERT INTO order_items (`od_order_id`,`od_tenant_id`,`od_entity_id`,`od_vendor_id`,`od_product_sku`,`od_product_type`,`od_product_name`,`od_product_qty`,`od_product_price`,`od_product_total_price`,`od_product_price_usd`,`od_product_total_price_usd`,`od_stone_type`,`od_stone_shape`,`od_stone_carat`,`od_stone_color`,`od_stone_clarity`,`od_stone_cut`,`od_stone_polish`,`od_stone_symmetry`,`od_stone_lab`,`od_stone_rap`,`od_stone_dis`) VALUES ".$data['order_items'];
        $this->db->query($orderItemQuery);

        if($this->db->transation_status() === false)
        {
            $this->db->transaction_rollback();
            respond_error_to_api("Rollback error");
        } 
         $this->db->transation_commit();

         return true;
    }
}

?>
