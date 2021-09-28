<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Documents Manager
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Documents extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Trulance : Registered Clients & Consignees ';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
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
    
    function receiveadvance(){
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        $data['searchText'] = $searchText;
        $this->load->library('pagination');
        
        $id = trim(filter_input(INPUT_GET, 'id'));
        $data['id'] = $id;
        $this->load->model('transporter_model');
        $data['transporterExpensesRecords'] = $this->transporter_model->getTranporterExpenseList($id);
        //get shipping lines
        $this->load->model('shippingline_model');
        $data['shippinglines'] = $this->shippingline_model->getShippinglines();
        
        //get file ifo by file id
        $id = trim(filter_input(INPUT_GET, 'id'));
        $fileInfo = $this->document_model->getDocumentInfo($id);
        // echo'<pre>File info: '; print_r($fileInfo); 
        // die;
        
        $data['file_no'] = $fileInfo[0]->file_no;
        $data['container_nr'] = $fileInfo[0]->container_nr;
        $data['client_id'] = $fileInfo[0]->client_id;
        
        //Get voucher no. auto generator
        $data['receiptNo'] = 'RT'.$this->paymentNoGenerator($digits = 3);
        
        //Get documents
        $this->load->model('document_model');
        $data['files'] = $this->document_model->getDocs();
        
        $this->load->model('client_model');
        $data['consignees'] = $this->client_model->getClientList();
        
        $data['clients'] = $this->client_model->getClientList();
        $data['id'] = $id;
        
        //get all advance payments by for this file
        $data['payments'] = $this->document_model->getClientAdvancePays($data['container_nr']);
        // echo'<pre>'; print_r($data['payments']); 
        // die;
        
        $data['trucks'] = $this->transporter_model->getTransporterTrucks($id);
        
        $this->global['pageTitle'] = 'Trulance : Payment Advance';
        $this->loadViews("documents/payadvance", $this->global, $data, NULL);
    }
    
    public function seriesGenerator($digits = 6){
        $i = 0;
        $invNo = "";
        while($i < $digits){
                //Generate a random unique number
                $invNo .=mt_rand(0,9);
                $i++;
        }

        return $invNo;
    }
    
    function payadvance(){
        echo'<pre> Payment HTTPS Request: '; print_r($_REQUEST); 
        // die;
        
        $receiptInfo = [];
        $id = trim(filter_input(INPUT_GET, 'id'));
        $receiptInfo['receipt_no'] = 'RT'.$this->seriesGenerator(3);
        $receiptInfo['client'] = trim(filter_input(INPUT_POST, "client"));
        $receiptInfo['transaction_date'] = trim(filter_input(INPUT_POST, "transaction_date"));
        $receiptInfo['file_no'] = trim(filter_input(INPUT_POST, "file_no")); //
        $receiptInfo['container_no'] = trim(filter_input(INPUT_POST, "container_nr")); //container_nr
        $receiptInfo['amount'] = trim(filter_input(INPUT_POST, "amount"));
        $receiptInfo['payment_mode'] = trim(filter_input(INPUT_POST, "payment_mode"));
        $receiptInfo['user_type'] = trim(filter_input(INPUT_POST, "user_type"));
        $receiptInfo['transaction_details'] = trim(filter_input(INPUT_POST, "transaction_details"));
        
        //Get documents
        $file_no = $receiptInfo['file_no'];
        $this->load->model('document_model');
        $file = $this->document_model->getDocumentData($file_no); 
        echo'<pre> Payment Document info HTTPS Request: '; print_r($file); 
        // die;
        
        //Get client info
        $clientName = trim(filter_input(INPUT_POST, "client"));
        $this->load->model('client_model');
        $clientInfo = $this->client_model->getClientDetale($clientName);
        echo'<pre> Payment Client info HTTPS Request: '; print_r($clientInfo); 
        // die;
        
        // echo'<pre> File info: '; print_r($file); die;
        $last_paid_amount = $file[0]->amount_paid;
        if(!isset($file[0]->amount_paid) || empty($file[0]->amount_paid)){$last_paid_amount = 0;}
        
        $paymentInfo = [];
        
        $paymentInfo['client_id'] = $clientInfo->id;
        $paymentInfo['txn_type'] = 'CLIENT_ADVANCE';
        $paymentInfo['transaction_date'] = date("Y-m-d H:i:s");
        $paymentInfo['ref'] = $receiptInfo['transaction_details']; 
        $paymentInfo['payment_for'] = trim(filter_input(INPUT_POST, "container_nr"));
        $paymentInfo['paid_by'] = trim(filter_input(INPUT_POST, "client"));
        $paymentInfo['amount'] = $receiptInfo['amount'];
        $paymentInfo['created_at'] = date("Y-m-d H:i:s");
        echo'<pre> Payment info HTTPS Request: '; print_r($paymentInfo); 
        // die;
        
        $amountPaid = $last_paid_amount + $receiptInfo['amount'];
        $agreedCharges = $file[0]->clearing_charges + $file[0]->extra_paid;
        $newBalance = $agreedCharges - $amountPaid;
        
        $balancePayable = $agreedCharges - $receiptInfo['amount'];
        
        $this->load->model('payment_model');
        $result = $this->payment_model->insertNewReceipt($receiptInfo, $amountPaid, $balancePayable, $receiptInfo['container_no'], $paymentInfo);
        echo'<pre> Payment HTTPS Response: '; print_r($result); 
        // die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Advance Payment submited successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Payment record creation failed');
        }
        
        redirect('/documents/receiveadvance?id='.$id);
    }
    
    function editadvance(){
        // echo'<pre> Edit display(get the add form and modify-> HTTPS request): '; print_r($_REQUEST); 
        //die;
        echo'<pre> HTTPS Request: '; print_r($_REQUEST);
        // die;
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            // echo'<pre>HTTPS request: '; print_r($_REQUEST); 
            
            $pay_id = trim(filter_input(INPUT_GET, 'id'));
            $doc_id = trim(filter_input(INPUT_GET, 'document_id'));
            
            $data['id'] = $pay_id;
            echo'<pre>Pay ID: '; print_r($pay_id); 
            echo'<pre>Doc ID: '; print_r($doc_id); 
            // die;
            
            //Get document information by id
            $document_info = $this->document_model->getDocumentInfo($doc_id);
            echo'<pre>Doc info: '; print_r($document_info); 
            // die;
            
            //Get document information by id
            $this->load->model('payment_model');
            $payment_info = $this->payment_model->getClientDepositPayment($pay_id);
            echo'<pre>Paymentt info: '; print_r($payment_info); 
            // die;
            
            //Get documents
            $this->load->model('document_model');
            // $data['doc_id'] = $id;
            
            //get all advance payments by for this file
            $data['payments'] = $this->document_model->getClientAdvancePays($document_info[0]->file_no);
            echo'<pre>'; print_r($data['payments']); 
            die;
            
            $this->global['pageTitle'] = 'Trulance : Edit Advance';
            $this->loadViews("documents/editadvance", $this->global, $data, NULL);
        }  
    }
    
    function updateadvance(){
        echo'<pre> HTTPS request: '; print_r($_REQUEST); 
        die;
        
        // $amount_paid = $document_info[0]->amount_paid;
        // $balance = $document_info[0]->balance;
        // echo'<pre>Old amount paid: '; print_r($amount_paid); 
        // echo'<pre>Old balance: '; print_r($balance); 
        
        // $amount_to_deduct = $payment_info[0]->amount;
        // echo'<pre>Amount to deduct: '; print_r($amount_to_deduct); 
        
        // $new_amount_paid = $amount_paid - $amount_to_deduct;
        // $new_balance = $balance - $amount_to_deduct;
        // echo'<pre>New amount paid: '; print_r($new_amount_paid); 
        // echo'<pre>New balance: '; print_r($new_balance); 
        // die;
    }
    
    function deleteadvance(){
        echo'<pre>HTTPS request: '; print_r($_REQUEST); 
        
        $pay_id = trim(filter_input(INPUT_GET, 'id'));
        $doc_id = trim(filter_input(INPUT_GET, 'document_id'));
        echo'<pre>Pay ID: '; print_r($pay_id); 
        echo'<pre>Doc ID: '; print_r($doc_id); 
        
        //Get document information by id
        $document_info = $this->document_model->getDocumentInfo($doc_id);
        echo'<pre>Doc info: '; print_r($document_info); 
        
        //Get document information by id
        $this->load->model('payment_model');
        $payment_info = $this->payment_model->getClientDepositPayment($pay_id);
        echo'<pre>Paymentt info: '; print_r($payment_info); 
        
        $amount_paid = $document_info[0]->amount_paid;
        $balance = $document_info[0]->balance;
        echo'<pre>Old amount paid: '; print_r($amount_paid); 
        echo'<pre>Old balance: '; print_r($balance); 
        
        $amount_to_deduct = $payment_info[0]->amount;
        echo'<pre>Amount to deduct: '; print_r($amount_to_deduct); 
        
        $new_amount_paid = $amount_paid - $amount_to_deduct;
        $new_balance = $balance - $amount_to_deduct;
        echo'<pre>New amount paid: '; print_r($new_amount_paid); 
        echo'<pre>New balance: '; print_r($new_balance); 
        
        //Now lets parse the data to the model for execution
        $result = $this->payment_model->deleteDepositPayment($new_amount_paid, $new_balance, $pay_id, $doc_id);
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Payment Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Payment record deletion failed');
        }
        
        redirect('/documents/listing');
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
            $data['docs'] = $this->document_model->getDocs();
            // echo'<pre>HTTPS Clearing & Forwarding info Request: '; print_r($data['docs']); 
            // die;
            
            $this->global['pageTitle'] = 'Trulance : Lodged Documents ';
            $this->loadViews("documents/index", $this->global, $data, NULL);
        // }
    }
    
    function statementpdf(){
        // echo'<pre>HTTPS request: '; print_r($_REQUEST); 
        // die;
        
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
        $pdf->Write(25, '', '', 0, 'C', true, 9, false, false, 0);
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
    function statement(){
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
            // die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            //$data['documents'] = $this->document_model->getDocumentsList();
            $data['docs'] = $this->document_model->getDocs();
            
            $data['file_no'] = trim(filter_input(INPUT_GET, 'file_no'));
            $data['container_nr'] = trim(filter_input(INPUT_GET, 'container_nr'));
            // echo'<pre>'; print_r($data); 
            // die;
            
            $this->global['pageTitle'] = 'Trulance : Lodged Documents';
            $this->loadViews("documents/statement", $this->global, $data, NULL);
        }
    }
    
    function deletedoc(){
        //Get id
        $id = trim(filter_input(INPUT_GET, 'id'));
        
        //delete by id 
        $this->load->model('document_model');
        $result = $this->document_model->deleteDocument($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Document Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Document deletion failed');
        }
        
        redirect('/documents/listing');
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

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        // if
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
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
            
            $this->global['pageTitle'] = 'Trulance : Add New File';

            $this->loadViews("documents/addNew", $this->global, $data, NULL);
        }
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
    function addNewDocument()
    {
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
                // echo'<pre>Document HTTPS Request: '; print_r($_REQUEST);
                // die;
                $documentInfo = array();
                $documentInfo['file_no'] = trim(filter_input(INPUT_POST, 'file_no'));
                $documentInfo['date_received'] = trim(filter_input(INPUT_POST, 'date_received'));
                $documentInfo['bill_of_landing'] = trim(filter_input(INPUT_POST, 'bill_of_landing'));
                $documentInfo['vessel'] = trim(filter_input(INPUT_POST, 'vessel'));
                $documentInfo['manifest_no'] = trim(filter_input(INPUT_POST, 'manifest_no'));
                $documentInfo['eta_ata'] = trim(filter_input(INPUT_POST, 'eta_ata'));
                $documentInfo['client_id'] = trim(filter_input(INPUT_POST, 'client_id'));
                $documentInfo['consignee_id'] = trim(filter_input(INPUT_POST, 'consignee_id'));
                $documentInfo['shipping_line'] = trim(filter_input(INPUT_POST, 'shipping_line'));
                $documentInfo['cargo_type'] = trim(filter_input(INPUT_POST, 'cargo_type'));
                $documentInfo['collection_status'] = trim(filter_input(INPUT_POST, 'collection_status'));
                $documentInfo['container_size'] = trim(filter_input(INPUT_POST, 'container_size'));
                $documentInfo['consignement'] = trim(filter_input(INPUT_POST, 'consignement'));
                $documentInfo['idf_no'] = trim(filter_input(INPUT_POST, 'idf_no'));
                
                $documentInfo['clearing_charges'] = trim(filter_input(INPUT_POST, 'clearing_charges'));
                $documentInfo['extra_paid'] = trim(filter_input(INPUT_POST, 'extra_paid'));//
                if(empty($documentInfo['clearing_charges'])){
                    $documentInfo['clearing_charges'] = 0;
                }
                if(empty($documentInfo['extra_paid'])){
                    $documentInfo['extra_paid'] = 0;
                }
                
                $documentInfo['total'] = $documentInfo['clearing_charges'] + $documentInfo['extra_paid'];
                $documentInfo['t812_nr'] = trim(filter_input(INPUT_POST, 't812_nr'));
                $documentInfo['container_nr'] = trim(filter_input(INPUT_POST, 'container_nr'));//
                $documentInfo['amount_agreed'] = $documentInfo['clearing_charges'] + $documentInfo['extra_paid'];//
                $documentInfo['expenses'] = 0; //
                $documentInfo['amount_paid'] = 0; //amount_paid
                
                $containerInfo['container_no '] = $documentInfo['container_nr'];
                $containerInfo['file_no '] = $documentInfo['file_no'];
                // echo'<pre>Document Info HTTPS Request: '; print_r($documentInfo);
                // die;
                
                $this->load->model('document_model');
                
                //check if container is already lodged
                $constainerExistance = $this->document_model->containerExists($documentInfo['container_nr']);
                $couunter = count($constainerExistance);
                // echo'<pre>Document Exists?:: '; print_r($couunter);
                // die;
                if($couunter >= 1){
                    $this->session->set_flashdata('error', 'Container nr:'.$documentInfo['container_nr'].' exists!');
                }
                else if($couunter == 0){
                    $result = $this->document_model->addNewDocument($documentInfo, $containerInfo);
                    
                    if($result > 0)
                    {
                        $this->session->set_flashdata('success', 'New Document for container no. '.$documentInfo['container_nr'].' has been lodged successfully');
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Document creation failed');
                    }
                }
                
                redirect('/documents/add');
            //}
        }
    }
    
    function containers(){
        //echo'<pre>'; print_r($_REQUEST); die;
        //Get documents details by id
        $docID = $this->input->get("id");
        
        //Get file info by id
        $this->load->model('document_model');
        $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
        //echo'<pre>'; print_r($data['documentInfo']); die;
        
        $data['containers'] = $this->document_model->getDocumentContainers($data['documentInfo'][0]->file_no);
        
        //echo'<pre>'; print_r($data['containers']); die;
        
        $this->global['pageTitle'] = 'Trulance : Containers ';
        $this->loadViews("documents/containers", $this->global, $data, NULL);
    }
    
    function addContainer(){
        //echo'<pre>'; print_r($_REQUEST); die;
        $containerInfo = array();
        //$containerInfo['id'] =  $this->input->post("id");
        $containerInfo['file_no'] =  $this->input->post("file_no");
        $containerInfo['container_chasis_no'] =  $this->input->post("container_chasis_no");
        $containerInfo['description'] =  $this->input->post("description");
        
        //Get file info by id
        $this->load->model('document_model');
        $insert = $this->document_model->addNewContainer($containerInfo);
        //echo'<pre>'; print_r($insert); die;
        if($insert > 0)
        {
            $this->session->set_flashdata('success', 'Container added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Container addition failed');
        }
        
        redirect('/documents/containers?id='.$containerInfo['id']);
    }
    
    /**
     * This function is for updating the clearing info in the file
     * */
    function clearing(){
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else{
            //Get file id
            $docID = $this->input->get("id");
            
            //Get file info by id
            $this->load->model('document_model');
            $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
            // echo'<pre>'; print_r($data['documentInfo']); die;
            
            //display info to the user
            $this->global['pageTitle'] = 'Trulance : Registered Shipping Lines ';
            $this->loadViews("documents/clearing", $this->global, $data, NULL);
        }
    }
    
    /**
     * Updating clearance inforrmation
     * */
     function updateClearance(){
        // echo'<pre>'; print_r($_REQUEST); die;
        $documentInfo = [];
        // $documentInfo['id'] =  $this->input->post("id");
        
        $id =  $this->input->post("id");
        $documentInfo['charges'] =  $this->input->post("charges");
        $documentInfo['do_status'] =  $this->input->post("do_status");
        $documentInfo['down'] =  $this->input->post("down");
        $documentInfo['car_reg'] =  $this->input->post("car_reg");
        $documentInfo['gate_out'] =  $this->input->post("gate_out");
        $documentInfo['clearing_charges'] =  $this->input->post("clearing_charges");
        $documentInfo['extra_paid'] =  $this->input->post("extra_paid");
        $documentInfo['total'] =  $documentInfo['clearing_charges'] + $documentInfo['extra_paid'];
        $documentInfo['t810_nr'] = trim(filter_input(INPUT_POST, 't810_nr'));
        $documentInfo['t812_nr'] = trim(filter_input(INPUT_POST, 't812_nr'));
        $documentInfo['container_nr'] = trim(filter_input(INPUT_POST, 'container_nr'));//
        $documentInfo['notes'] = trim(filter_input(INPUT_POST, 'notes')); //
        $documentInfo['amount_paid'] = trim(filter_input(INPUT_POST, 'amount_paid')); //
        
        $amountPaid = trim(filter_input(INPUT_POST, 'amount_paid')); //
        if(empty($amountPaid)) {
            $amountPaid = 0;
        }
        else{
            //last_amount_paid
            $amountLastPaid = trim(filter_input(INPUT_POST, 'last_amount_paid')); 
            $amountPaid += $amountLastPaid;
            // $amountPaid = $amountPaid;
        }
        //Do the sumations 
        $documentInfo['balance'] = $documentInfo['total'] - $amountPaid;
        $documentInfo['amount_paid'] = $amountPaid;
        // echo'<pre>Amount paid: '; print_r($amountPaid);
        // echo'<pre>Balance: '; print_r($documentInfo['balance']); 
        // echo'<pre>'; print_r($documentInfo); 
        // die;
        
        $this->load->model('document_model');
        // $update = $this->document_model->editDocumentInfo($documentInfo, $documentInfo['id'], $amountPaid); //editDocumentInfo($documentInfo, $docID, $balance)
        $update = $this->document_model->editDocumentInfo($documentInfo, $id); //, $amountPaid); //editDocumentInfo($documentInfo, $docID, $balance)
        // echo'<pre>'; print_r($update); die;
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Document updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Document update failed');
        }
        
        redirect('/documents/clearing?id='.$id);
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
            
            $this->global['pageTitle'] = 'Trulance : Edit Client';
            
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
                
                redirect('userListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Trulance : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        
        $this->global['pageTitle'] = $active == "details" ? 'Trulance : My Profile' : 'Trulance : Change Password';
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