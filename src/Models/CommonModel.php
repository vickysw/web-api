<?php 
/**
 *  13 July 2020
 *  For Common Model
 */

 namespace App\Models;

use App\Core\Model;

class CommonModel extends Model{


   /**
     * For update Data
    */
    public function updateData($data,$table,$where)
    { 

        if(is_array($data)){
          
          $uplist = '';
          $this->table = $table;
          $this->getFields();
          foreach ($data as $k => $v) {
    
            if (in_array($k, $this->fields)) {
                $uplist .= "`$k`='$v'".",";
                }
            }
          $data = rtrim($uplist,',');
       
        }
  
      $sql =  "UPDATE `{$table}` SET {$data} WHERE {$where}";
     
      if ($this->db->query($sql)) {
        if ($rows = $this->db->get_affected_rows()) {

            return $rows;

        } else {

            return false;

        }    

    } else {

        return false;

    }

  }

    /**
     * For get Data
     */
    public function getData($table,$where = "",$multiRows = false,$selected_field = ""){ 
        $string = "";
        if($where != "")
          $string .= " WHERE ".$where;

        if($selected_field != "")
          $field = $selected_field;
        else
          $field = '*';

        $sql = "SELECT $field FROM ".$table.$string;
    
       if($multiRows)
           $data = $this->db->getAll($sql);
       else 
           $data = $this->db->getRow($sql);

        return $data;

    }

    /**
     *  For Upload multiple images 
     * 
     */
    public function multiUploadImages($dir_name,$fileKey)
    {
        if(!file_exists(UPLOAD_PATH.$dir_name)){
            mkdir(UPLOAD_PATH.$dir_name, 0777, true);
            chmod(UPLOAD_PATH.$dir_name,0777);
        }
    
        $file_ary = array();

      foreach($_FILES[$fileKey]['name'] as $k=>$v)
      {
          $name = $_FILES[$fileKey]['name'][$k];
          $fileinfo =   pathinfo($name);

          $filename = str_replace('/','_',$dir_name).'_'.time().rand(0,9).'.'. $fileinfo['extension'];
          $type = $_FILES[$fileKey]['type'][$k];
          $tmp_name = $_FILES[$fileKey]['tmp_name'][$k];
         //$error = $_FILES[$fileKey]['error'][$v];
         //$size = $_FILES[$fileKey]['size'][$v];
         
        $new_file = UPLOAD_PATH.$dir_name.'/'.$filename;
        move_uploaded_file($tmp_name,$new_file);
        $file_ary[$k]['path'] = $new_file;
        $file_ary[$k]['type'] = $type;

      }

      return $file_ary;
       
    }

     /**
     *  For Upload multiple image
     * 
     */
    public function uploadImage($dir_name,$fileKey)
    {
        if(!file_exists(UPLOAD_PATH.$dir_name)){
            mkdir(UPLOAD_PATH.$dir_name, 0777, true);
            chmod(UPLOAD_PATH.$dir_name,0777);
        }
    
        $image_file = "";

          $name = $_FILES[$fileKey]['name'];
          $fileinfo =   pathinfo($name);

          $filename = str_replace('/','_',$dir_name).'_'.time().rand(0,9).'.'. $fileinfo['extension'];
          $type = $_FILES[$fileKey]['type'];
          $tmp_name = $_FILES[$fileKey]['tmp_name'];
         //$error = $_FILES[$fileKey]['error'][$v];
         //$size = $_FILES[$fileKey]['size'][$v];
         
        $image_file = UPLOAD_PATH.$dir_name.'/'.$filename;
        move_uploaded_file($tmp_name,$image_file);

      return $image_file;
       
    }

    /**
     *  13 Aug 2020
     *  For check User
     */
    public function check_user_exists($data)
    {
       $query = "SELECT CONCAT(`bp_fname`,' ',`bp_lname`) AS `user_name`,`bk_profile` AS `user_profile`,`bp_tal_id`,`bp_unique_id`,`bp_tal_pass`,`bp_ref_id`,`bp_id`,`bp_user_type` FROM basic_profile AS `bp` INNER JOIN `basic_kyc` AS bk ON bp.bp_unique_id = bk.bk_bp_unique_id AND bp.entity_id = bk.bk_entity_id WHERE bp.bp_email='{$data['apu_username']}' AND bp.entity_id='{$data['entity_id']}' AND bp.bp_action = 0 AND bp.bp_status=1  AND bp.bp_user_type = 12 ";
       $loginData = $this->db->getOne($query);
      if(!empty($loginData))
      {
        if(password_verify($data['apu_password'],$loginData['bp_tal_pass'])){
          $login_str = "SELECT DISTINCT 
          tccl.team_id, bp.bp_fname, tccl.entity_id AS tenent_id, tccl.sub_entity_id AS legal_entity_id, tccl.client_id, 
          tccl.team_user_role_id,
  
          (SELECT bp_crop_name FROM basic_profile WHERE entity_id = tccl.sub_entity_id AND bp_user_type = if(tccl.entity_id = tccl.sub_entity_id, 10, 11) AND bp_status = 1) AS company_name, 
  
          if(tccl.entity_id = tccl.sub_entity_id, 
          (SELECT logo FROM `company_detail` WHERE entity_id = tccl.entity_id), 
          (SELECT scd_logo FROM `sub_company_detail` WHERE sub_entity_id = tccl.sub_entity_id AND tenent_id = tccl.entity_id )
          ) AS company_logo, 
  
          if(tccl.entity_id = tccl.sub_entity_id, 
          (SELECT cd_tal_id FROM `company_detail` WHERE entity_id = tccl.entity_id), 
          (SELECT scd_bp_tal_id FROM `sub_company_detail` WHERE sub_entity_id = tccl.sub_entity_id AND tenent_id = tccl.entity_id )
          ) AS company_bp_id, 
  
          if(tccl.entity_id = tccl.sub_entity_id, 
          (SELECT is_super_admin FROM `company_detail` WHERE entity_id = tccl.entity_id), 0
          ) AS is_super_admin 
  
          FROM 
  
          basic_profile AS `bp` INNER JOIN 
          team_client_company_listing AS `tccl` ON bp.bp_tal_id = tccl.team_id AND bp.bp_tenent_id = tccl.entity_id INNER JOIN 
          user_role AS `ur` ON tccl.team_user_role_id = ur.ur_r_type AND ur.entity_id = tccl.entity_id 
  
          WHERE 
          bp.bp_tenent_id = '{$data['entity_id']}' AND tccl.status = 0 AND bp.bp_tal_id = '{$loginData['bp_tal_id']}'";

          $res = $this->db->getOne($login_str);

          if(!empty($res))
          {
              $companies[] = ['tenent_id' => $res['tenent_id'],'client_id' => $res['client_id'],'entity_id' => $res['legal_entity_id'],'company_bp_id' => $res['company_bp_id'],'company_name' => $res['company_name'], 'company_logo' => $res['company_logo'],'user_role' => $res['team_user_role_id'], 'permission' => $this->get_role_rights($res), 'assign_key' => $res['is_super_admin']];
          }
            $json = array(
              'user_tal_id' => $loginData['bp_tal_id'],
              'user_bp_id' => $loginData['bp_id'],
              'user_bp_unique_id' => $loginData['bp_unique_id'],
              'user_name' => $loginData['user_name'],
              'user_profile' => $loginData['user_profile'],
              'user_type' => $loginData['bp_user_type'],
              'company_count' => !empty($res) ? count($res) : 0,
              'companies_list' => $companies,
             );
             return $json;
        }else{
          respond_error_to_api("Your Password is not match. Please try after sometime.");  
        }
      }else{
        respond_error_to_api("User not exits.");  
      }
    }

    protected function get_role_rights($data)
    {
      if($data['tenent_id'] != "" && $data['entity_id'] != "" && $data['user_role'] != "" && $data['user_type'] != "" ){
            if($data['user_type']==12){
                    if($data['is_super_admin'] == 1){
                      $str .= ' AND am.m_flag IN (0,1,2,3,5) ';
                    } else {
                      $str .= ' AND am.m_flag IN (1,2,3,5) ';
                    }
            } else if($data['user_type']==15){
                $str .= ' AND am.m_flag IN (2,5) ';
            } else if($data['user_type']==16){
                $str .= ' AND am.m_flag IN (3,5) ';
            }

        $sql = "SELECT rr_create,rr_edit,rr_delete,rr_view,am.ref_id AS ref_id,am.group_name AS group_name,am.group_code AS group_code,am.module_code AS module_code,am.module_name AS module_name,am.module_route AS module_route,am.m_page_fa AS fa,am.m_id AS m_id FROM role_rights AS `rr` INNER JOIN all_module AS `am` ON rr.rr_m_id = am.m_id WHERE rr_entity_id='{$data['entity_id']}' AND rr_tenent_id='{$data['tenent_id']}' AND rr.rr_user_role='{$data['user_role']}' {$str} ORDER BY am.m_position ASC";

        $resource = $this->db->getAll($sql);

            foreach($resource as $key=>$value)
            {
              $module[$key]['module_code'] = $value['module_code'];
              $module[$key]['module_name'] = $value['module_name'];
              $module[$key]['main_menu_route'] = $value['main_menu_route'];
              $module[$key]['fa_icon'] = $value['fa'];
              $module[$key]['group_name'] = $value['group_name'];
              $module[$key]['group_code'] = $value['group_code'];
              $module[$key]['ref_id'] = $value['ref_id'];
              $module[$key]['m_id'] = $value['m_id'];
              $module[$key]['create'] = $value['rr_create'];
              $module[$key]['edit'] = $value['rr_edit'];
              $module[$key]['delete'] = $value['rr_delete'];
              $module[$key]['view'] = $value['rr_view'];
            }
            return buildTree($module);
          }else{
            respond_error_to_api("Parameter missing for Authorisation access.");  
          }
    }

    /**
     *  18 Aug 2020
     *  For prepare build tree {Permission}
     */
    public function buildTree(array $elements, $parentId = 0)
    {
      $branch = array();
      foreach ($elements as $element) {
        if ($element['ref_id'] == $parentId) {
          $children = $this->buildTree($elements, $element['m_id']);
          if ($children) {
            $element['submenu'] = $children;
          } else {
            $element['submenu'] = array();
          }
          $branch[] = $element;
        }
      }
      return $branch;
    }
    /**
     *  20 Aug 2020
     *  For base64 Image Upload
     */

    public function base64ImageUpload($path,$base64)
    {
      list($type, $base64) = explode(';', $base64);
      list(, $base64) = explode(',', $base64);
      $base64 = base64_decode($base64);
      if (!file_exists(UPLOAD_PATH.$path)) {
        mkdir(UPLOAD_PATH.$path, 0777, true);
        chmod(UPLOAD_PATH.$path, 0777);
      }
      $path = UPLOAD_PATH.$path . '/' . time().rand(0,9).'_image.jpg';
      file_put_contents($path, $base64);
      return $path;
    }

    public function FetchDomainDetail($data){
      $qry = "SELECT cd.name AS `cmp_name`,cd.entity_id AS `entity_id`,cd.logo AS `logo`,cd.entity_code AS `entity_code`,cd.cd_tal_id AS `tal_id` FROM `company_detail` AS `cd` INNER JOIN `basic_profile` AS `bp` ON cd.cd_tal_id = bp.bp_tal_id WHERE cd.status= 0  AND bp.bp_status=1 AND bp.bp_type='org' AND cd.cmp_url='".$data['domain_name']."'";
      $resData =  $this->db->getAll($qry);

      $res['resData'] = array();
      if(is_array($resData) && count($resData) > 0)
      {
        foreach($resData as $key => $row)
        {
          if ($data['status'] == "Admin_panel")
              $status_title = "Admin Panel";
          else if ($data['status'] == "User_panel")
              $status_title = "User Panel";
      
          $res['resData'][] = array(
            "name" => $row['cmp_name'] . ' ' . $status_title,
            "entity_id" => $row['entity_id'],
            "logo" => $row['logo'] == "" ? "" : SITE_URL.$row['logo'],
            "entity_code" => $row['entity_code'],
            "cd_tal_id" => $row['tal_id'],
            "title" => $status_title
          );
        }
      }
      return $res;
    }

}

?>
