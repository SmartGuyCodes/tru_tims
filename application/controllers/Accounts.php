<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Accounts Manager
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Accounts extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Amey Trading : Registered Clients & Consignees ';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    function fees(){
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('transporter_model');
            $this->load->model('account_model');
            
            $data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
            $data['trucks'] = $this->transporter_model->getTransporterTrucks($data['transporter']);
            //Get fees in the db
            $data['fees'] = $this->account_model->getFees();
            //echo'<pre>'; print_r($data['fees']); die;
            $this->global['pageTitle'] = 'Amey Trading : Fees';

            $this->loadViews("accounts/fees", $this->global, $data, NULL);
        // }
    }
    
    function addNewFee(){
        $feeInfo = array();
        $feeInfo['title'] = trim(filter_input(INPUT_POST, 'title'));
        $feeInfo['type'] = trim(filter_input(INPUT_POST, 'type'));
        
        $this->load->model('account_model');
        $result = $this->account_model->addNewFee($feeInfo);
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'New Fee created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Fee creation failed');
        }
        
        redirect('/accounts/fees');
    }
    
    function deletefee(){
        $id = trim(filter_input(INPUT_POST, 'delid'));
        
        $this->load->model('account_model');
        $result = $this->account_model->deleteFee($id);
        
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Fee Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Fee deletion failed');
        }
        
        redirect('/accounts/fees');
    }
    
    function accountlist(){
        #echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('transporter_model');
            $this->load->model('account_model');
            
            //$data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
            //$data['trucks'] = $this->transporter_model->getTransporterTrucks($data['transporter']);
            //Get fees in the db
            $data['accounts'] = $this->account_model->getAccounts();
            //echo'<pre>'; print_r($data['accounts']); die;
            $this->global['pageTitle'] = 'Amey Trading : Accounts List';

            $this->loadViews("accounts/accountslist", $this->global, $data, NULL);
        // }
    }
    
    function addNewAccount(){
        $accInfo = array();
        $accInfo['name'] = trim(filter_input(INPUT_POST, 'name'));
        $accInfo['type'] = trim(filter_input(INPUT_POST, 'type'));
        
        $this->load->model('account_model');
        $result = $this->account_model->addNewAccount($accInfo);
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'New account created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Account creation failed');
        }
        
        redirect('/accounts/accountlist');
        //echo'<pre>'; print_r($_REQUEST); die;
    }
    
    function deleteaccount(){
        $id = trim(filter_input(INPUT_POST, 'delid'));
        
        $this->load->model('account_model');
        $result = $this->account_model->deleteAccount($id);
        
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Account Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Account deletion failed');
        }
        
        redirect('/accounts/accountlist');
    }
    
    function invoicelist(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {      
            $this->load->model('client_model');
            
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->client_model->clientListingCount($searchText);

            $returns = $this->paginationCompress ( "clientListing/", $count, 10 );
            //            
            $data['invoicerecords'] = $this->account_model->getInvoiceRecords();
            
            //echo'<pre>'; print_r($data['invoicerecords']); die;
            $this->global['pageTitle'] = 'Amey Trading : Quotations List ';
            
            $this->loadViews("accounts/invoicelist", $this->global, $data, NULL);
        // }
    }
    
    function preview(){
        // echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {      
            $this->load->model('client_model');
            
            //echo'<pre>'; print_r($_REQUEST); die;
            $data['invoicerecords'] = $this->account_model->getInvoiceRecords();
            
            //echo'<pre>'; print_r($data['invoicerecords']); die;
            $this->global['pageTitle'] = 'Amey Trading : Quotation Preview';
            
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            //$data['doc'] = $this->invoicepdfgen($data['id']);
            
            $this->loadViews("accounts/invoicepdf", $this->global, $data, NULL);
        // }
    }
    
    function invoicepdfgen($id){
        //Get invoice data via id
        $this->load->model('account_model');
        $invoiceInfo = $this->account_model->getInvoiceRecord($id);
        
        //Now get invoice items
        $invoiceNo = $invoiceInfo[0]->invoice_no;
        $invoiceClient = $invoiceInfo[0]->client;
        $invoiceAmount = $invoiceInfo[0]->amount;
        $invoiceStatus = $invoiceInfo[0]->status;
        $invoiceDate = $invoiceInfo[0]->invoice_date;
        $invoiceDiscount = $invoiceInfo[0]->discount;
        // $invoiceRef = $invoiceInfo[0]->invoice_no;
        
        $invoiceItems = $this->account_model->getInvItem($invoiceNo);
        
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        //table, th, td { border: 1px solid black; border-collapse: collapse; }
        $tbl = '
            <table border = "1" cellpadding = "5" align="left" style="border: 1px solid black; border-collapse: collapse; margin: 5px !important; padding: 5px !important;">
                <tr nobr="true">
                    <th colspan="3"><h3>Quotation No. <strong>'.$invoiceNo.'</strong></h3></th>
                    <th colspan="3" align="right"><strong>Bill to:</strong>
                        <br /> '.$invoiceClient.'
                    </th>
                </tr>
                <thead  width="100%">
                    <tr nobr="true" style="background-color:#3c8dbc;color:#000000;">
                        <th align="left" style="border: 1px solid black; border-collapse: collapse !important; padding: 15px !important; margin:5px;"><h3>Item</h3></th>
                        <th align="left" style="border: 1px solid black; border-collapse: collapse !important; padding: 15px !important; margin:5px;"><h3>Description</h3> </th>
                        <th align="right" style="border: 1px solid black; border-collapse: collapse !important; padding: 15px !important; margin:5px;"><h3>Amount </h3></th>
                    </tr>
                </thead>
            <tbody  width="100%">';
            
            foreach($invoiceItems as $i){
                $tbl .= '
                    <tr nobr="true">
                        <td style="border: 1px solid black; border-collapse: collapse !important;  padding: 15px !important;margin: 5px;" colspan="2" align="left">'.$i->charge.'</td>
                        <td style="border: 1px solid black; border-collapse: collapse !important;  padding: 15px !important;margin: 5px;"  colspan="2" align="left">'.$i->description.'</td>
                        <td style="border: 1px solid black; border-collapse: collapse !important;  padding: 15px !important;margin: 5px;"  colspan="2" align="right">'.number_format($i->amount,2).'</td>
                    </tr>'
                ;
            }    
        
            $tbl .= '
            <br />
            <br />
            <tr nobr="true" width="100%">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="background-color:#3c8dbc;color:#000000;" align="right"><h4>Total:</h4> </th>
                <th align="right">'.number_format($invoiceAmount, 2).'</th>
            </tr>
        
            <tr nobr="true" width="100%">
                <th></th>
                <th></th>
                <th></th>
                <th ></th>
                <th style="background-color:#3c8dbc;color:#000000;" align="right"><h4>Amount Paid: </h4></th>
                <th align="right">0</th>
            </tr>
        
            <tr nobr="true" width="100%">
                <th></th>
                <th></th>
                <th></th>
                <th ></th>
                <th style="background-color:#3c8dbc;color:#000000;" align="right"><h4>Amount Due: </h4></th>
                <th align="right">'.number_format($invoiceAmount, 2).'</th>
            </tr>
            </tbody>
        </table>';
        //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('voucher-'.$invoiceNo.'.pdf', 'I');
    }
    
    function previewvoucher(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {      
            $this->load->model('client_model');
            
            //Get voucher no. auto generator
            $data['voucherNo'] = 'PV'.$this->paymentNoGenerator($digits = 3);
            
            //Get payments done
            $this->load->model('payment_model');
            $data['payments'] = $this->payment_model->getAllVouchers();
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            
            $this->load->model('transporter_model');
            $data['transporters'] = $this->transporter_model->getTranportersList();
            //echo'<pre>'; print_r($data['clients']); die;
            
            //echo'<pre>'; print_r($data['invoicerecords']); die;
            $this->global['pageTitle'] = 'Amey Trading : Voucher Preview';
            
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            //$data['doc'] = $this->invoicepdfgen($data['id']);
            
            $this->loadViews("accounts/voucherpdf", $this->global, $data, NULL);
        // }
    }
    
    function numberToWords($num){ 
        //         $ones = array( 
        // 1 => "one", 
        // 2 => "two", 
        // 3 => "three", 
        // 4 => "four", 
        // 5 => "five", 
        // 6 => "six", 
        // 7 => "seven", 
        // 8 => "eight", 
        // 9 => "nine", 
        // 10 => "ten", 
        // 11 => "eleven", 
        // 12 => "twelve", 
        // 13 => "thirteen", 
        // 14 => "fourteen", 
        // 15 => "fifteen", 
        // 16 => "sixteen", 
        // 17 => "seventeen", 
        // 18 => "eighteen", 
        // 19 => "nineteen" 
        // ); 
        //         $tens = array( 
        // 1 => "ten",
        // 2 => "twenty", 
        // 3 => "thirty", 
        // 4 => "forty", 
        // 5 => "fifty", 
        // 6 => "sixty", 
        // 7 => "seventy", 
        // 8 => "eighty", 
        // 9 => "ninety" 
        // ); 
        //         $hundreds = array( 
        // "hundred", 
        // "thousand", 
        // "million", 
        // "billion", 
        // "trillion", 
        // "quadrillion" 
        // ); //limit t quadrillion 
        //         $num = number_format($num,2,".",","); 
        //         $num_arr = explode(".",$num); 
        //         $wholenum = $num_arr[0]; 
        //         $decnum = $num_arr[1]; 
        //         $whole_arr = array_reverse(explode(",",$wholenum)); 
        //         krsort($whole_arr); 
        //         $rettxt = ""; 
        //         foreach($whole_arr as $key => $i){ 
        //             if($i < 20){ 
        //                 $rettxt .= $ones[$i]; 
        //             }
        //             elseif($i < 100){ 
        //                 $rettxt .= $tens[substr($i,0,1)]; 
        //                 $rettxt .= " ".$ones[substr($i,1,1)]; 
        //             }
        //             else{ 
        //                 $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        //                 $rettxt .= " ".$tens[substr($i,1,1)]; 
        //                 $rettxt .= " ".$ones[substr($i,2,1)]; 
        //             } 
        //             if($key > 0){ 
        //                 $rettxt .= " ".$hundreds[$key]." "; 
        //             } 
        //         } 
        //         if($decnum > 0){ 
        //             $rettxt .= " and "; 
        //             if($decnum < 20){ 
        //                 $rettxt .= $ones[$decnum]; 
        //             }
        //             elseif($decnum < 100){ 
        //                 $rettxt .= $tens[substr($decnum,0,1)]; 
        //                 $rettxt .= " ".$ones[substr($decnum,1,1)]; 
        //             } 
        //         } 
                
        //         return $rettxt; 
    }
    
    function voucherpdfgen($id){
        //Get invoice data via id
        $this->load->model('payment_model');
        $voucherInfo = $this->payment_model->getVoucherProfile($id);
        
        //Now get invoice items
        $voucherNo = $voucherInfo[0]->voucher_no;
        $paymentDate = $voucherInfo[0]->payment_date;
        $payee = $voucherInfo[0]->transporter;
        $voucherAmount = $voucherInfo[0]->amount;
        $paymentMode = $voucherInfo[0]->payment_mode;
        $paymentDescr = $voucherInfo[0]->ref;
        //$paymentAmount = $this->numberToWords($voucherAmount);
        // echo'<pre>'; print_r($paymentAmount); die;
        
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(28, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
        <h2 style="text-align: center;"><strong>Payment Voucher</strong></h2>
        <p style="text-align:left"><strong>PV No.:</strong> <span style="color:#ff0000">'.$voucherNo.'</span></p>
        <hr>
        ';
        
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
                tr:nth-child(even) {
                    background-color: #dddddd;
                }
                table {
                  font-family: arial, sans-serif;
                  border-collapse: collapse;
                  width: 100%;
                }
                
                td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                }
            </style>
            <table border="0" cellpadding="2" cellspacing="2" align="left" width="100%">
                <tbody  width="100%">
                    <tr nobr="true">
                        <th colspan="3"><strong>Amount: </strong><br />'.number_format($voucherInfo[0]->amount, 2).'</th>
                        <th colspan="3"><strong>Payment Date: </strong><br />'.$paymentDate.'</th>
                    </tr>
                    <tr nobr="true" style="background-color:#3c8dbc;color:#fff; text-align: center;" width="100%">
                        <th align="center" colspan="6" style="margin-left: 33%;">Payment Details</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3" align="right">Paid to: </th>
                        <th colspan="3">'.$voucherInfo[0]->transporter.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3" align="right">Mode of payment: </th>
                        <th colspan="3">'.$voucherInfo[0]->payment_mode.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3" align="right">Sum Paid: </th>
                        <th colspan="3">'.number_format($voucherInfo[0]->amount, 2).'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3" align="right">Being: </th>
                        <th colspan="3">'.$voucherInfo[0]->ref.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3" align="right">Payee: </th>
                        <th colspan="3">Amey Trading Company Ltd</th>
                    </tr>
                </tbody>
            </table>
        ';
        //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('voucher-'.$voucherNo.'.pdf', 'I');
    }
    
    public function invoiceNoGen($digits = 6){
        $i = 0;
        $invNo = "";
        while($i < $digits){
                //Generate a random unique number
                $invNo .=mt_rand(0,9);
                $i++;
        }

        return $invNo;
    }
    
    function newInvoice(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('client_model');
            $this->load->model('account_model');
            
            $data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
            $data['clients'] = $this->client_model->getClientList();
            
            $data['invNo'] = $this->invoiceNoGen($digits = 6);
            $data['invNo'] = 'QT'.$data['invNo'];
            
            //Get fees in the db
            $data['fees'] = $this->account_model->getFees();
            //echo'<pre>'; print_r($data['clients']); die;
            $this->global['pageTitle'] = 'Amey Trading : New Quotation';

            $this->loadViews("accounts/newinvoice", $this->global, $data, NULL);
        // }
    }
    
    function addNewInvoice(){
        $invoiceInfo = array();
        $invoiceInfo['invoice_no'] = trim(filter_input(INPUT_POST, 'invoice_no'));
        $invoiceInfo['invoice_date'] = trim(filter_input(INPUT_POST, 'invoice_date'));
        $invoiceInfo['client'] = trim(filter_input(INPUT_POST, 'client'));
        //echo'<pre>'; print_r($_REQUEST);
        
        $this->load->model('account_model');
        $result = $this->account_model->addNewInvoice($invoiceInfo);
        //echo'<pre>'; print_r($result);die;
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'New Quotation created successfully. Add items');
        }
        else
        {
            $this->session->set_flashdata('error', 'Quotation creation failed');
        }
        
        redirect('/accounts/addIvoiceItems?invoice_no='.$invoiceInfo['invoice_no']);
    }
    
    function addIvoiceItems(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('client_model');
            $this->load->model('account_model');
            
            $data['transporter'] = trim(filter_input(INPUT_GET, 'id'));
            $data['clients'] = $this->client_model->getClientList();
            
            $data['invoice_no'] = trim(filter_input(INPUT_GET, 'invoice_no'));
            
            //Get invoice invoice details
            $data['invoiceInfo'] = $this->account_model->getInvoiceInfo($data['invoice_no']);
            
            //Get items in the db
            $data['items'] = $this->account_model->getInvoiceItems($data['invoice_no']);
            
            //echo'<pre>'; print_r($data['invoiceInfo']); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Invoice Items';

            $this->loadViews("accounts/newinvoiceitems", $this->global, $data, NULL);
        // }
    }
    
    function addInvoiceItem(){
        //echo'<pre>'; print_r($_REQUEST);die;
        $itemInfo = array();
        $itemInfo['charge'] = trim(filter_input(INPUT_POST, 'item'));
        $itemInfo['invoice_no'] = trim(filter_input(INPUT_POST, 'invoice_no'));
        $itemInfo['description'] = trim(filter_input(INPUT_POST, 'description'));
        $itemInfo['amount'] = trim(filter_input(INPUT_POST, 'amount'));
        
        $this->load->model('account_model');
        $result = $this->account_model->addNewInvoiceItem($itemInfo, $itemInfo['amount'], $itemInfo['invoice_no']);
        //echo'<pre>'; print_r($result);die;
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'New Quotation item created successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Quotation item creation failed');
        }
        
        redirect('/accounts/addIvoiceItems?invoice_no='.$itemInfo['invoice_no']);
    }
    
    function updateInvoiceItem(){
        //echo'<pre>'; print_r($_REQUEST);die;
        $itemInfo = array();
        $itemInfo['charge'] = trim(filter_input(INPUT_POST, 'item'));
        $itemInfo['invoice_no'] = trim(filter_input(INPUT_POST, 'invoice_no'));
        $id = trim(filter_input(INPUT_POST, 'id'));
        $itemInfo['description'] = trim(filter_input(INPUT_POST, 'description'));
        $itemInfo['amount'] = trim(filter_input(INPUT_POST, 'amount'));
        
        $this->load->model('account_model');
        $result = $this->account_model->addNewInvoiceItem($itemInfo, $itemInfo['amount'], $itemInfo['invoice_no']);
        //echo'<pre>'; print_r($result);die;
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'New Quotation item created successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Quotation item creation failed');
        }
        
        redirect('/accounts/edit?id='.$id);
    }
    
    function edit(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('client_model');
            $this->load->model('account_model');
            
            $data['clients'] = $this->client_model->getClientList();
            
            $id = trim(filter_input(INPUT_GET, 'id'));
            $data['profile'] = $this->account_model->getInvoiceRecord($id);
            
            $data['items'] = $this->account_model->getInvoiceItems($data['profile'][0]->invoice_no);
            //echo'<pre>'; print_r($data['profile']); die;
            
            //Get fees in the db
            $data['fees'] = $this->account_model->getFees();
            $this->global['pageTitle'] = 'Amey Trading : Edit Invoice';

            $this->loadViews("accounts/editinvoice", $this->global, $data, NULL);
        // }
    }
    
    function deleteinvoiceitem(){
        //get item id
        $itemId = trim(filter_input(INPUT_POST, 'delid'));
        
        //get the item info by using the id.
        $itemInfo = $this->account_model->getInvoiceItem($itemId);
        
        //Record item invoice numbe, and item amount
        $invoiceNo = $itemInfo[0]->invoice_no;
        $itemAmount = $itemInfo[0]->amount;
        
        //Subtract the amount from the invoice by invoice id
        //echo'<pre>'; print_r($itemInfo);die;
        $result = $this->account_model->removeInvoiceItem($itemId, $invoiceNo, $itemAmount);
        //echo'<pre>'; print_r($result);die;
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Quotation item deleted successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Quotation item deletion failed');
        }
        
        redirect('/accounts/addIvoiceItems?invoice_no='.$invoiceNo);
    }
    
    function deleteinvoice(){
        //Get invoice id
        $invoiceId = trim(filter_input(INPUT_GET, 'id'));
        
        //Get invoice info by id
        $invoiceInfo = $this->account_model->getInvoiceRecord($invoiceId);
        
        //Record invoice no
        $invoiceNo = $invoiceInfo[0]->invoice_no;
        
        //Delete invoice and its children from the db
        $result = $this->account_model->deleteInvoiceRecord($invoiceId, $invoiceNo);
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Quotation record deleted successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Quotation record deletion failed');
        }
        
        redirect('/accounts/invoicelist');
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
    
    function makepayments(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            
            //Get voucher no. auto generator
            $data['voucherNo'] = 'PV'.$this->paymentNoGenerator($digits = 3);
            
            //Get payments done
            $this->load->model('payment_model');
            $data['payments'] = $this->payment_model->getAllVouchers();
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            
            $this->load->model('transporter_model');
            $data['transporters'] = $this->transporter_model->getTranportersList();
            // echo'<pre>'; print_r($data['transporters']);
            // die;
            
            $this->load->model('document_model');
            $data['files'] = $this->document_model->getDocs();
            if(!empty($data['files'])){
                $data['transporter_id'] = $data['files'][0]->transporter_id;
            }
            else{
                $data['transporter_id'] = 0;
            }
            // echo'<pre>'; print_r(); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Voucher Listing';
            $this->loadViews("accounts/voucherlist", $this->global, $data, NULL);
        // }
    }
    
    function nontranspayment(){
        // echo'<pre>'; print_r($_REQUEST); die;
        $payInfo = [];
        $payInfo['voucher_no'] = trim(filter_input(INPUT_POST, 'voucher_no')); //
        $payInfo['container_no'] = trim(filter_input(INPUT_POST, 'container_no')); //file_no
        $payInfo['payment_date'] = trim(filter_input(INPUT_POST, 'payment_date'));
        $payInfo['payment_mode'] = trim(filter_input(INPUT_POST, 'payment_mode'));
        $payInfo['transporter'] = trim(filter_input(INPUT_POST, 'payee_name'));
        $payInfo['amount'] = trim(filter_input(INPUT_POST, 'amount')); //
        $payInfo['ref'] = trim(filter_input(INPUT_POST, 'ref')); //
        //echo'<pre>'; print_r($payInfo); die;
        
        //Get transporter trip expenses by file id
        $this->load->model('transporter_model');
        $fileInfo = $this->transporter_model->getTranporterExpense($payInfo['container_no']);
        // echo'<pre>'; print_r($fileInfo); die;
        $curr_balance = $fileInfo[0]->balance;
        $payable_balance = $curr_balance - $payInfo['amount'];
        
        $paymentInfo = [];
        // Info for the payment tracking table per transporter
        $paymentInfo['transporter_id'] = trim(filter_input(INPUT_POST, 'transporter_id'));
        $paymentInfo['txn_type'] = 'TRANSPORTER_PAY';
        $paymentInfo['transaction_date'] = date('d/m/Y');
        $paymentInfo['ref'] = $payInfo['ref'];//'PY'.$this->fileNoGenerator(3).'/RF'.$this->fileNoGenerator(3);
        $paymentInfo['payment_for'] = $payInfo['container_no'];
        $paymentInfo['amount'] = $payInfo['amount'];//$expenseInfo['advance'];
        $paymentInfo['created_at'] = date('d/m/Y');
        
        // echo'<pre>Payment info: '; print_r($paymentInfo); die;
        // echo'<pre>Current balance: '; print_r($curr_balance); 
        // echo'<pre>Amount paid: '; print_r($payInfo['amount']); 
        // echo'<pre>Payable balance: '; print_r($payable_balance); 
        // die;
        
        $this->load->model('payment_model');
        $result = $this->payment_model->insertNewPayment($paymentInfo, $payInfo, $payInfo['container_no'], $payInfo['amount'], $payable_balance);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Payment record created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Payment record creation failed');
        }
        
        redirect('/accounts/makepayments');
    }
    
    function editpaymentvoucher(){
        echo'<pre>'; print_r($_REQUEST); die;
    }
    
    function deletepaymentvoucher(){
        //echo'<pre>'; print_r($_REQUEST); die;
        $id = trim(filter_input(INPUT_POST, 'delid'));
        
        $this->load->model('payment_model');
        $result = $this->payment_model->removeVoucherProfile($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Voucher Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Voucher Record Deletion failed');
        }
        
        redirect('/accounts/makepayments');
    }
    
    function previewreceipt(){
        // echo'<pre>'; print_r($_REQUEST); die;
        //  echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {      
            // echo'<pre>'; print_r($_REQUEST); die;
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            
            //Get voucher no. auto generator
            $data['receiptNo'] = 'RT'.$this->paymentNoGenerator($digits = 3);
            
            //Get payments done
            $this->load->model('payment_model');
            $data['payments'] = $this->payment_model->getAllReceipts();
            
            //Get clients list and transporters list
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            
            $this->load->model('transporter_model');
            $data['transporters'] = $this->transporter_model->getTranportersList();
            
            $this->global['pageTitle'] = 'Amey Trading : Receipts Listing';
            $this->loadViews("accounts/receiptpdf", $this->global, $data, NULL);
        // }
    }
    
    function letarisitipdf(){
        // echo'<pre>';print_r($_REQUEST); die;
        //Get invoice data via id
        $id = trim(filter_input(INPUT_GET, 'id'));
        $this->load->model('payment_model');
        $receiptInfo = $this->payment_model->getReceiptProfile($id);
        
        // echo'<pre>';print_r($_REQUEST);
        // echo'<pre>'; print_r($receiptInfo); die;
        
        //Now get receipt items
        $receipt_no = $receiptInfo[0]->receipt_no;
        $client = $receiptInfo[0]->client;
        $amount = $receiptInfo[0]->amount;
        $payment_mode = $receiptInfo[0]->payment_mode;
        $transaction_details = $receiptInfo[0]->transaction_details;
        $transaction_date = $receiptInfo[0]->transaction_date;
        // $invoiceRef = $receiptInfo[0]->invoice_no;
        
        //$invoiceItems = $this->account_model->getInvItem($invoiceNo);
        
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(28, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        
        $html = '
        <h2 style="text-align: center;"><strong>Payment Receipt</strong></h2>
        <p style="text-align:left"><strong>Receipt No.:</strong> <span style="color:#ff0000">'.$receipt_no.'</span></p>
        <hr>
        ';
        
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
                tr:nth-child(even) {
                    background-color: #dddddd;
                }
                table {
                  font-family: arial, sans-serif;
                  border-collapse: collapse;
                  width: 100%;
                }
                
                td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                }
            </style>
            <table border="0" cellpadding="2" cellspacing="2" align="left" width="100%">
                <tbody  width="100%">
                    <tr nobr="true">
                        <th colspan="3"><strong>Amount: </strong><br />'.number_format($amount, 2).'</th>
                        <th colspan="3"><strong>Payment Date: </strong><br />'.$transaction_date.'</th>
                    </tr>
                    <tr nobr="true" style="background-color:#3c8dbc;color:#fff; text-align: center;" width="100%">
                        <th align="center" colspan="6" style="margin-left: 33%;">Transaction Details</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3">Received from: </th>
                        <th colspan="3">'.$client.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3">Mode of payment: </th>
                        <th colspan="3">'.$payment_mode.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3">Sum Received: </th>
                        <th colspan="3">'.number_format($amount,2).'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3">Being: </th>
                        <th colspan="3">'.$transaction_details.'</th>
                    </tr>
                    <tr nobr="true">
                        <th style="background-color:#3c8dbc;color:#fff; text-align: center;" colspan="3">Received by: </th>
                        <th colspan="3">Amey Trading Company Ltd</th>
                    </tr>
                </tbody>
            </table>
        ';
        //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('receipt-'.$receipt_no.'.pdf', 'I');
    }
    
    function receivepayments(){
        //echo'<pre>'; print_r($_REQUEST); die;
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            
            //Get voucher no. auto generator
            $data['receiptNo'] = 'RT'.$this->paymentNoGenerator($digits = 3);
            
            //Get payments done
            $this->load->model('payment_model');
            $data['payments'] = $this->payment_model->getAllReceipts();
            
            //Get clients list and transporters list
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            
            $this->load->model('transporter_model');
            $data['transporters'] = $this->transporter_model->getTranportersList();
            
            //Get documents
            $this->load->model('document_model');
            $data['files'] = $this->document_model->getDocs();
            
            $this->global['pageTitle'] = 'Amey Trading : Receipts Listing';
            $this->loadViews("accounts/receiptlist", $this->global, $data, NULL);
        // }
    }
    
    function clientfiles(){ //$file_no
        echo'<pre>'; print_r($_REQUEST); die;
        //Get documents
        $this->load->model('document_model');
        $data = $this->document_model->getDocumentData($file_no);
        echo'<pre>'; print_r($data['files']); die;
        
        // encoding array to json format
        echo json_encode($data);
    }
    
    function receivepayment(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
        // die;
        $receiptInfo = [];
        $receiptInfo['receipt_no'] = trim(filter_input(INPUT_POST, "receipt_no"));
        $receiptInfo['transaction_date'] = trim(filter_input(INPUT_POST, "transaction_date"));
        $receiptInfo['amount'] = trim(filter_input(INPUT_POST, "amount"));
        $receiptInfo['payment_mode'] = trim(filter_input(INPUT_POST, "payment_mode"));
        $receiptInfo['user_type'] = trim(filter_input(INPUT_POST, "user_type"));
        $receiptInfo['transaction_details'] = trim(filter_input(INPUT_POST, "transaction_details"));
        $receiptInfo['container_no'] = trim(filter_input(INPUT_POST, "container_no"));
        $receiptInfo['client'] = trim(filter_input(INPUT_POST, "client"));
        // echo'<pre>'; print_r($_REQUEST); 
        
        //Get documents
        $container_no = $receiptInfo['container_no'];
        $this->load->model('document_model');
        $file = $this->document_model->getDocumentDataByCont($container_no); 
        echo'<pre>'; print_r($file);
        // die;
        $receiptInfo['client'] = $file[0]->client_id; //client_id
        $clientName = $receiptInfo['client'];
        
        //Get client info
        $this->load->model('client_model');
        $clientInfo = $this->client_model->getClientDetale($receiptInfo['client']);
        // echo'<pre>'; print_r($clientInfo);
        
        // echo'<pre> File info: '; print_r($file); die;
        $last_paid_amount = $file[0]->amount_paid;
        if(!isset($file[0]->amount_paid) || empty($file[0]->amount_paid)){$last_paid_amount = 0;}
        
        $paymentInfo = [];
        // Info for the payment tracking table per transporter
        $paymentInfo['client_id'] = $clientInfo->id;
        $paymentInfo['txn_type'] = 'CLIENT_PAY';
        $paymentInfo['transaction_date'] = date("Y-m-d H:i:s");
        $paymentInfo['ref'] = $receiptInfo['transaction_details']; //$payInfo['ref'];//'PY'.$this->fileNoGenerator(3).'/RF'.$this->fileNoGenerator(3);
        $paymentInfo['payment_for'] = $receiptInfo['container_no'];
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
        
        // echo'<pre>'; print_r($agreedCharges);
        // echo'<pre>'; print_r($newBalance); 
        // die;
        
        $this->load->model('payment_model');
        $result = $this->payment_model->insertNewReceipt($receiptInfo, $amountPaid, $balancePayable, $container_no, $paymentInfo); //add $balancePayable
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Payment record created successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Payment record creation failed');
        }
        
        redirect('/accounts/receivepayments');
    }
    
    function deletepaymentreceipt(){
        $id = trim(filter_input(INPUT_POST, 'delid'));
        
        $this->load->model('payment_model');
        $result = $this->payment_model->removeReceiptProfile($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Payment Record deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Receipt deletion failed');
        }
        
        redirect('/accounts/receivepayments');
    }
    
    function creditorsdebtors(){
        echo'<pre>'; print_r($_REQUEST); die;
    }
    
    /**
     * This function is used to load the user list
     */
    public function listing()
    {
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            //$data['documents'] = $this->document_model->getDocumentsList();
            $data['docs'] = $this->document_model->getDocs();
            
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            $this->global['pageTitle'] = 'Amey Trading : Registered Shipping Lines ';
            $this->loadViews("documents/index", $this->global, $data, NULL);
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

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('document_model');
            $this->load->model('client_model');
            $this->load->model('shippingline_model');
            
            $data['roles'] = '';//$this->client_model->getUserRoles();
            $data['fileNo'] = $this->fileNoGenerator($digits = 6);
            $data['fileNo'] = 'FL0'.$data['fileNo'];
            
            $data['clients'] = $this->client_model->getClientList();
            $data['shippinglines'] = $this->shippingline_model->getShippinglines();
            #echo'<pre>'; print_r($data['shippinglines']); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Add New File';

            $this->loadViews("documents/addNew", $this->global, $data, NULL);
        // }
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
        //echo'<pre>'; print_r($_REQUEST); die;
        if($this->isAdmin() == TRUE)
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
                
                $this->load->model('document_model');
                $result = $this->document_model->addNewDocument($documentInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Document created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Document creation failed');
                }
                
                redirect('/documents/add');
            //}
        }
    }
    
    function containers(){
        //Get documents details by id
        $docID = $this->input->get("id");
        
        //Get file info by id
        $this->load->model('document_model');
        $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
        $data['containers'] = $this->document_model->getDocumentContainers($data['documentInfo'][0]->file_no);
        
        //echo'<pre>'; print_r($data['containers']); die;
        
        $this->global['pageTitle'] = 'Amey Trading : Containers ';
        $this->loadViews("documents/containers", $this->global, $data, NULL);
    }
    
    function addContainer(){
        //echo'<pre>'; print_r($_REQUEST); die;
        $containerInfo = array();
        $containerInfo['id'] =  $this->input->post("id");
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
        if($this->isAdmin() == TRUE){
            $this->loadThis();
        }
        else{
            //Get file id
            $docID = $this->input->get("id");
            
            //Get file info by id
            $this->load->model('document_model');
            $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
            //echo'<pre>'; print_r($data['documentInfo']); die;
            
            //display info to the user
            $this->global['pageTitle'] = 'Amey Trading : Registered Shipping Lines ';
            $this->loadViews("documents/clearing", $this->global, $data, NULL);
        }
    }
    
    /**
     * Updating clearance inforrmation
     * */
     function updateClearance(){
        $documentInfo = [];
        $documentInfo['id'] =  $this->input->post("id");
        $documentInfo['charges'] =  $this->input->post("charges");
        $documentInfo['do_status'] =  $this->input->post("do_status");
        $documentInfo['down'] =  $this->input->post("down");
        $documentInfo['car_reg'] =  $this->input->post("car_reg");
        $documentInfo['gate_out'] =  $this->input->post("gate_out");
        
        $this->load->model('document_model');
        $update = $this->document_model->editDocumentInfo($documentInfo, $documentInfo['id']);
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Document updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Document update failed');
        }
        
        redirect('/documents/clearing?id='.$documentInfo['id']);
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
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        if($this->isAdmin() == TRUE)
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