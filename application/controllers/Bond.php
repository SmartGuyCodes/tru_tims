<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bond Manager
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Bond extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bond_model');
        $this->isLoggedIn();   
    }
    
    //SECURITY: FOR TESTING ONLY
    // public function index()
    // {
    //     $this->global['pageTitle'] = 'Amey Trading : Registered Bonds';
        
    //     $this->loadViews("bonds/listing", $this->global, NULL , NULL);
    // }
    
    // Bonds Module
    public function listing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
            // die;
            $data['bonds'] = $this->bond_model->getAllBonds();
            // echo'<pre>HTTPS Bonds request: '; print_r($data['bonds']); 
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Registered Bonds';
            $this->loadViews("bonds/bondslist", $this->global, $data , NULL);
        }
    }
    
    function statementpdf()
    {
        echo'<pre>HTTPS request: '; print_r($_REQUEST); 
        die;
        
        ob_start();
        $file_no = trim(filter_input(INPUT_GET, 'file_no'));
        $container_no = trim(filter_input(INPUT_GET, 'container_no'));
        // echo'<pre>File no: '; print_r($file_no); 
        // die;
        
        //Get file info
        $this->load->model('document_model');
        $fileInfo = $this->document_model->getDocumentData($file_no);
        // echo'<pre>File info: '; print_r($fileInfo); 
        // die;
        
        //Get payments for this file
        // $filePayments = $this->document_model->getFilePayments($file_no);
        $filePayments = $this->document_model->getFilePaymentsByCont($container_no);
        // echo'<pre>File payments: '; print_r($filePayments); 
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
        $pdf->Write(25, 'Amey Trading Ltd.', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;" text-align: center;><strong>'.$fileInfo[0]->client_id.' Clearing Charges</strong></h3>
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
                     <th>Consignee</th>
                     <th>Container No.</th>
                     <th align="right">Clearing Charges</th>
                     <th align="right">Extra Paid</th>
                     <th align="right">Total</th>
                  </tr>
              </thead>
              <tbody>'; 
              $totalClearingCharges = 0;
              $totalExtraPaid = 0;
              $totalAgreedAmount = 0;
              foreach($fileInfo as $c){
                    $totalClearingCharges = $totalClearingCharges + ($c->clearing_charges);
                    $totalExtraPaid += $c->extra_paid;
                    $totalAgreedAmount = $totalAgreedAmount + ($c->clearing_charges + $c->extra_paid);
                    $tbl .= '
                        <tr nobr="true">
                            <td>'.$c->date_received.'</td> 
                            <td>'.$c->consignee_id.'</td> 
                            <td>'.$c->container_nr.' </td>
                            <td align="right">'.number_format(($c->clearing_charges), 2).'</td> 
                            <td align="right">'.number_format($c->extra_paid, 2).'</td> 
                            <td align="right">'.number_format(($c->clearing_charges + $c->extra_paid),2).'</td> 
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Total Clearing Charges: </td> 
                        <td align="right">'.number_format($totalClearingCharges, 2).'</td> 
                        <td></td>
                        <td></td> 
                    </tr>
                    <tr nobr="true">
                        <td></td> 
                        <td></td>
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Total Extra Paid: </td> 
                        <td></td>
                        <td align="right">'.number_format($totalExtraPaid, 2).'</td> 
                        <td></td> 
                    </tr>
                    <tr nobr="true">
                        <td></td> 
                        <td></td>
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Total Agreed Amount: </td> 
                        <td></td>
                        <td></td>
                        <td align="right">'.number_format($totalAgreedAmount, 2).'</td>  
                    </tr>
                    <br />
                    <br />
                </tbody>
            </table>
        ';
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%; text-align: center;"><strong>'.$fileInfo[0]->client_id.' Cash In</strong></h3>
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
              
              foreach($filePayments as $pay){
                    $amountPaid = $amountPaid + $pay->amount;
                    $tbl .= '
                        <tr nobr="true">
                            <td>'.$pay->transaction_date.'</td> 
                            <td width="15%">'.$pay->txn_type.'</td>
                            <td>'.$pay->payment_for.'</td> 
                            <td width="15%">'.$pay->ref.' </td>
                            <td></td>
                            <td></td> 
                            <td></td> 
                            <td align="right">'.number_format($pay->amount,2).'</td> 
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
                        <td align="right" style="background-color:#3c8dbc;color:#fff;">Total Paid: </td> 
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
                        <td align="right">'.number_format(($totalAgreedAmount - $amountPaid), 2).'</td> 
                    </tr>
                </tbody>
            </table>
        ';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // Clean any content of the output buffer
        ob_end_clean();
        $pdf->Output($fileInfo[0]->client_id.'clientstmt'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    
    function statement()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
            // die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            //$data['documents'] = $this->document_model->getDocumentsList();
            $data['docs'] = $this->document_model->getDocs();
            
            $data['file_no'] = trim(filter_input(INPUT_GET, 'file_no'));
            $data['container_nr'] = trim(filter_input(INPUT_GET, 'container_nr'));
            // echo'<pre>'; print_r($data); 
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Lodged Documents';
            $this->loadViews("documents/statement", $this->global, $data, NULL);
        }
    }
    
    function deletebond()
    {
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        // die;
        
        //Get id
        $ref = trim(filter_input(INPUT_GET, 'ref'));
        
        //delete by id 
        $this->load->model('bond_model');
        $result = $this->bond_model->deleteBond($ref);
        // echo'<pre>'; print_r($result); 
        // die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Bond Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Bond deletion failed');
        }
        
        redirect('/bond/listing');
    }
    
    private function bondSeriesNoGenerator($digits = 6)
    {
        $i = 0;
        $seriesNo = "";
        while($i < $digits){
            //Generate a random unique number
            $seriesNo .=mt_rand(0,9);
            $i++;
        }

        return $seriesNo;
    }
    
    function add()
    {
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('document_model');
            $this->load->model('client_model');
            $this->load->model('shippingline_model');
            
            $data['roles'] = '';//$this->client_model->getUserRoles();
            $data['fileNo'] = $this->fileNoGenerator($digits = 6);
            $data['fileNo'] = 'FL0'.$data['fileNo'];
            
            $data['clients'] = $this->client_model->getClientList();
            $data['shippinglines'] = $this->shippingline_model->getShippinglines();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Add New File';

            $this->loadViews("documents/addNew", $this->global, $data, NULL);
        }
    }
    
    function addNewBond()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            // Bonds Module
            echo'<pre>'; print_r($_REQUEST);
            // die;
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
                // echo'<pre>Document HTTPS Request: '; print_r($_REQUEST);
                // die;
                $bondInfo = array();
                $bondInfo['bond_ref'] = 'B'.$this->bondSeriesNoGenerator(3).'D';
                $bondInfo['bond_name'] = trim(filter_input(INPUT_POST, 'bond_name'));
                $bondInfo['bond_value'] = trim(filter_input(INPUT_POST, 'bond_value'));
                // echo'<pre>Bond Info HTTPS Request: '; print_r($bondInfo);
                // die;
                
                $this->load->model('document_model');
                
                //check if container is already lodged
                $bondExistance = $this->bond_model->bondExists($bondInfo['bond_ref']);
                $couunter = count($bondExistance);
                // echo'<pre>Bond Exists?:: '; print_r($couunter);
                // die;
                
                if($couunter >= 1){
                    $this->session->set_flashdata('error', 'Bond ref nr:'.$bondInfo['bond_ref'].' duplicate. Contact admin ASAP!');
                }
                else if($couunter == 0){
                    $result = $this->bond_model->addNewBond($bondInfo);
                    
                    if($result > 0)
                    {
                        $this->session->set_flashdata('success', 'Bond series no. '.$bondInfo['bond_ref'].' has been registered successfully');
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Bond registration failed');
                    }
                }
                
                redirect('/bond/listing');
            //}
        }
    }
    
    function editBond()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            // Bonds Module
            echo'<pre>'; print_r($_REQUEST);
            die;
            
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
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Amey Trading : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
    
    function bondExists($email)
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
    
    // Whenever abond is released the value increases. 
    function releasebond(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
            // die;
            $bond_ref = trim(filter_input(INPUT_GET, 'bond_ref'));
            $data['attachedbonds'] = $this->bond_model->getAllAttachedBondsByRef($bond_ref);
            // echo'<pre>Attached Bonds HTTPS request: '; print_r($data['attachedbonds']); 
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Attached Bonds';
            $this->loadViews("bonds/attachedbonds", $this->global, $data , NULL);
        }
    }
    function releaseaxn(){
        //bond_ref	bond_name	container_no	consignment	date_attached	attached	released	date_released	value	total	created_at	updated_at
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        // die;
        
        //Get bond profile
        $bond_id = trim(filter_input(INPUT_GET, 'id'));
        $profileInfo = $this->bond_model->getAttachedBondProfile($bond_id);
        $bond_value = $profileInfo[0]->value;
        $bond_ref = $profileInfo[0]->bond_ref;
        // echo'<pre>Bond profile info HTTPS Request: '; print_r($profileInfo);
        // echo'<pre>Bond amount HTTPS Request: '; print_r($bond_value);
        // echo'<pre>Bond ref info HTTPS Request: '; print_r($bond_ref);
        // die;
        
        //On release get the bond value and add it to the parent bond value
        //update bond profile by id
        $result = $this->bond_model->releaseBond($bond_id, $bond_ref, $bond_value);
        // echo'<pre>HTTPS Response: '; print_r($result);
        // die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Bond released successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Bond release failed');
        }
        
        redirect('/bond/releasebond?bond_ref='.$bond_ref); ///bond/releasebond?bond_ref=B166D
    }
    function releasedbonds(){
        echo'<pre> HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    
    //Alafu the second form would be to select the container no. And attach bond using value of the container so itakuwa na date, container, 
    //consignment , from, to, bond value.
    function attachbond()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            // echo'<pre> HTTPS Request: '; print_r($_REQUEST);
            // die;
            
            $bond_ref = trim(filter_input(INPUT_GET, 'bond_ref'));
            
            //Get containerInfo 
            // $this->load->model('document_model');
            // $data['container_no'] = $container_no;
            // $containerFile = $this->document_model->containerExists($container_no);
            // $data['container_no'] = $containerFile[0]->container_nr;
            // $data['consignment'] = $containerFile[0]->consignement;
            
            //Get Bonds
            $this->load->model('bond_model');
            $data['bonds'] = $this->bond_model->getAllBonds();
            // echo'<pre>Bonds HTTPS info: '; print_r($data); 
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Attach Bond';
            $this->loadViews("bonds/attachbond", $this->global, $data, NULL);
        }  
    }
    function addattachment(){
        echo'<pre> HTTPS Request: '; print_r($_REQUEST);
        // die;
        
        $bondInfo['bond_name'] = trim(filter_input(INPUT_POST, 'bond_name'));
        $bondInfo['date_attached'] = date("Y-m-d H:i:s");
        $bondInfo['attached '] = 1;
        $bondInfo['released '] = 0;
        $bondInfo['container_no'] = trim(filter_input(INPUT_POST, 'container_nr'));
        $bondInfo['consignment'] = trim(filter_input(INPUT_POST, 'consignment'));
        $bondInfo['value'] = trim(filter_input(INPUT_POST, 'charges'));
        $bondInfo['bonded_from'] = trim(filter_input(INPUT_POST, 'from'));
        $bondInfo['bonded_to'] = trim(filter_input(INPUT_POST, 'to'));
        
        echo'<pre> Bond information HTTPS Request: '; print_r($bondInfo);
        // die;
        //Get bond by name and get the ref and total amount
        $parentBondInfo = $this->bond_model->getBondProfileByName($bondInfo['bond_name']);
        $parentBondRef = $parentBondInfo[0]->bond_ref;
        $bondInfo['bond_ref'] = $parentBondInfo[0]->bond_ref;
        $parentBondValue = $parentBondInfo[0]->bond_value;
        
        //When abond is attached the value decreases.
        $newParentBondValue = $parentBondValue - $bondInfo['value'];
        
        echo'<pre> Parent Bond information HTTPS Request: '; print_r($parentBondInfo);
        echo'<pre> Parent Bond Ref HTTPS Request: '; print_r($parentBondRef);
        echo'<pre> Parent Bond Old Value HTTPS Request: '; print_r($parentBondValue);
        echo'<pre> Child Bond Attached Value HTTPS Request: '; print_r($bondInfo['value']);
        echo'<pre> Parent Bond New Value HTTPS Request: '; print_r($newParentBondValue);
        
        // $documentInfo['is_bonded'] = 1;
        // $documentInfo['date_bond_attached'] = $bondInfo['date_attached'];
        // $documentInfo['updated_at'] = date("Y-m-d H:i:s");
        // $documentInfo['bond_value'] = $bondInfo['bond_name'];
        
        //save this info into the db, then process response for user view: Update the bonded status
        $result = $this->bond_model->insertAttachment($bondInfo, $parentBondRef, $newParentBondValue);//insertAttachment($bondInfo, $bondRef, $newParentBondValue, $documentInfo);
        // echo'<pre> Attachment HTTPS Request: '; print_r($result);
        
        // die;
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Bond attached successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Bond attachment failed');
        }
        
        redirect('/bond/listing');
    }
    function attachedbonds(){
        echo'<pre> HTTPS Request: '; print_r($_REQUEST);
        die;
    }
}