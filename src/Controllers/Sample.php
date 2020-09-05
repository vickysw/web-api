<?php
/**
 * Kalsariya Vanraj M.  
 * 17 Aug 2020
 * Purpose: This is  sample code controller 
 */

use App\Models\CommonModel as common;        // Load common model
use App\Models\SampleModel as sampleModel;   // Load particular model 
use PhpOffice\PhpSpreadsheet\Spreadsheet;    // Excel Sheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
use Mpdf\Mpdf;                               // PDF

class Sample extends App\Core\Controller
{
    private $mydata;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->loader->helper('custom'); // Load custom helper
        $this->model = new sampleModel("order_list");  // Create sample controller object
        $this->common = new common('order_list'); // Create sample model object
        $this->mydata = json_decode(file_get_contents("php://input"), true); // Get input param
        if (empty($this->mydata)) {
            $this->mydata   = $_POST;

            if(isset($this->mydata['tenent_id']) && $this->mydata['tenent_id'] != "" ){
                $this->mydata['tenent_id'] = encrypt($this->mydata['tenent_id']);
            }else if(isset($this->mydata['entity_id']) && $this->mydata['entity_id'] != "" ){
                $this->mydata['entity_id'] = encrypt($this->mydata['entity_id']);
            }
        }

        if($this->action == "TestAction"){ // 

           // Your logic    
        }
    }

     /**
     * 17 Aug 2020
     * For get list
     * @param page_no
     * @param page_per {per item}
     * @param q {search item}
     */
    public function getList()
    {

        $keys = array("tenent_id","entity_id","customer_id","q","page_no","page_per");

        $data =  check_api_keys($keys,$this->mydata);

        $validation = array(
            "tenent_id" => array(
                "rule"  => "required",
                "value" => $data["tenent_id"]
            ),
            "entity_id" => array(
                "rule"  => "trim",
                "value" => $data["entity_id"]
            )
        );

        $this->check_validation($validation);
 
       $orderData =  $this->model->sample_list($data);

       
        respond_success_to_api("success", $orderData);

    }

    /**
     * 17 Aug 2020
     * For Add/update/delete data
     */
    public function saveData()
    {
        $keys = array("dm_tenent_id","dm_entity_id","dm_warehouse_id","dm_our_table_name");

        $data =  check_api_keys($keys,$this->mydata);

        $validation = array(
            "tenent_id" => array(
                "rule"  => "required",
                "value" => $data["tenent_id"]
            ),
            "entity_id" => array(
                "rule"  => "trim",
                "value" => $data["entity_id"]
            )
        );

        $this->check_validation($validation);

        $updateData['dm_id'] = $data['dm_id']; // pass primary key If you want to update 
        $updateData['dm_tenent_id'] = $data['tenent_id']; 
        $updateData['dm_entity_id'] = $data['entity_id']; 
        $updateData['dm_warehouse_id'] = $data['warehouse_id']; 
        $updateData['dm_our_table_name'] = $data['table_name']; 
    
        $this->model->save_diamond_file_master($updateData);

       
        respond_success_to_api("success", $orderData);

    }
    /**
     *  Vanraj Kalsariya M.
     *  18 Aug 2020
     *  For demo purpose
     *  Token: cd32fd1a3e33b16d0cfffa74823ba807e5774a8e
     *  Command: composer require phpoffice/phpspreadsheet --prefer-source
     *  documentation: https://phpoffice.github.io/PhpSpreadsheet/namespaces/phpoffice-phpspreadsheet.html
     *  For refresh composer: composer dump-autoload -o
     */
    public function excelDemo()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }
    /**
     *  Vanraj Kalsariya M.
     *  18 Aug 2020
     *  For demo purpose (v8.0.7)
     *  Command: composer require mpdf/mpdf 
     *  documentation: https://mpdf.github.io
     *  For refresh composer: composer dump-autoload -o
     */
    public function mpdfDemo()
    {
        $mpdf = new \Mpdf\Mpdf();

        // Write some HTML code:
        $mpdf->WriteHTML('Hello World');

        // Output a PDF file directly to the browser
        $mpdf->Output();
    }
}
?>
