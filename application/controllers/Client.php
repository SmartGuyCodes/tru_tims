<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Client extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Amey Trading : Client Listing ';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
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
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->client_model->clientListingCount($searchText);

            $returns = $this->paginationCompress ( "clientListing/", $count, 10 );
            //            
            $data['clientRecords'] = $this->client_model->getClientList();
            
            $this->global['pageTitle'] = 'Amey Trading : Client Listing ';
            $this->loadViews("clients/index", $this->global, $data, NULL);
        // }
    }
    
    private function paymentNoGenerator($digits = 3){
        $i = 0;
        $fileNo = "";
        while($i < $digits){
                //Generate a random unique number
                $fileNo .=mt_rand(0,9);
                $i++;
        }

        return $fileNo;
    }
    
    function addCharges(){
        // if($this->isAdmin() == TRUE)
        // if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $data['client_id'] = trim(filter_input(INPUT_GET, 'name'));
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
            // die;
            $this->load->model('document_model');
            $data['documents'] = $this->document_model->getDocs();
            // echo'<pre>HTTPS Request: '; print_r($data);
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Client Charges';

            $this->loadViews("clients/addCharge", $this->global, $data, NULL);
        // }
    }
    
    function updateClientCharges(){
        //Get all the inputs
        $client_id = trim(filter_input(INPUT_GET, 'client_id'));
        $clearing_charges = trim(filter_input(INPUT_POST, 'clearing_charge'));
        $extra_paid =  trim(filter_input(INPUT_POST, 'extra_charges'));
        $total = $clearing_charges + $extra_paid;
        $updated_at = date('d-m-Y H:m:s');
        // echo'<pre> HTTPS POST Request: '; print_r($_REQUEST);
        
        //Check whether the container exists, if yes, update charges
        $container_nr = trim(filter_input(INPUT_POST, 'container_no'));
        $this->load->model('document_model');
        $exists = $this->document_model->containerExists($container_nr);
        
        if(!empty($exists)){
            // echo'<pre> HTTPS POST Container existance: '; print_r("Container exists");
            $result = $this->client_model->updateClientCharges($container_nr, $clearing_charges, $extra_paid, $total); //add $balancePayable
            // echo'<pre>'; print_r($result); 
            // die;
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New Charges for container no. '.$container_nr.' has been lodged successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Charges records update failed');
            }
        }
        else{
            //else add new container
            // echo'<pre> HTTPS POST Container existance: '; print_r("Container does not exist!");
            $documentInfo = array();
            $documentInfo['file_no'] = 'FL'.$this->fileNoGenerator(7);
            $documentInfo['date_received'] = date('d-m-Y H:m:s');
            $documentInfo['bill_of_landing'] = 'N/A';
            $documentInfo['vessel'] = 'N/A';
            $documentInfo['manifest_no'] = 'N/A';
            $documentInfo['eta_ata'] = 'N/A';
            $documentInfo['client_id'] = $client_id;
            $documentInfo['consignee_id'] = 'N/A';
            $documentInfo['shipping_line'] = 'N/A';
            $documentInfo['cargo_type'] = 'N/A';
            $documentInfo['collection_status'] = 'N/A';
            $documentInfo['container_size'] = 'N/A';
            $documentInfo['consignement'] = 'N/A';
            $documentInfo['idf_no'] = 'N/A';
            
            $documentInfo['clearing_charges'] = $clearing_charges;
            $documentInfo['extra_paid'] = $extra_paid;
            if(empty($documentInfo['clearing_charges'])){
                $documentInfo['clearing_charges'] = 0;
            }
            if(empty($documentInfo['extra_paid'])){
                $documentInfo['extra_paid'] = 0;
            }
            
            $documentInfo['total'] = $documentInfo['clearing_charges'] + $documentInfo['extra_paid'];
            $documentInfo['t812_nr'] = 'N/A';
            $documentInfo['container_nr'] = $container_nr;
            $documentInfo['amount_agreed'] = $documentInfo['clearing_charges'] + $documentInfo['extra_paid'];//
            $documentInfo['expenses'] = 0; //
            $documentInfo['amount_paid'] = 0; //amount_paid
            
            $containerInfo['container_no '] = $container_nr;
            $containerInfo['file_no '] = $documentInfo['file_no'];
            // echo'<pre>Document Info HTTPS Request: '; print_r($documentInfo);
            // die;
            
            $this->load->model('document_model');
            $result = $this->document_model->addNewDocument($documentInfo, $containerInfo);
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New Charges for container no. '.$documentInfo['container_nr'].' has been lodged successfully. Add the consignment.');
                redirect('/client/addConsignment?container_nr='.$container_nr);
            }
            else
            {
                $this->session->set_flashdata('error', 'Document charges update failed');
            }
        }
        
        redirect('/client/listing');
    }
    
    function addConsignment(){
        //echo'<pre> HTTPS REQUEST: '; print_r($_REQUEST);
        //die;
        $data['clientRecords'] = $this->client_model->getClientList();
        // echo'<pre> HTTPS REQUEST: '; print_r($data['clientRecords']);
        // die;
        $data['container_nr'] = trim(filter_input(INPUT_GET, 'container_nr'));
        $this->global['pageTitle'] = 'Amey Trading : Edit Client Charges';

        $this->loadViews("clients/addConsignment", $this->global, $data, NULL);
    }
    
    function updateContainerConsignment(){
        // echo'<pre> HTTPS REQUEST: '; print_r($_REQUEST);
        // die;
        
        $container_nr = trim(filter_input(INPUT_POST, 'container_nr'));
        $consignment = trim(filter_input(INPUT_POST, 'consignment'));
        $consignee = trim(filter_input(INPUT_POST, 'consignee'));
        
        //
        $this->load->model('document_model');
        $result = $this->document_model->updateContainerConsignment($container_nr, $consignee, $consignment);
        // echo'<pre> HTTPS server response: '; print_r($result);
        // die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'New Charges and consignment for container no. '.$container_nr.' have been lodged successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Document charges update failed');
        }
        
        redirect('/client/listing');
    }
    
    function fileNoGenerator($digits = 6){
        $i = 0;
        $fileNo = "";
        while($i < $digits){
                //Generate a random unique number
                $fileNo .=mt_rand(0,9);
                $i++;
        }

        return $fileNo;
    }
    
    function payadvance(){
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        $receiptInfo = [];
        $receiptInfo['receipt_no'] = trim(filter_input(INPUT_POST, "receipt_no"));
        $receiptInfo['transaction_date'] = trim(filter_input(INPUT_POST, "transaction_date"));
        $receiptInfo['amount'] = trim(filter_input(INPUT_POST, "amount"));
        $receiptInfo['payment_mode'] = trim(filter_input(INPUT_POST, "payment_mode"));
        $receiptInfo['user_type'] = trim(filter_input(INPUT_POST, "user_type"));
        $receiptInfo['transaction_details'] = trim(filter_input(INPUT_POST, "transaction_details"));
        $receiptInfo['file_no'] = trim(filter_input(INPUT_POST, "file_no"));
        // echo'<pre>'; print_r($_REQUEST); 
        
        //Get documents
        $file_no = $receiptInfo['file_no'];
        $this->load->model('document_model');
        $file = $this->document_model->getDocumentData($file_no); 
        $receiptInfo['client'] = $file[0]->client_id; //client_id
        $clientName = $receiptInfo['client'];
        
        //Get client info
        $this->load->model('client_model');
        $clientInfo = $this->client_model->getClientDetale($clientName);
        // echo'<pre>'; print_r($clientInfo);
        
        // echo'<pre> File info: '; print_r($file); die;
        $last_paid_amount = $file[0]->amount_paid;
        if(!isset($file[0]->amount_paid) || empty($file[0]->amount_paid)){$last_paid_amount = 0;}
        
        $paymentInfo = [];
        // Info for the payment tracking table per transporter
        $paymentInfo['client_id'] = $clientInfo->id;
        $paymentInfo['txn_type'] = 'CLIENT_ADVANCE';
        $paymentInfo['transaction_date'] = date('d/m/Y');
        $paymentInfo['ref'] = $receiptInfo['transaction_details']; //$payInfo['ref'];//'PY'.$this->fileNoGenerator(3).'/RF'.$this->fileNoGenerator(3);
        $paymentInfo['payment_for'] = $receiptInfo['file_no'];
        $paymentInfo['amount'] = $receiptInfo['amount'];//$expenseInfo['advance'];
        $paymentInfo['created_at'] = date("Y-m-d H:i:s");
        // echo'<pre>Date created: '; print_r($paymentInfo['created_at']); 
        // echo'<pre>Payment Info: '; print_r($paymentInfo); 
        // die;
        
        $amountPaid = $last_paid_amount + $receiptInfo['amount'];
        $agreedCharges = $file[0]->clearing_charges + $file[0]->extra_paid;
        $newBalance = $agreedCharges - $amountPaid;
        
        //for clearing statement it is balance payable = clearing fee + extra charge - any payments received from client.
        //GET balance payable from the documents filed. Get document by file id 
        $balancePayable = $agreedCharges - $receiptInfo['amount'];
        // echo'<pre>Last Paid amount: '; print_r($last_paid_amount); 
        // echo'<pre>Total Paid Amount: '; print_r($amountPaid); 
        // echo'<pre>Agreed Charges: '; print_r($agreedCharges); 
        // echo'<pre>Balance Payable: '; print_r($balancePayable);
        // die;
        
        $this->load->model('payment_model');
        $result = $this->payment_model->insertNewReceipt($receiptInfo, $amountPaid, $balancePayable, $file_no, $paymentInfo); //add $balancePayable
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Payment record created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Payment record creation failed');
        }
        
        redirect('/client/listing');
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('client_model');
            $data['roles'] = '';//$this->client_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Amey Trading : Add New Client';

            $this->loadViews("clients/addNew", $this->global, $data, NULL);
        // }
    }
    
    function statementpdf(){
        ob_start();
        $id = trim(filter_input(INPUT_GET, 'id'));
        // echo'<pre>HTTPS request: '; print_r($_REQUEST); 
        // die;
        
        $this->load->model('client_model');
        $this->load->model('reports_model'); //tbl_transport_expense
        $this->load->model('document_model');
        
        $clientInfo = $this->client_model->getClientInfo($id); //Get client info details
        $clientStmt = $this->document_model->getClientDocuments($clientInfo->client_name); //get list of all documents by client name and id
        $clientPayments = $this->client_model->getClientPayments($id); //Get all client payments received by client name/id
        // echo'<pre>Client profile: '; print_r($clientInfo);
        // echo'<pre>Statement: '; print_r($clientStmt);
        // echo'<pre>Payments: '; print_r($clientPayments); 
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
            <h3 style="margin-left:33%;" text-align: center;><strong>'.$clientInfo->client_name.' Clearing Charges</strong></h3>
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
              foreach($clientStmt as $c){
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
            <h3 style="margin-left:33%; text-align: center;"><strong>'.$clientInfo->client_name.' Cash In</strong></h3>
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
                     <th>Container no.</th>
                     <th></th>
                     <th></th>
                     <th></th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
              
              foreach($clientPayments as $pay){
                    $amountPaid = $amountPaid + $pay->amount;
                    $tbl .= '
                        <tr nobr="true">
                            <td>'.$pay->transaction_date.'</td> 
                            <td width="15%">'.$pay->txn_type.'</td>
                            <td>'.$pay->payment_for.'</td> 
                            <td width="15%">'.$pay->ref.' </td>
                            <td>'.$pay->payment_for.' </td>
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
                        <td ></td>
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
        $pdf->Output($clientInfo->client_name.'clientstmt'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function statement(){
        $id = trim(filter_input(INPUT_GET, 'id'));
        // echo'<pre>'; print_r($id); 
        // die;
        
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->client_model->clientListingCount($searchText);

            $returns = $this->paginationCompress ( "clientListing/", $count, 10 );
            //            
            $data['clientRecords'] = $this->client_model->getClientList();
            
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            
             //Get voucher no. auto generator
            $data['receiptNo'] = 'RT'.$this->paymentNoGenerator($digits = 3);
            
            //Get documents
            $this->load->model('document_model');
            $data['files'] = $this->document_model->getDocs();
            
            $this->global['pageTitle'] = 'Amey Trading : Client Statement ';
            $this->loadViews("clients/statement", $this->global, $data, NULL);
        }
    }
    
    function edit(){
        //get user id
        $userId = $this->input->get("id");
        
        //Get user by id
        $this->load->model('client_model');
        $data = array();
        $data['clientInfo'] = $this->client_model->getClientInfo($userId); 
        
        //echo'<pre>'; print_r($data['clientInfo']); die;
        $this->global['pageTitle'] = 'Amey Trading : Edit Client info';

        $this->loadViews("clients/edit", $this->global, $data, NULL);
    }
    
    function editClient(){
        $id = $this->input->post('id');
        $clientInfo['client_name'] = $this->input->post('contact_name');
        $clientInfo['contact_person'] = $this->input->post('contact_person');
        $clientInfo['physical_address'] = $this->input->post('address');
        $clientInfo['reg_date'] = $this->input->post('reg_date');
        $clientInfo['tel_no'] = $this->input->post('tel_no');
        $clientInfo['email'] = $this->input->post('email');
        $clientInfo['ac_type'] = $this->input->post('client_Type');
        //$clientInfo['id'] = $this->input->post('id');
        //echo'<pre>'; print_r($clientInfo); die;
        
        //Update info by user id
        $this->load->model('client_model');
        $update = $this->client_model->editClient($clientInfo, $id);
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Client record updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'User updating failed');
        }
                
        redirect('/client/edit?id='.$id);
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
    function addNewClient()
    {
        // echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
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
                $clientInfo = array();
                //id 	client_name 	contact_person 	address 	reg_date 	pin_no 	vat_no 	physical_address 	tel_no 	email 	ac_type 	created_at 	updated_at 
                $clientInfo['client_name'] = trim(filter_input(INPUT_POST, 'contact_name'));
                $clientInfo['contact_person'] = trim(filter_input(INPUT_POST, 'contact_person'));
                $clientInfo['address'] = trim(filter_input(INPUT_POST, 'address'));
                $clientInfo['reg_date'] = trim(filter_input(INPUT_POST, 'reg_date'));
                $clientInfo['pin_no'] = trim(filter_input(INPUT_POST, 'pin'));
                $clientInfo['vat_no'] = trim(filter_input(INPUT_POST, 'vat'));
                $clientInfo['tel_no'] = trim(filter_input(INPUT_POST, 'tel'));
                $clientInfo['email'] = trim(filter_input(INPUT_POST, 'email'));
                $clientInfo['physical_address'] = trim(filter_input(INPUT_POST, 'physical_address'));
                $clientInfo['ac_type'] = trim(filter_input(INPUT_POST, 'ac_type'));
                
                $this->load->model('client_model');
                $result = $this->client_model->addNewClient($clientInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Client created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('/client/add');
            //}
        }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if($this->isAdmin() == TRUE || $userId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('clientListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Client';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
      
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
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
                
                redirect('/client/edit?id='.$userId);
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
         $id = trim(filter_input(INPUT_POST, 'delid'));
        
        $this->load->model('client_model');
        $result = $this->client_model->deleteClient($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Client Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Client deletion failed');
        }
        
        redirect('/client/listing');
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
