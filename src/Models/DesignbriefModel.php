<?php 
/**
 *  15 July 2020
 *  For Manage Orders 
 */

 namespace App\Models;

use App\Core\Model;

class DesignbriefModel extends Model{


    public function getDesignBrief($designBriefID = "")
    {

        $this->table = "design_brief";

        if($designBriefID != "")
            $DesignBriefList = $this->selectByPk($designBriefID);
        else{
            $sql = "select * from $this->table";

            $DesignBriefList = $this->db->getAll($sql);
        }    

        return $DesignBriefList;

    }


    public function save_data($data)
    {

            $this->db->rollback_transaction();
      
            $DeB['created_by'] = $data['created_by'];
            $DeB['created_id'] = $data['created_id'];
            $DeB['create_for'] = $data['create_for'];
            $DeB['title'] = $data['title'];
            //$DeB['design_brief_id'] = $data['created_by'] == "SalesTeam" ? $data['design_brief_id'];
      
            $designID = $this->insert($DeB);
            $updateDeB['design_brief_id'] = ($data['created_by'] == "SalesTeam") ? "CRM_DB_".$designID : "DB_".$designID;// For updating design briefe id
            $updateDeB['id'] = $designID;
            $this->update($updateDeB);
            
            $this->table = "design_brief_detail"; //Update table param 

            $dbD['design_brief_id'] =  $designID;
            $dbD['comments'] =  $data['comments'];
            $dbD['category'] =  $data['category'];
            $dbD['gender'] =  $data['gender'];
            $dbD['semi_mount'] =  $data['semi_mount'];
            $dbD['design_date_deadline'] =  $data['design_date_deadline'];
            $dbD['previous_order_number'] =  $data['previous_order_number'];
            $dbD['price_from'] =  $data['price_from'];
            $dbD['price_to'] =  $data['price_to'];
            $dbD['approx_quantity'] =  $data['approx_quantity'];
            
            $detailId = $this->insert($dbD);

            
            if(!empty($data['reference_images'])){
                $this->table = "design_brief_references";

                foreach($data['reference_images'] as $key =>$value)
                {
                    $dbR[$key]['design_brief_id'] = $designID;
                    $dbR[$key]['attachment_path'] = $value['path'];
                    $dbR[$key]['file_type'] = $value['type'];
                    $dbR[$key]['updated_by'] = $data['created_id'];
                }
                
                $dbRID = $this->insert_batch($dbR);
            }

            $this->table = "design_brief_metal_detail";

            $dbM['design_brief_id'] =  $designID;
            $dbM['ring_size'] =  $data['ring_size'];
            $dbM['necklece_length'] =  $data['necklece_length'];
            $dbM['bracelet_length'] =  $data['bracelet_length'];
            $dbM['gold_weight_from'] =  $data['gold_weight_from'];
            $dbM['gold_weight_to'] =  $data['gold_weight_to'];
            $dbM['main_gold_type'] =  $data['main_gold_type'];
            $dbM['second_gold_tone'] =  $data['second_gold_tone'];
            $dbM['width_top'] =  $data['width_top'];
            $dbM['width_bottom'] =  $data['width_bottom'];
            $dbM['thickness_top'] =  $data['thickness_top'];
            $dbM['thickness_bottom'] =  $data['thickness_bottom'];

            $dbMID = $this->insert($dbM);

            $this->table = "design_brief_setting_type";

            $dbS['design_brief_id'] = $designID;
            $dbS['center_type'] = $data['center_type'];
            $dbS['setting'] = $data['setting'];
            $dbS['plating_type'] = $data['plating_type'];

            $dbsID = $this->insert($dbS);


            $this->table = "design_brief_finishing_touches";

            $dbT['design_brief_id'] = $designID;
            $dbT['comfort_fit'] = $data['comfort_fit'];
            $dbT['engraving_text'] = $data['engraving_text'];
            $dbT['engraving_logo'] = $data['engraving_logo'];
            $dbT['finishing_type'] = $data['finishing_type'];
            $dbT['fitted_wedder'] = $data['fitted_wedder'];
            $dbT['font_style'] = $data['font_style'];

            $dbTID = $this->insert($dbT);

            
            if($data['accent_melee_stone']){   
                $this->table = "design_brief_accent_melee_stones";

                $dbMelee['design_brief_id'] = $designID;
                $dbMelee['approx_price'] = $data['approx_price'];
                $dbMelee['carat_from'] = $data['carat_from'];
                $dbMelee['carat_to'] = $data['carat_to'];
                $dbMelee['clarity'] = $data['clarity'];
                $dbMelee['color'] = $data['color'];
                $dbMelee['cut'] = $data['cut'];
                $dbMelee['shape'] = $data['shape'];

                $dbMeleeID = $this->insert($dbMelee);
            }

            if($data['semi_mount'])
            {
                $this->table = "design_brief_accent_melee_stones";

                $dbcs['design_brief_id'] = $designID;
                $dbcs['shape'] = $data['cs_shape'];
                $dbcs['carat_from'] = $data['cs_carat_from'];
                $dbcs['carat_to'] = $data['cs_carat_to'];
                $dbcs['diameter_from'] = $data['cs_diameter_from'];
                $dbcs['type'] = 'CENTER';
                $dbcs['diameter_to'] = $data['cs_diameter_to'];

                $center_Id = $this->insert($dbcs);
            }

            if($data['center_stone'] != ""){
                $this->table = "design_brief_stone_selection";

                $dbss['design_brief_id'] = $designID;
                $dbss['center_stone'] = $center_Id;
                $dbss['accent_melee_stone'] = $dbMeleeID;

                $dbTID = $this->insert($dbss);
            }

            
            // var_dump($dbMeleeID);

           if($this->db->transation_status() === false)
           {
               $this->db->transaction_rollback();
               respond_error_to_api("error");
           } 
            $this->db->transation_commit();
    
         return $designID;

    }

    public function getDesignBriefList($data)
    {
        $no_of_records_per_page = $data['per_page'];
        $offset = ($data['page_no']-1) * $no_of_records_per_page;

        if($data['user_type'] == 13 || $data['user_type'] == 15)
        $where = "(create_for = '".$data['bp_tal_id']."' OR created_id = '".$data['bp_tal_id']."') and is_deleted = 1 ";
        else
        $where = "created_id = '".$data['bp_tal_id']."' and is_deleted = 1 ";
            

        $res['total_rows'] = $this->total($where);
        $res['pg_value'] =  $data['page_no'] .' - '.$data['per_page'];
        $res['toal_pages'] = ceil($res['total_rows']/$data['per_page']);
        

        $res['resData'] = $this->pageRows($offset, $limit = $data['per_page'],$where);

       return $res;

    }

    public function getDesignBriefDetail($data)
    {
        // $where = "create_for = '".$data['bp_tal_id']."' OR created_id = '".$data['bp_tal_id']."' and is_deleted = 1 ";
        $this->db->query("SET  SESSION SQL_MODE=''");
        $sql = "SELECT db.id,db.design_brief_id,db.title,db.create_for,dbd.id as design_brief_detail_id,dbd.approx_quantity,dbd.category,dbd.comments,dbd.design_date_deadline,dbd.gender,dbd.previous_order_number,dbd.price_from,dbd.price_to,dbd.semi_mount,GROUP_CONCAT( DISTINCT dbr.id) as design_brief_references_id ,GROUP_CONCAT(dbr.attachment_path) as attachment_path,GROUP_CONCAT(dbr.file_type)as file_type,dbmd.id as design_brief_metal_detail_id ,dbmd.bracelet_length,dbmd.gold_weight_from,dbmd.gold_weight_to,dbmd.main_gold_type,dbmd.necklece_length,dbmd.ring_size,dbmd.second_gold_tone,dbmd.thickness_bottom,dbmd.thickness_top,dbmd.width_bottom,dbmd.width_top,dbst.id as design_brief_setting_type_id,dbst.center_type,dbst.plating_type,dbst.setting,dbss.id as design_brief_stone_selection_id,dbss.accent_melee_stone,dbss.center_stone,dbft.id as design_brief_finishing_touches_id,dbft.comfort_fit,dbft.engraving_logo,dbft.engraving_text,dbft.finishing_type,dbft.fitted_wedder,dbft.font_style,GROUP_CONCAT( dbams.id SEPARATOR '|') as design_brief_accent_melee_stones_id,GROUP_CONCAT( dbams.type SEPARATOR '|') as `type` ,GROUP_CONCAT( dbams.approx_price SEPARATOR '|') as approx_price ,GROUP_CONCAT( dbams.carat_from SEPARATOR '|') as carat_from,GROUP_CONCAT( dbams.carat_to SEPARATOR '|') as carat_to,GROUP_CONCAT( dbams.clarity SEPARATOR '|') as clarity,GROUP_CONCAT(dbams.color SEPARATOR '|') as color,GROUP_CONCAT(dbams.cut SEPARATOR '|') as cut,GROUP_CONCAT(dbams.shape SEPARATOR '|') as shape,GROUP_CONCAT(IFNULL(dbams.diameter_from,'null') SEPARATOR '|') as diameter_from,GROUP_CONCAT(IFNULL(dbams.diameter_to,'null') SEPARATOR '|') as diameter_to  FROM design_brief as db LEFT JOIN design_brief_detail dbd ON dbd.design_brief_id = db.id LEFT JOIN design_brief_references dbr ON dbr.design_brief_id = db.id LEFT JOIN design_brief_metal_detail dbmd ON dbmd.design_brief_id = db.id LEFT JOIN design_brief_setting_type dbst ON dbst.design_brief_id = db.id LEFT JOIN design_brief_stone_selection dbss ON dbss.design_brief_id = db.id LEFT JOIN design_brief_finishing_touches dbft ON dbft.design_brief_id = db.id LEFT JOIN design_brief_accent_melee_stones dbams ON dbams.design_brief_id = db.id  WHERE db.id =".$data['design_brief_id']." AND ( db.created_id = '".$data['bp_tal_id']."' OR db.create_for = '".$data['bp_tal_id']."' )   GROUP BY db.id";
		
        $res = $this->db->getRow($sql);;

       return $res;

    }

    /**
     * Vanraj M.K | 22/07/2020
     * Update accent melee stones
     */
    public function update_melee_stone($data)
    {
        $dataAccent['id'] = $data['accent_melee_stone_id'];
        $dataAccent['approx_price'] = $data['approx_price'];
        $dataAccent['carat_from'] = $data['carat_from'];
        $dataAccent['carat_to'] = $data['carat_to'];
        $dataAccent['clarity'] = $data['clarity'];
        $dataAccent['color'] = $data['color'];
        $dataAccent['cut'] = $data['cut'];
        $dataAccent['shape'] = $data['shape'];

        $this->table = 'design_brief_accent_melee_stones';

        return $this->db->update($dataAccent);
    }

    /**
     *  Vanraj M.K | 22 July 2020
     *  Update design brief data
     */
    public function update_data($data)
    {

        $this->db->rollback_transaction();
        
        $DeB['id']  = $data['design_brief_id'];
        $DeB['updated_by'] = $data['bp_tal_id'];
        $DeB['title'] = $data['title'];
        
        $this->table = "design_brief";
        $is_update_status = $this->update($DeB);
        
        
            $this->table = "design_brief_detail"; //Update table param 

            $dbD['comments'] =  $data['comments'];
            $dbD['category'] =  $data['category'];
            $dbD['gender'] =  $data['gender'];
            $dbD['semi_mount'] =  $data['semi_mount'];
            $dbD['design_date_deadline'] =  $data['design_date_deadline'];
            $dbD['previous_order_number'] =  $data['previous_order_number'];
            $dbD['price_from'] =  $data['price_from'] == "" ? 0 : $data['price_from'];
            $dbD['price_to'] =  $data['price_to'] == "" ? 0 : $data['price_to'];
            $dbD['approx_quantity'] =  $data['approx_quantity'] == "" ? 1 : $data['approx_quantity'];
            // var_dump($data['design_brief_detail_id']);
            if($data['design_brief_detail_id'] == "null" || $data['design_brief_detail_id'] == "" ){
                $dbD['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbD);
            }else{
                $dbD['id'] =  $data['design_brief_detail_id'];
                $is_update_status = $this->update($dbD);
            }
        

        
        if(!empty($data['reference_images'])){
            $this->table = "design_brief_references";

            foreach($data['reference_images'] as $key =>$value)
            {
                $dbR[$key]['design_brief_id'] = $data['design_brief_id'];
                $dbR[$key]['attachment_path'] = $value['path'];
                $dbR[$key]['file_type'] = $value['type'];
                $dbR[$key]['updated_by'] = $data['created_id'];
            }
            
            $dbRID = $this->insert_batch($dbR);
        }

        // if($data['design_brief_metal_detail_id'] != ""){

            $this->table = "design_brief_metal_detail";

            // $dbM['id'] =  $data['design_brief_metal_detail_id'];
            $dbM['ring_size'] =  $data['ring_size'];
            $dbM['necklece_length'] =  $data['necklece_length'];
            $dbM['bracelet_length'] =  $data['bracelet_length'];
            $dbM['gold_weight_from'] =  $data['gold_weight_from'];
            $dbM['gold_weight_to'] =  $data['gold_weight_to'];
            $dbM['main_gold_type'] =  $data['main_gold_type'];
            $dbM['second_gold_tone'] =  $data['second_gold_tone'];
            $dbM['width_top'] =  $data['width_top'];
            $dbM['width_bottom'] =  $data['width_bottom'];
            $dbM['thickness_top'] =  $data['thickness_top'];
            $dbM['thickness_bottom'] =  $data['thickness_bottom'];


            if($data['design_brief_metal_detail_id'] == "null" || $data['design_brief_metal_detail_id'] == ""){
                $dbM['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbM);
            }else{
                $dbM['id'] =  $data['design_brief_metal_detail_id'];
                $is_update_status = $this->update($dbM);
            }


           
        // }

        // if($data['design_brief_setting_type_id'] != ""){

            $this->table = "design_brief_setting_type";

            // $dbS['id'] = $data['design_brief_setting_type_id'];
            $dbS['center_type'] = $data['center_type'];
            $dbS['setting'] = $data['setting'];
            $dbS['plating_type'] = $data['plating_type'];
            
            if($data['design_brief_setting_type_id'] == "null" || $data['design_brief_setting_type_id'] == ""){
                $dbS['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbS);
            }else{
                $dbS['id'] =  $data['design_brief_setting_type_id'];
                $is_update_status = $this->update($dbS);
            }


           
        // }

        // if($data['design_brief_finishing_touches_id'] != ""){
            $this->table = "design_brief_finishing_touches";

            // $dbT['id'] = $data['design_brief_finishing_touches_id'];
            $dbT['comfort_fit'] = $data['comfort_fit'];
            $dbT['engraving_text'] = $data['engraving_text'];
            $dbT['engraving_logo'] = $data['engraving_logo'];
            $dbT['finishing_type'] = $data['finishing_type'];
            $dbT['fitted_wedder'] = $data['fitted_wedder'];
            $dbT['font_style'] = $data['font_style'];

            if($data['design_brief_finishing_touches_id'] == "null" || $data['design_brief_finishing_touches_id'] == ""){
                $dbT['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbT);
            }else{
                $dbT['id'] =  $data['design_brief_finishing_touches_id'];
                $is_update_status = $this->update($dbT);

            }

        // }
        
        // if($data['center_stone'] != ""){
            $this->table = "design_brief_stone_selection";

            // $dbss['id'] = $data['design_brief_stone_selection_id'];
            $dbss['center_stone'] = $data['center_stone'];
            $dbss['accent_melee_stone'] = $data['accent_melee_stone'];

            if($data['design_brief_stone_selection_id'] == "null" || $data['design_brief_stone_selection_id'] == ""){
                $dbss['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbss);
            }else{
                $dbss['id'] =  $data['design_brief_stone_selection_id'];
                $is_update_status = $this->update($dbss);


            }

        // }

        // if($data['accent_melee_stone'] != ""){   
            $this->table = "design_brief_accent_melee_stones";

            // $dbMelee['id'] = $data['design_brief_accent_melee_stones_id'];
            $dbMelee['approx_price'] = $data['approx_price'];
            $dbMelee['carat_from'] = $data['carat_from'];
            $dbMelee['carat_to'] = $data['carat_to'];
            $dbMelee['clarity'] = $data['clarity'];
            $dbMelee['color'] = $data['color'];
            $dbMelee['cut'] = $data['cut'];
            $dbMelee['shape'] = $data['shape'];
            $stone = explode("|",$data["design_brief_accent_melee_stones_id"]);
            if($data['design_brief_accent_melee_stones_id'] == "null" || $data['design_brief_accent_melee_stones_id'] == ""){
                $dbMelee['design_brief_id'] =  $data['design_brief_id'];
                $is_update_status = $this->insert($dbMelee);
            }else{
                $dbMelee['id'] =  $stone[0];
                $is_update_status = $this->update($dbMelee);

            if($data['semi_mount'])    
            {
                $dbcs['shape'] = $data['cs_shape'];
                $dbcs['carat_from'] = $data['cs_carat_from'];
                $dbcs['carat_to'] = $data['cs_carat_to'];
                $dbcs['diameter_from'] = $data['cs_diameter_from'];
                $dbcs['diameter_to'] = $data['cs_diameter_to'];

                if($data['design_brief_accent_melee_stones_id'] == "null" || $data['design_brief_accent_melee_stones_id'] == ""){
                    $dbcs['design_brief_id'] =  $data['design_brief_id'];
                    $is_update_status = $this->insert($dbcs);
                }else{
                    $dbcs['id'] =  $stone[1];//$data['design_brief_accent_melee_stones_id'];
                    $is_update_status = $this->update($dbcs);
                }
            }

        }
        // var_dump($dbMeleeID);

       if($this->db->transation_status() === false)
       {
           $this->db->transaction_rollback();
           respond_error_to_api("error");
       } 
        $this->db->transation_commit();

    //  return $is_update_status;
    return true;
    }


    public function delete_reference_image($data)
    {
        $this->table = "design_brief_references";
        $res = $this->delete($data['design_brief_references_id']);
        if($res)
            unlink($data['attachment_path']);

        return $res;    
    }

    public function delete_design_brief($data)
    {
        $this->table = "design_brief";
        $list['id'] = $data['design_brief_id'];
        $list['is_deleted'] = 0;
        $res = $this->update($list);
      
        return $res;    
    }


    public function upload_idea_board_image($data)
    {
        $dbRID = false;
        if(!empty($data['idea_board_images'])){
            $this->table = "design_brief_references";

            foreach($data['idea_board_images'] as $key =>$value)
            {
                $dbR[$key]['design_brief_id'] = $data['design_brief_id'];
                $dbR[$key]['attachment_path'] = $value['path'];
                $dbR[$key]['file_type'] = $value['type'];
                $dbR[$key]['created_by'] = $data['bp_tal_id'];
                $dbR[$key]['from_type'] = 'idea_board';

            }
            
            $dbRID = $this->insert_batch($dbR);
        }

        return $dbRID;
    }


    public function idea_board_state($data)
    {
        $this->table = "design_brief_stats";
        $insData['design_brief_id'] = $data['design_brief_id'];
        $insData['customer_id'] = $data['customer_id'];
        $insData['state'] = $data['state'];
     
        return $this->insert($insData);
        
    }
}

?>
