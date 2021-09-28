<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transporters
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Transporter extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transporter_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Amey Trading : Registered Transporters  ';
        
        $this->loadViews("transporters/index", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    public function listing()
    {
        // if($this->isAdmin() == TRUE)
        // if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $data['transporterRecords'] = $this->transporter_model->getTranportersList();
            $this->load->model('client_model');
            $data['consignees'] = $this->client_model->getClientList();
            
            //echo'<pre>'; print_r($data['consignees']); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Registered Transporters';
            $this->loadViews("transporters/index", $this->global, $data, NULL);
        // }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('transporter_model');
            $data['roles'] = '';//$this->client_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Amey Trading : Add New Transporter';

            $this->loadViews("transporters/addNew", $this->global, $data, NULL);
        // }
    }
    
    /**
     * This function is used to load the add new form
     */
    function edit()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $id = trim(filter_input(INPUT_GET, 'id'));
            $this->load->model('transporter_model');
            $data['profile'] = $this->transporter_model->getTransporterInfo($id);
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Transporter Profile';

            $this->loadViews("transporters/edit", $this->global, $data, NULL);
        // }
    }
    
    function deletetransporter(){
        $id = trim(filter_input(INPUT_GET, 'id'));
        //echo'<pre>'; print_r($id); die;
        
        $this->load->model('transporter_model');
        $result = $this->transporter_model->deleteTransporter($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Transporter Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Transporter record deletion failed');
        }
        
        redirect('/transporter/listing');
    }
    
    
    function transportstatementpdf(){
        ob_start();
        
        $id = trim(filter_input(INPUT_GET, 'id'));
        $this->load->model('transporter_model');
        $transporterexpenses = $this->transporter_model->getTranporterExpenseList($id);
        $transporterInfo = $this->transporter_model->getTransporterInfo($id);
        //Get transporter advances
        $transporteradvances = $this->transporter_model->getTransporterAdvances($transporterInfo->id);
        // echo'<pre>HTTPS Transporter Expense: '; print_r($transporterexpenses);
        // die;
        
        //amount
        $totalPayments = 0;
        foreach($transporteradvances as $adv){
            $totalPayments += $adv->amount;
        }
        
        $totalCharges = 0;
        foreach($transporterexpenses as $exp){
            $totalCharges = $totalCharges + $exp->clearing_charge + $exp->extra_paid;
        }
        
        // echo'<pre>Agredd amount: '; print_r($totalPayments); 
        // echo'<pre>'; print_r($totalCharges); 
        $balancePayable = $totalCharges - $totalPayments; 
        
        //get all debit transactions against this persion and minus from agreed amount Plus extra charge- and u get the answer.
        // echo'<pre>'; print_r($totalCharges);
        // die;
        
        
        $this->load->library('Pdf');
            
        // create new PDF document
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        //Custom Header and footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        
        $pdf->SetMargins(25.0, 40, 25.0); // left = 2.5 cm, top = 4 cm, right = 2.5cm
        $pdf->SetFooterMargin(1.5);  // the bottom margin has to be set with SetFooterMargin
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); // we use a default constant to avoid quality lost, PDF_IMAGE_SCALE_RATIO = 1
        $pdf->setImageScale(0.5);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->AddPage();
        // $pdf->AddPage('P', $page_format, false, false);

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;" text-align: center;><strong>'.$transporterInfo->transporter_name.' Transport Charges</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Date</th>
                     <th>Vehicle No.</th>
                     <th>Consignee</th>
                     <th>Container No.</th>
                     <th>T812 Nr.</th>
                     <th align="right">Transport Rate</th>
                     <th align="right">Advance</th>
                  </tr>
              </thead>
              <tbody>'; 
               
              $totalCalcAdvance = 0;
              $totalCalcBalance = 0;
              $totalCalcAmount = 0;
              $totaltripagreed = 0;
              foreach($transporterexpenses as $c){
                    $totalCalcAdvance += $c->advance;
                    $totalCalcBalance += $c->balance;
                    $totalCalcAmount += $c->total;
                    $totaltripagreed = $totaltripagreed + ($c->clearing_charge + $c->extra_paid);
                    $tbl .= '
                        <tr nobr="true">
                            <td>'.$c->transport_date.'</td> 
                            <td>'.$c->vehicle_number.'</td>
                            <td>'.$c->consignee.'</td> 
                            <td>'.$c->container_no.' </td>
                            <td>'.$c->t812_no.' </td>
                            <td align="right">'.number_format(($c->clearing_charge + $c->extra_paid), 2).'</td> 
                            <td align="right">'.number_format($c->advance, 2).'</td> 
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <tr nobr="true">
                        <td></td> 
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>
                        <td></td>
                    </tr>
                    <tr nobr="true">
                        <td></td> 
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Trips Total Agreed: </td> 
                        <td align="right">'.number_format($totaltripagreed, 2).'</td> 
                    </tr>
                    <tr nobr="true">
                        <td></td> 
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>
                        <td></td>
                    </tr>
                    <br />
                    <br />
                </tbody>
            </table>
        ';
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%; text-align: center;"><strong>'.$transporterInfo->transporter_name.' Cash In</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        //$transporteradvances
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Date</th>
                     <th width="15%">Type</th>
                     <th>Payment For</th>
                     <th width="15%">Payment Ref</th>
                     <th></th>
                     <th></th>
                     <th></th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
              
              foreach($transporteradvances as $adv){
                    $amountPaid = $amountPaid + $adv->amount;
                    $tbl .= '
                        <tr nobr="true">
                            <td>'.$adv->transaction_date.'</td> 
                            <td width="15%">'.$adv->txn_type.'</td>
                            <td>'.$adv->payment_for.'</td> 
                            <td width="15%">'.$adv->ref.' </td>
                            <td></td>
                            <td></td> 
                            <td></td> 
                            <td align="right">'.number_format($adv->amount,2).'</td> 
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <tr nobr="true">
                        <td></td> 
                        <td width="15%"></td>
                        <td></td> 
                        <td width="15%"></td>
                        <td></td>
                        <td></td> 
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Total Expenses: </td> 
                        <td align="right">'.number_format($amountPaid, 2).'</td> 
                    </tr>
                    <br />
                    <br />
                    <tr nobr="true">
                        <td></td> 
                        <td width="15%"></td>
                        <td></td> 
                        <td width="15%"></td>
                        <td></td>
                        <td></td> 
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Balance Payable: </td> 
                        <td align="right">'.number_format($balancePayable, 2).'</td> 
                    </tr>
                </tbody>
            </table>
        ';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // Clean any content of the output buffer
        ob_end_clean();
        $pdf->Output($transporterInfo->transporter_name.'transportstmt'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function transportstatement(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $data['transporterRecords'] = $this->transporter_model->getTranportersList();
            $this->load->model('client_model');
            $data['consignees'] = $this->client_model->getClientList();
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            
            //echo'<pre>'; print_r($data['consignees']); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Registered Transporters';
            $this->loadViews("transporters/statement", $this->global, $data, NULL);
        // }
    }
    
    function updateTransporterProfile()
    {
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            
            // $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            // $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            // $this->form_validation->set_rules('password','Password','required|max_length[20]');
            // $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            // $this->form_validation->set_rules('role','Role','trim|required|numeric');
            // $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            // if($this->form_validation->run() == FALSE)
            // {
            //     $this->addNewClient();
            // }
            // else
            // {
                $transporterInfo = array();
                $id = trim(filter_input(INPUT_GET, 'id'));
                $transporterInfo['transporter_name'] = trim(filter_input(INPUT_POST, 'transporter_name'));
                $transporterInfo['contact_person'] = trim(filter_input(INPUT_POST, 'contact_person'));
                $transporterInfo['physical_address '] = trim(filter_input(INPUT_POST, 'physical_address'));
                $transporterInfo['mobile_no'] = trim(filter_input(INPUT_POST, 'mobile_no'));
                $transporterInfo['email'] = trim(filter_input(INPUT_POST, 'email'));
                $transporterInfo['notes'] = trim(filter_input(INPUT_POST, 'notes'));
                $transporterInfo['updated_at'] = date('d-m-Y H:m:s');
                
                $this->load->model('transporter_model');
                $result = $this->transporter_model->editTransporter($transporterInfo, $id);//updateTransporter($transporterInfo, $id);
                //echo'<pre>'; print_r($result); die;
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'Transporter record updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Record update failed');
                }
                
                redirect('/transporter/edit?id='.$id);
            //}
        // }
    }
    
    function launch(){
        $tripInfo = [];
        $tripInfo['trip_code'] = "CTA_".$this->codeGen(3);
        
        echo'<pre>'; print_r($_REQUEST); 
        echo'<pre>'; print_r($tripInfo); 
        die;
    }
    
    public function codeGen($digits = 6){
        $i = 0;
        $fileNo = "";
        while($i < $digits){
                //Generate a random unique number
                $fileNo .=mt_rand(0,9);
                $i++;
        }

        return $fileNo;
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewTransporter()
    {
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            // [transporter/addNewTransporter] => 
            // [transporter_name] => Transporter 1
            // [contact_person] => Musa Juma
            // [mobile_no] => 0791036665
            // [email] => ceo.ogego@gmail.com
            // [address] => Milimani, Kisumu
            // [notes] => Well...  
            // $this->load->library('form_validation');
            
            // $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            // $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            // $this->form_validation->set_rules('password','Password','required|max_length[20]');
            // $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            // $this->form_validation->set_rules('role','Role','trim|required|numeric');
            // $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            // if($this->form_validation->run() == FALSE)
            // {
            //     $this->addNewClient();
            // }
            // else
            // {
                $transporterInfo = array();
                //id 	client_name 	contact_person 	address 	reg_date 	pin_no 	vat_no 	physical_address 	tel_no 	email 	ac_type 	created_at 	updated_at 
                $transporterInfo['transporter_name'] = trim(filter_input(INPUT_POST, 'transporter_name'));
                $transporterInfo['contact_person'] = trim(filter_input(INPUT_POST, 'contact_person'));
                $transporterInfo['physical_address '] = trim(filter_input(INPUT_POST, 'address'));
                $transporterInfo['mobile_no'] = trim(filter_input(INPUT_POST, 'mobile_no'));
                $transporterInfo['email'] = trim(filter_input(INPUT_POST, 'email'));
                $transporterInfo['notes'] = trim(filter_input(INPUT_POST, 'notes'));
                // echo'<pre>Transporter: '; print_r($transporterInfo); 
                // die;
                $this->load->model('transporter_model');
                $result = $this->transporter_model->addNewTransporter($transporterInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Transporter created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('/transporter/add');
            //}
        // }
    }
    
    /**
     *  Get transporter trucks
     * 
     * */
     function transporterTrucks(){
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('transporter_model');
            $data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
            $data['trucks'] = $this->transporter_model->getTransporterTrucks($data['transporter']);
            //echo'<pre>'; print_r($data['trucks']); die;
            
            $data['transporterInfo'] = $this->transporter_model->getTransporterInfo($data['transporter']);
            $data['transporter_name'] = $data['transporterInfo']->transporter_name;
            // echo'<pre> Transporter Info: '; print_r($data['transporterInfo']->transporter_name); 
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Trucks';

            $this->loadViews("transporters/trucks", $this->global, $data, NULL);
        // }
     }
     
        function editTruck(){
            // echo'<pre> HTTPS Request: '; print_r($_REQUEST); 
            // die;
            
            // if($this->isAdmin() == TRUE)
            // {
            //     $this->loadThis();
            // }
            // else
            // {
                $data['truck_id'] = trim(filter_input(INPUT_GET, 'truck_id'));
                $data['transporter_id'] = trim(filter_input(INPUT_GET, 'transporter_id'));
                // echo'<pre> Data: '; print_r($data); 
                // die;
                
                $this->load->model('transporter_model');
                // $data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
                $data['trucks'] = $this->transporter_model->getTransporterTrucks($data['transporter_id']);
                // echo'<pre> Transporter trucks: '; print_r($data['trucks']); 
                // die;
                
                $data['truckInfo'] = $this->transporter_model->getTruckInfoByID($data['truck_id']);
                $data['truckInfo'] = $data['truckInfo'][0];
                // echo'<pre> Truck Information: '; print_r($data['truckInfo']);
                // die;
                
                $data['transporterInfo'] = $this->transporter_model->getTransporterInfo($data['transporter_id']);
                // echo'<pre> Transporter Info: '; print_r($data['transporterInfo']); 
                // die;
                
                $this->global['pageTitle'] = 'Amey Trading : Trucks';
                $this->loadViews("transporters/editTrucks", $this->global, $data, NULL);
            // }
        }
        
        function updateTruck(){
            // echo'<pre>HTTPS REquest: '; print_r($_REQUEST);
            // die;
            
            // HTTPS REquest: Array
            // (
            //     [transporter/updateTruck] => 
            //     [id] => 18
            //     [transporter_id] => 18
            //     [transporter_name] => WIILS
            //     [truck_no] => DDD 000X
            //     [driver_name] => Dan Dagau
            //     [driver_mobile_no] => +31256718097
            //     [driver_email] => dagau@gmail.com
            //     [__tawkuuid] => e::cargomis.ameytrading.co.ke::0ruBIp cRmdVOkQ0lw2kr9fkTnlNpCO FBqxGTIJO1rzO4x7 LPrDAWbftjPaAeI::2
            //     [TawkConnectionTime] => 0
            //     [ci_session] => 037960664646cc7607eb6e7a19d655f4eaf2bc86
            // )
            $truck_id = trim(filter_input(INPUT_GET, 'id'));
            $truckInfo['transporter_id'] = trim(filter_input(INPUT_POST, 'transporter_id'));
            $truckInfo['transporter_name'] = trim(filter_input(INPUT_POST, 'transporter_name'));
            $truckInfo['truck_no'] = trim(filter_input(INPUT_POST, 'truck_no'));
            $truckInfo['driver_name'] = trim(filter_input(INPUT_POST, 'driver_name'));
            $truckInfo['driver_mobile_no'] = trim(filter_input(INPUT_POST, 'driver_mobile_no'));
            $truckInfo['driver_email'] = trim(filter_input(INPUT_POST, 'driver_email'));
            $truckInfo['created_at'] = date("Y-m-d H:i:s");
            
            // echo'<pre>Truck Information: '; print_r($truckInfo);
            // die;
            
            $this->load->model('transporter_model');
            $result = $this->transporter_model->updateTruckByID($truck_id, $truckInfo);
            
            // echo'<pre>HTTPS Response: '; print_r($result);
            // die;
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'Truck updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Truck update failed');
            }
            
            redirect('/transporter/transporterTrucks?id='.$truckInfo['transporter_id']);
        }
        
        function addNewTruck(){
        $truckInfo = array();
        $truckInfo['truck_no'] = trim(filter_input(INPUT_POST, 'truck_no'));
        $truckInfo['transporter_id'] = trim(filter_input(INPUT_POST, 'transporter_id'));
        //transporter_name
        $truckInfo['transporter_name'] = trim(filter_input(INPUT_POST, 'transporter_name'));
        $truckInfo['driver_name '] = trim(filter_input(INPUT_POST, 'driver_name'));
        $truckInfo['driver_mobile_no'] = trim(filter_input(INPUT_POST, 'driver_mobile_no'));
        $truckInfo['driver_email'] = trim(filter_input(INPUT_POST, 'driver_email'));
        $truckInfo['created_at'] = date("Y-m-d H:i:s");
        
        $this->load->model('transporter_model');
        $result = $this->transporter_model->addNewTruck($truckInfo);
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'New Truck created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Truck addition failed');
        }
        
        redirect('/transporter/transporterTrucks?id='.$truckInfo['transporter_id']);
      }
      
        function deleteTruck(){
            // echo'<pre>HTTPS DELETE Request: '; print_r($_REQUEST);
            // die;
            
            $truck_id = trim(filter_input(INPUT_GET, 'truck_id'));
            $transporter_id = trim(filter_input(INPUT_GET, 'transporter_id'));
            // echo'<pre>Truck id: '; print_r($truck_id);
            // die;
            
            //deleteTruckByID($truck_id)
            $this->load->model('transporter_model');
            $result = $this->transporter_model->deleteTruckByID($truck_id);
            // echo'<pre>HTTPS DELETE Response: '; print_r($result);
            // die;
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'Truck deleted successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Truck delete action failed');
            }
            
            redirect('/transporter/transporterTrucks?id='.$transporter_id);
        }
      
        function transporterextrafee(){
          
            // if($this->isAdmin() == TRUE)
            // {
            //     $this->loadThis();
            // }
            // else
            // {
                // echo'<pre>HTTPS Extra Fee request: '; print_r($_REQUEST); 
                // die;
                
                //file_no id
                $container_no = trim(filter_input(INPUT_GET, 'container_no'));
                $data['container_no'] = $container_no;
                $data['transporter_id'] = trim(filter_input(INPUT_GET, 'tid'));
                $data['truck_no'] = trim(filter_input(INPUT_GET, 'truck_no')); 
                
                //get doc by file_no
                $this->load->model('document_model');
                // $docinfo = $this->document_model->getDocumentData($file_no); 
                $docinfo = $this->document_model->getDocumentDataByCont($data['container_no']); 
                
                #get transporter by id
                $this->load->model('transporter_model');
                $transporterInfo = $this->transporter_model->getTransporterInfoById($data['transporter_id']);
                $data['transporter_name'] = $transporterInfo->transporter_name;
                // echo'<pre>';print_r($transporterInfo); die;
                
                #get fees from fees table
                $this->load->model('account_model');
                $data['transportation_expense_fee_types'] = $this->account_model->getFees();
                
                #Get payment by truck reg no $data['truck_no']
                $data['transporterExpensesRecords'] = $this->transporter_model->getTranporterExpenseList($data['transporter_id']);
                
                // $data['trucks'] = $this->transporter_model->getTransporterTrucks($data['transporter_id']);
                // echo'<pre>';print_r($data['trucks']); die;
                
                // $data['transporterextrafee'] = $this->transporter_model->getTransporteExtraFee($data['transporter_id']);
                $data['transporterextrafee'] = $this->transporter_model->getTransporteExtraFeeByTruck($data['truck_no']);
                // echo'<pre>';print_r($data['transporterExpensesRecords']);
                // die;
                
                //get transporter balance by file_no
                $expense = $this->transporter_model->getTranporterExpense($data['container_no']);
                // echo'<pre> Transporter Expenses: '; print_r($expense); 
                // die;
                $data['balance'] = $expense[0]->balance;
                if(empty($data['balance'])){
                    $data['balance']  = 0;
                }
                // echo'<pre>'; print_r(); die;
                
                #This form will go to fetch the extra expenses via a form
                $this->global['pageTitle'] = 'Amey Trading : Tranporter Expenses';
                $this->loadViews("transporters/transporterextrafee", $this->global, $data, NULL);
            // }
      }
      
      function addtransporterextrafee(){
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        #get input from user
        $expenseInfo['transporter_name'] = trim(filter_input(INPUT_POST, 'transporter_name'));
        $expenseInfo['transporter_id'] = trim(filter_input(INPUT_POST, 'transporter_id'));
        $expenseInfo['fee_type'] = trim(filter_input(INPUT_POST, 'fee_type'));
        $expenseInfo['fee_amount'] = trim(filter_input(INPUT_POST, 'fee_amount'));
        $expenseInfo['fee_note'] = trim(filter_input(INPUT_POST, 'fee_note'));
        $expenseInfo['container_no'] = trim(filter_input(INPUT_POST, 'container_no'));
        $truck_no = trim(filter_input(INPUT_GET, 'truck_no'));
        $expenseInfo['truck_no'] = $truck_no;
        // $file_no = $expenseInfo['file_no'];
        $container_no = $expenseInfo['container_no'];
        $amount = $expenseInfo['fee_amount'];
        
        // Info for the payment tracking table per transporter
        $paymentInfo['truck_no'] = $truck_no;
        $paymentInfo['transporter_id'] = $expenseInfo['transporter_id'];
        $paymentInfo['txn_type'] = $expenseInfo['fee_type'];
        $paymentInfo['transaction_date'] = date('d/m/Y');
        $paymentInfo['ref'] =  $expenseInfo['fee_note'];
        $paymentInfo['payment_for'] = $truck_no .' ('.$expenseInfo['container_no'].')';
        $paymentInfo['amount'] = $expenseInfo['fee_amount'];
        $paymentInfo['created_at'] = date('d/m/Y');
        // echo'<pre>';print_r($paymentInfo); //die;
        
        //Get documents. This is where balance is affected...
        $this->load->model('transporter_model');
        $file = $this->transporter_model->getTranporterExpense($container_no);
        
        // echo'<pre>'; print_r($file); 
        // die;
        $curr_balance = $file[0]->balance;
        if(!isset($file[0]->balance) || empty($file[0]->balance)){$curr_balance = 0;}
        
        // $new = $last_paid_amount + $payInfo['amount'];
        $agreedCharges = $file[0]->clearing_charge + $file[0]->extra_paid;
        //balance payable per trip is = amount agreed + extra charges - total monies
        $updatedBalance = $curr_balance + $amount;
        $newBalance = $agreedCharges - $updatedBalance;
        
        //update the tbl_transport_expense by deducting amount from the balance by file_no
        $transportExpense['balance'] = $newBalance;
        $transportExpense['truck_no'] = $truck_no;

        // echo'<pre>Agreed: '; print_r($agreedCharges); 
        // echo'<pre>Current balance: '; print_r($curr_balance); 
        // echo'<pre>Updated balance: '; print_r($updatedBalance); 
        // echo'<pre>New balance: '; print_r($newBalance); 
        // die;
          
        #get the above data and pass them to the model; for calculation and storage into the db
        $this->load->model('transporter_model');
        //addNewTransporterExtraExpense($expenseInfo, $file_no, $paymentInfo, $totalbalance)
        //$result = $this->transporter_model->addNewTransporterExtraExpense($expenseInfo, $amount, $file_no, $paymentInfo, $newBalance);
        $result = $this->transporter_model->addNewTransporterExtraExpense($expenseInfo, $container_no, $paymentInfo, $newBalance);
        
        // echo'<pre>'; print_r($result); die;
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Transporter expense record successfully');
            
        }
        else
        {
            $this->session->set_flashdata('error', 'Transporter expense record creation failed');
        }
            
        redirect('transporter/transporterextrafee?container_no='.$expenseInfo['container_no'].'&tid='.$expenseInfo['transporter_id']);
      }
      
        function deleteextrafee(){
            $id = trim(filter_input(INPUT_GET, 'id'));
            echo'<pre>'; print_r($id); 
            echo'<pre>'; print_r($_REQUEST); 
            die;
        
            $this->load->model('transporter_model');
            $result = $this->transporter_model->removeTransporterExtraExpense($id);
            // echo'<pre>'; print_r($result); die;
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'Transport Expense Record deleted successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Transport Expense Record deletion failed');
            }
            
            redirect('/transporter/transporterextrafee?id='.$id);
        }
      
        function transportexpenses(){
            //echo'<pre>'; print_r($_REQUEST); die;
            // if($this->isAdmin() == TRUE)
            // {
            //     $this->loadThis();
            // }
            // else
            // {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
                $data['searchText'] = $searchText;
                $this->load->library('pagination');
                
                $id = trim(filter_input(INPUT_GET, 'id'));
                $data['id'] = $id;
                $data['transporterExpensesRecords'] = $this->transporter_model->getTranporterExpenseList($id);
                
                // echo'<pre>'; print_r($data['transporterExpensesRecords']); die;
                
                $this->load->model('client_model');
                $data['consignees'] = $this->client_model->getClientList();
                
                $this->load->model('document_model');
                $data['files'] = $this->document_model->getDocs();
                // echo'<pre>'; print_r($data['files']); die;
                
                $data['trucks'] = $this->transporter_model->getTransporterTrucks($id);
                
                $data['transporter_id'] = $id;
                
                $this->global['pageTitle'] = 'Amey Trading : Tranporters Expense';
                $this->loadViews("transporters/transportexpenses", $this->global, $data, NULL);
            // }
        }
      
        function savetransportexpense(){
            if(!empty($fileExists)){
                $this->session->set_flashdata('error', 'In transit');
            }
            else{
                $this->load->model('transporter_model');
                // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
                // die;
                $expenseInfo = array();
              
                $id = trim(filter_input(INPUT_POST, 'transporter_id'));
                $transporterId = $id;
                
                $expenseInfo['transporter_id'] = $id;
                $container_no = trim(filter_input(INPUT_POST, 'container_nr'));
                
                //Get file info
                $this->load->model('document_model');
                $fileInfo = $this->document_model->getDocumentDataByCont($container_no);
                
                // if(empty($fileInfo)){
                    // echo'<pre>HTTPS response Message: '; print_r("No container existing...");
                    // $this->session->set_flashdata('error', 'Err... Container no. '.$container_no.' has not been lodged yet!');
                // }
                
                // echo'<pre>HTTPS response Message: '; print_r("Container no ".$container_no."exists!");
                $expenseInfo['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
                $expenseInfo['transport_date'] = trim(filter_input(INPUT_POST, 'transport_date'));
                $expenseInfo['vehicle_number'] = trim(filter_input(INPUT_POST, 'vehicle_number'));
                $expenseInfo['t812_no'] = trim(filter_input(INPUT_POST, 't812_no'));
                $expenseInfo['clearing_charge'] = trim(filter_input(INPUT_POST, 'clearing_charge'));
                $expenseInfo['extra_paid'] = trim(filter_input(INPUT_POST, 'extra_paid'));
                $expenseInfo['total'] = $expenseInfo['clearing_charge'] + $expenseInfo['extra_paid']; //agreed_amount
                // $expenseInfo['container_no'] = $container_no;
                $expenseInfo['container_no'] = $container_no;
                // $expenses = $expenseInfo['total'];
              
                $result = $this->transporter_model->addNewTransporterExpense($expenseInfo, $container_no, $transporterId, $expenseInfo['total']); //Update file table with transporter id $expenses
                // echo'<pre>'; print_r($result); die;
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'Transporter expense record successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Transporter expense record creation failed');
                }
                // echo'<pre> File info: '; print_r($fileInfo); 
                // die;
            }
            
            
            redirect('/transporter/transportexpenses?id='.$id);
        }
      
        function edittransporterexpense(){
            // if($this->isAdmin() == TRUE)
            // {
            //     $this->loadThis();
            // }
            // else
            // {
                $id = trim(filter_input(INPUT_GET, 'id'));
                $this->load->model('transporter_model');
                $data['profile'] = $this->transporter_model->getTransportExpense($id);
                $data['transporter_id'] = $data['profile'][0]->transporter_id;
                
                $data['curr_balance'] = $data['profile'][0]->balance;
                $data['file_no'] = $data['profile'][0]->file_no;
                
                //Get all payments from the db by user id
                $transporterPayments = $this->transporter_model->getTranporterPayments($data['transporter_id']);
                $total_paid = 0;
                foreach($transporterPayments as $t){
                    $total_paid += $t->amount;    
                }
                // echo'<pre>'; print_r($total_paid); die;
                
                $this->global['pageTitle'] = 'Amey Trading : Edit Transporter Expense';
    
                $this->loadViews("transporters/editexpense", $this->global, $data, NULL);
            // }
        }
        
    public function fileNoGenerator($digits = 6){
        $i = 0;
        $fileNo = "";
        while($i < $digits){
                //Generate a random unique number
                $fileNo .=mt_rand(0,9);
                $i++;
        }

        return $fileNo;
    }
        
        function editTransportExpense(){
            echo'<pre>'; print_r($_REQUEST); die;
            $id = trim(filter_input(INPUT_GET, 'id'));
            
            // last_advance
            $last_advance = trim(filter_input(INPUT_POST, 'last_advance'));
            $advance = trim(filter_input(INPUT_POST, 'advance'));
            
            if(!isset($last_advance) || empty($last_advance)){
                $last_advance = 0;
            }
            if(!isset($advance) || empty($advance)){
                $advance = 0;
            }
            
            $expenseInfo = [];
            $expenseInfo['advance'] = $last_advance + $advance;
            $expenseInfo['clearing_charge'] = trim(filter_input(INPUT_POST, 'clearing_charge')); //
            $expenseInfo['extra_paid'] = trim(filter_input(INPUT_POST, 'extra_paid')); //
            
            //get advance and calculate new balance
            $curr_bal = trim(filter_input(INPUT_POST, 'balance'));
            if(empty($curr_bal)) {$curr_bal = 0;}
            $expenseInfo['balance'] = $curr_bal - trim(filter_input(INPUT_POST, 'advance'));
            
            // Info for the payment tracking table per transporter
            $paymentInfo['transporter_id'] = trim(filter_input(INPUT_POST, 'transporter_id'));
            $paymentInfo['txn_type'] = 'ADVANCE';
            $paymentInfo['transaction_date'] = date('d/m/Y');
            $paymentInfo['ref'] = 'PYADV'.$this->fileNoGenerator(3).'/RF'.$this->fileNoGenerator(3);
            $paymentInfo['payment_for'] = trim(filter_input(INPUT_POST, 'vehicle_number'));
            $paymentInfo['amount'] = $advance;//$expenseInfo['advance'];
            $paymentInfo['created_at'] = date('d/m/Y');
            //echo'<pre>';print_r($paymentInfo); //die;
            
            $file_no = trim(filter_input(INPUT_POST, 'file_no')); //old_balance file_no
            // $totalBalance = $oldBalance + $paymentInfo['amount'];
            
            //save balance and advance paid
            $this->load->model('transporter_model');
            $result = $this->transporter_model->updateTransporterExpense($id, $expenseInfo, $paymentInfo, $expenseInfo['balance'], $file_no);
            // echo'<pre>';print_r($result); die;
            
            //Return server response
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'Transport Expense Record updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Transport Expense Record update failed');
            }
            
            redirect('/transporter/listing');
        }
      
        function deletetransporterexpense(){
            // echo'<pre>'; print_r($_REQUEST); die; 
            
            $id = trim(filter_input(INPUT_GET, 'id'));
            $transporter_id = trim(filter_input(INPUT_POST, 'transporter_id'));
        
            $this->load->model('transporter_model');
            $result = $this->transporter_model->removeTransporterExpense($id);
            // echo'<pre>'; print_r($result); die;
            
            if($result = 1)
            {
                $this->session->set_flashdata('success', 'Transport Expense Record deleted successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Transport Expense Record deletion failed');
            }
            
            // redirect('/transporter/listing');///transportexpenses?id=10
            redirect('/transporter/transportexpenses?id='.$transporter_id);///
      }
      
        function addtransporterexpense(){
            echo'<pre>'; print_r($_REQUEST); die;
        }
      
        function interchange(){
            // echo'<pre>'; print_r($_REQUEST); 
            // die;
            // interchange date fields zikuwe na calender picker.
            // noted.
            // interchange add driver name, client name,consignee, transporter, add charges field, idicate if it has deposit or not.
            // if($this->isAdmin() == TRUE)
            // if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
            // {
            //     $this->loadThis();
            // }
            // else
            // {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
                $data['searchText'] = $searchText;
                $this->load->library('pagination');
                
                $id = trim(filter_input(INPUT_GET, 'id'));
                $data['id'] = $id;
                $data['transporterExpensesRecords'] = $this->transporter_model->getTranporterExpenseList($id);
                //get shipping lines
                $this->load->model('shippingline_model');
                $data['shippinglines'] = $this->shippingline_model->getShippinglines();
                
                //echo'<pre>'; print_r($data['shippinglines']); die;
                
                $this->load->model('client_model');
                $data['consignees'] = $this->client_model->getClientList();
                $data['clients'] = $this->client_model->getClientList();
                $data['transporters'] = $this->transporter_model->getTranportersList();
                // echo'<pre>'; print_r($data); 
                // die;
                
                $data['trucks'] = $this->transporter_model->getTransporterTrucks($id);
                
                $this->global['pageTitle'] = 'Amey Trading : Interchange';
                $this->loadViews("transporters/interchange", $this->global, $data, NULL);
            // }  
        }
      
         function addNewInterchange(){
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
            // die;
            
            // [entry_date] => 02/26/2020
            // [driver] => DD
            // [charges] => 23400
            // [deposit] => NOT PAID
            // [container_no] => 09230293
            // [interchange_date] => 02/29/2020
            // [depot] => FORTUNE CONTAINER DEPOT
            // [container_size] => 40ft
            // [truck_no] => KAG 738G
            // [agent_shipping_line] => WEC LINES
            // [client_name] => WIILS
            $interchangeInfo = [];
            $interchangeInfo['entry_date'] = trim(filter_input(INPUT_POST, 'entry_date'));
            $interchangeInfo['charge'] = trim(filter_input(INPUT_POST, 'charges'));
            $interchangeInfo['container_no'] = trim(filter_input(INPUT_POST, 'container_no'));
            $interchangeInfo['interchange_date'] = trim(filter_input(INPUT_POST, 'interchange_date'));
            $interchangeInfo['depot'] = trim(filter_input(INPUT_POST, 'depot'));
            $interchangeInfo['container_size'] = trim(filter_input(INPUT_POST, 'container_size'));
            $interchangeInfo['truck_no'] = trim(filter_input(INPUT_POST, 'truck_no'));
            $interchangeInfo['driver_name'] = trim(filter_input(INPUT_POST, 'driver'));
            $interchangeInfo['agent_shipping_line'] = trim(filter_input(INPUT_POST, 'agent_shipping_line'));
            $interchangeInfo['deposit'] = trim(filter_input(INPUT_POST, 'deposit'));
            $interchangeInfo['client_name'] = trim(filter_input(INPUT_POST, 'client_name'));
            // echo'<pre>Interchange information HTTPS Request: '; print_r($interchangeInfo); 
            // die;
            
            $this->load->model('transporter_model');
            $result = $this->transporter_model->addNewInterchange($interchangeInfo);
            //echo'<pre>'; print_r($result); die;
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New Interchange Record created successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Interchange record creation failed');
            }
            
            redirect('/transporter/interchange');
        }
      
        function statement(){
            echo'<pre>'; print_r($_REQUEST); die;
        }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        // if($this->isAdmin() == TRUE || $userId == 1)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            if($userId == null)
            {
                redirect('clientListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Client';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        // }
    }
      
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array();
                
                if(empty($password))
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('userListing');
            }
        }
    }

   
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Amey Trading : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        
        $this->global['pageTitle'] = $active == "details" ? 'Amey Trading : My Profile' : 'Amey Trading : Change Password';
        $this->loadViews("profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');        
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            
            $userInfo = array('name'=>$name, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
            if($result == true)
            {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/'.$active);
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('profile/'.$active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }
}
