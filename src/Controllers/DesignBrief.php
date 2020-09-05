<?php
/**
 * @author Vanraj Kalsariya <sbh.user14@gmail.com>
 * @link https://tech.suranabrother.com/admin-panel/#/login 
 * 
 */
use  App\Models\CommonModel as common;
use App\Models\DesignbriefModel as DesignBriefModel;

class DesignBrief extends App\Core\Controller
{
    private $mydata;
    private $model;
    public function __construct()
    {
        parent::__construct();
        
        $this->loader->helper('custom');
        $this->model = new DesignbriefModel("design_brief");
        $this->common = new common('design_brief');
        $this->mydata = json_decode(file_get_contents("php://input"), true);
        if (empty($this->mydata)) {
            $this->mydata   = $_POST;
        }

        if($this->action == "ConfirmOrder"){

            //Check Tenant is exists or not
            $where = "status = 0 AND entity_id ='".$this->mydata['tenant_id']."'";
            if(empty($this->common->getData("company_detail",$where)))
                 respond_error_to_api("Tenant not exits.");
           
            //Check Entity is exists or not
            $where = "scd_status = 0 AND sub_entity_id ='".$this->mydata['entity_id']."' AND tenent_id ='".$this->mydata['tenant_id']."' ";
            if(empty($this->common->getData("  ",$where)))
                respond_error_to_api("Entity not exits.");     

            //Check customer is exists or not 
            $where = "bp_action = 0 AND bp_tal_id ='".$this->mydata['customer_id']."' AND bp_user_type = 13";
            if(empty($this->common->getData("basic_profile",$where)))
                respond_error_to_api("Customer not exits.");     
        }
    }

   /**
    *  @date 16 July 2020
     * @used by Customer as well as Sales team
     * @return \Deasign_Brief_ID
     * @table  design_brief 
     * @param  created_by => Created by whome 
     * @param  created_id => Created by ID       ->  bp_tal_id 
     * @param  create_for => For whome to create ->  customer_id
     * 
     * @table design_brief_detail
     * @param  comments/category/gender/semi_mount/design_date_deadline/previous_order_number/price_from/price_to/approx_quantity
     * @table design_brief_references
     * @param $_FILES
     * @table design_brief_metal_detail
     * @param ring_size/necklece_length/bracelet_length/gold_weight_from/gold_weight_to/main_gold_type/second_gold_tone/width_top/width_bottom/thickness_top/thickness_bottom	
     * @table design_brief_setting_type
     * @param setting/center_type/plating_type
     * @table design_brief_stone_selection
     * @param center_stone/accent_melee_stone
     * @table design_brief_finishing_touches
     * @param finishing_type/comfort_fit/fitted_wedder/engraving_text/engraving_logo/font_style
     * 
     * If Accent Stones Yes then 
     *          @table design_brief_accent_melee_stones
     *          @param shape/carat_from/carat_to/color/clarity/cut/approx_price

      
    */
    public function CreateDesignbrief()
    {
        $keys = array("created_by", "created_id","create_for","title","comments","category","gender","semi_mount","design_date_deadline","previous_order_number","price_from","price_to","approx_quantity","ring_size","necklece_length","bracelet_length","gold_weight_from","gold_weight_to","main_gold_type","second_gold_tone","width_top","width_bottom","thickness_top","thickness_bottom","setting","center_type","plating_type","center_stone","accent_melee_stone","finishing_type","comfort_fit","fitted_wedder","engraving_text","font_style","center_stone","shape","carat_from","carat_to","color","clarity","cut","approx_price","cs_shape","cs_carat_from","cs_carat_to","cs_diameter_from","cs_diameter_to");

        $data =  check_api_keys($keys,$this->mydata);
        // References IMages max 3 
        if(!empty($_FILES['reference_images']))
        {
            if(COUNT($_FILES['reference_images']['name']) <= 3){
                $imageData  = $this->common->multiUploadImages($dir_name = 'design_brief',$key = "reference_images"); // Parameter  as dir name and file key path
                $data['reference_images'] = $imageData;
            }else{
                respond_error_to_api("error", "You can not add more than 3 attachment.");
            }
        }

        if(!empty($_FILES['engraving_logo']))
        {
            $imgData = $this->common->uploadImage($dir_name = 'design_brief/engraving_logo',$key = "engraving_logo");
            $data['engraving_logo'] = $imgData;
        }
        
        // $dbModel = new DesignbriefModel("design_brief");

        $designBriefID = $this->model->save_data($data);
        
        // $designBriefID  = $dbModel->insert_order();

        respond_success_to_api("success", $this->model->getDesignBrief($designBriefID));
    }

    /**
     * 18 July 2020
     * For get  Design Brief list
     */
    public function getDesignbriefList()
    {
        $keys = array("bp_tal_id","per_page","page_no");

        $data =  check_api_keys($keys,$this->mydata);

        // $userQuery = "SELECT * FROM basic_profile WHERE bp_tal_id='".$data['bp_tal_id']."'";
        $data['user_type'] = $this->common->getData('basic_profile'," bp_tal_id='".$data['bp_tal_id']."' ")['bp_tal_member_type']; 
 

        // $userQuery = "SELECT * FROM basic_profile WHERE bp_tal_id='".$data['bp_tal_id']."'";
       $this->table = "design_brief"; 
       $designData =  $this->model->getDesignBriefList($data);

       
        respond_success_to_api("success", $designData);

    }

    
    /**
     *  Vanraj M. K | 21 July 2020
     *  For get design brief form
     */
    public function getDesignbrief()
    {
        $keys = array("bp_tal_id","design_brief_id");

        $data =  check_api_keys($keys,$this->mydata);


        // $userQuery = "SELECT * FROM basic_profile WHERE bp_tal_id='".$data['bp_tal_id']."'";
       $this->table = "design_brief"; 
       $designData =  $this->model->getDesignBriefDetail($data);
       
        foreach($designData as  $key=>$value)
        {
            if($key == "design_brief_accent_melee_stones_id")
            {
				
                if($value != "")
                {
                    $type = explode('|',$designData['type']);
                    $id =  explode('|',$designData['design_brief_accent_melee_stones_id']);
                    $approx_price =  explode('|',$designData['approx_price']);
                    $carat_from =  explode('|',$designData['carat_from']);
                    $carat_to =  explode('|',$designData['carat_to']);
                    $clarity =  explode('|',$designData['clarity']);
                    $color =  explode('|',$designData['color']);
                    $cut =  explode('|',$designData['cut']);
                    $shape =  explode('|',$designData['shape']);
                    $diameter_from =  explode('|',$designData['diameter_from']);
                    $diameter_to =  explode('|',$designData['diameter_to']);
					
                    foreach($type as $key=>$value)
                    {
                        $designData[$value]['design_brief_accent_melee_stones_id'] = $id[$key];
						$designData[$value]['type'] = $type[$key];
                        $designData[$value]['approx_price'] = $approx_price[$key];
                        $designData[$value]['carat_from'] = $carat_from[$key];
                        $designData[$value]['carat_to'] = $carat_to[$key];
                        $designData[$value]['clarity'] = $clarity[$key];
                        $designData[$value]['color'] = $color[$key];
                        $designData[$value]['cut'] = $cut[$key];
                        $designData[$value]['shape'] = $shape[$key];
						if($value != "ACCENT"){
							$designData[$value]['diameter_from'] = $diameter_from[$key];
							$designData[$value]['diameter_to'] = $diameter_to[$key];
						}
                    }
					
                }
				
				if(!isset($designData['ACCENT']))
						$designData['ACCENT'] = (object)[];
				if(!isset($designData['CENTER']))
						$designData['CENTER'] = (object)[];	
				
				
            }
          
            if($key == "design_brief_references_id" ){
                if($value != "")
                {
                    $refe = explode(',',$designData['design_brief_references_id']);
                    $path = explode(',',$designData['attachment_path']);
                    $type = explode(',',$designData['file_type']);
                  
                   
                    foreach($refe as $k=>$v)
                    {
                        if(count($designData['references_data']) > 2)
                            break;
                        $designData['references_data'][$k]['design_brief_references_id'] = $v;
                        $designData['references_data'][$k]['attachment_path'] = $path[$k];
                        $designData['references_data'][$k]['file_type'] = $type[$k];
                    }
                }
            }
        }
       
        respond_success_to_api("success", $designData);
    }


    /**
     * Vanraj M. K | 22 July 2020 
     * Get Accent Melee stones
     */
    public function updateAccentMeleeStones()
    {
        $keys = array("bp_tal_id","design_brief_id","accent_melee_stone_id","approx_price","carat_from","carat_to","clarity","color","cut","shape");

        $data =  check_api_keys($keys,$this->mydata);

        $checkUpdate = $this->model->update_melee_stone($data);

    //    $getRes =  $this->common->getData('design_brief_accent_melee_stones'," id='".$data['accent_melee_stone_id']."'");
        if($checkUpdate)
            respond_success_to_api("success", $designData);
        else
            respond_error_to_api("error", "try after some time.");

    }

    /***
     *  Vanraj M. K | 21 July 2020
     *  Update  Design Briefe 
     */
    public function  updateDesignBrief()
    {
        $keys = array("bp_tal_id","design_brief_id","design_brief_detail_id","design_brief_references_id","design_brief_metal_detail_id","design_brief_setting_type_id","design_brief_stone_selection_id","design_brief_finishing_touches_id","design_brief_accent_melee_stones_id","title","comments","category","gender","semi_mount","design_date_deadline","previous_order_number","price_from","price_to","approx_quantity","ring_size","necklece_length","bracelet_length","gold_weight_from","gold_weight_to","main_gold_type","second_gold_tone","width_top","width_bottom","thickness_top","thickness_bottom","setting","center_type","plating_type","center_stone","accent_melee_stone","finishing_type","comfort_fit","fitted_wedder","engraving_text","font_style","center_stone","shape","carat_from","carat_to","color","clarity","cut","approx_price","cs_shape","cs_carat_from","cs_carat_to","cs_diameter_from","cs_diameter_to");

        $data =  check_api_keys($keys,$this->mydata);

        if(!empty($_FILES['reference_images']))
        {
            if(COUNT($_FILES['reference_images']['name']) <= 3){
                $imageData  = $this->common->multiUploadImages($dir_name = 'design_brief',$key = "reference_images"); // Parameter  as dir name and file key path
                $data['reference_images'] = $imageData;
            }else{
                respond_error_to_api("error", "You can not add more than 3 attachment.");
            }
        }

        if(!empty($_FILES['engraving_logo']))
        {
            $imgData = $this->common->uploadImage($dir_name = 'design_brief/engraving_logo',$key = "engraving_logo");
            $data['engraving_logo'] = $imgData;
        }
        
        $checkUpdate = $this->model->update_data($data);

        if($checkUpdate)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "try after some time.");     
    }

    /**
     * Vanraj M.K | 24 July 2020
     * Delete Reference image
     */
    public function deleteReferences()
    {
        $keys = array("bp_tal_id","design_brief_id","design_brief_references_id","attachment_path");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->delete_reference_image($data);
     
        if($res)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "try after some time."); 

    }

    /**
     * Vanraj M.K | 24 July 2020
     * Delete Reference image
     */
    public function deleteDesignBrief()
    {
        $keys = array("bp_tal_id","design_brief_id");

        $data =  check_api_keys($keys,$this->mydata);

        $res = $this->model->delete_design_brief($data);
     
        if($res)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "try after some time."); 

    }

    /**
     *  Vanraj M.K | 25 July 2020
     *  upload ideaboard  images
     */
    public function uploadIdeaboard()
    {
        $keys = array("bp_tal_id","design_brief_id");

        $data =  check_api_keys($keys,$this->mydata);
        // var_dump($_FILES);
        if(!empty($_FILES['idea_board_images']))
        {
                $imageData  = $this->common->multiUploadImages($dir_name = 'idea_board',$key = "idea_board_images"); // Parameter  as dir name and file key path
                $data['idea_board_images'] = $imageData;
         
        }else{
            respond_error_to_api('file not found', array(array(
                "index" => 0,
                "fieldName" => idea_board_images,
                "message" =>  ucfirst(strtolower(str_replace("_", " ", "file not found")))
            )));

            // check_api_keys(['idea_board_images'],$_FILES);
        }
        $res = $this->model->upload_idea_board_image($data);
     
        if($res)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "try after some time."); 
    }

    public function getIdeaboard()
    {
        $keys = array("bp_tal_id","design_brief_id");

        $data =  check_api_keys($keys,$this->mydata);

        $ideaBoradlist['idea_board'] = $this->common->getData('design_brief_references'," design_brief_id='".$data['design_brief_id']."' ",$multiRows = true); 

        if($ideaBoradlist['idea_board']){
            $ideaBoradlist['stats'] = $this->common->getData('design_brief_stats'," design_brief_id='".$data['design_brief_id']."' AND is_active = 1")['state'];
            respond_success_to_api("success",$ideaBoradlist);
        }else
            respond_error_to_api("error", "try after some time."); 


    }

    /**
     *  Vanraj M.K | 27 July 2020 
     *  @param state 0 => declined, 1 => Accept
     * 
     */
    public function ideaboardAcceptDeclined()
    {
        $keys = array("bp_tal_id","design_brief_id","customer_id","state");

        $data =  check_api_keys($keys,$this->mydata);

        $where = " design_brief_id='".$data['design_brief_id']."' AND  	customer_id='".$data['customer_id']."' AND is_active = 1";
        $ideaBoardStats = $this->common->getData('design_brief_stats', $where); 

        if($ideaBoardStats){

          $updateData = 'is_active = 0 ';
          $where = " design_brief_id = ".$ideaBoardStats['design_brief_id'];
          $this->common->updateData($updateData,"design_brief_stats",$where);

        }

        $res = $this->model->idea_board_state($data);
      
        if($res)
            respond_success_to_api("success");
        else
            respond_error_to_api("error", "try after some time."); 
    }

}
?>
