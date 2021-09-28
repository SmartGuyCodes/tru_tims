<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
 
    

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reports Manager
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Reports extends BaseController{
    
   
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports_model');
        $this->load->library('Pdf');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Amey Trading : Cargo Operations Management System';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    private function formatMoney($number, $fractional=false) {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    } 
    
    public function filterdailyreports(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            //echo'<pre>'; print_r($_REQUEST); die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            $data['clientlist'] = $this->reports_model->getListOfClients();
            
            $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/dailyreportsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filtereddailyreportspdf(){
        $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start'));
        $enddate = trim(filter_input(INPUT_GET, 'end'));
        $this->load->model('reports_model');
        $dailyreports = $this->reports_model->getDailyReportsByFilter($consignee, $startdate, $enddate);
        
        // echo'<pre>'; print_r(sizeof($dailyreports));
        // echo'<pre>';print_r($_REQUEST); 
        // echo'<pre>';print_r($dailyreports);
        if (sizeof($dailyreports) == 1) {
            $dailyrep = $dailyreports[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($dailyreports) > 1) {
            $dailyreports = $dailyreports;
        }
        // die; 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        // set page format (read source code documentation for further information)
        $pdf->AddPage();
        
        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 21, false, false, 0);
        $pdf->Write(18, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 21, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Daily Report. </strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        if (sizeof($dailyreports) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Date Received</th>
                        <th  >Consignee</th>
                        <th  >Container nr.</th>
                        <th  >Size</th>
                        <th  >ETA</th>
                        <th  >Charges</th>
                        <th  >D.O. Status</th>
                        <th  >Down</th>
                        <th  >Car Reg</th>
                        <th  >Gate Out</th>
                    </tr>
                    <tr>
                        <td>'.$dailyrep->date_received.'</td>
                        <td>'.$dailyrep->consignee_id.'</td>
                        <th>'.$dailyrep->container_nr.'</th>
                        <th>'.$dailyrep->container_size.'</th>
                        <th>'.$dailyrep->eta_ata.'</th>
                        <th>'.$dailyrep->charges.'</th>
                        <th>'.$dailyrep->do_status.'</th>
                        <th>'.$dailyrep->down.'</th>
                        <th>'.$dailyrep->car_reg.'</th>
                        <th>'.$dailyrep->gate_out.'</th>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($dailyreports) > 1){
            $tbl = '
                <style>
                    
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Date Received</th>
                            <th  >Consignee</th>
                            <th  >Container nr.</th>
                            <th  >Size</th>
                            <th  >ETA</th>
                            <th  >Charges</th>
                            <th  >D.O. Status</th>
                            <th  >Down</th>
                            <th  >Car Reg</th>
                            <th  >Gate Out</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($dailyreports as $dailyrep){
                        $tbl .= '
                            <tr>
                                <td>'.$dailyrep->date_received.'</td>
                                <td>'.$dailyrep->consignee_id.'</td>
                                <th>'.$dailyrep->container_nr.'</th>
                                <th>'.$dailyrep->container_size.'</th>
                                <th>'.$dailyrep->eta_ata.'</th>
                                <th>'.$dailyrep->charges.'</th>
                                <th>'.$dailyrep->do_status.'</th>
                                <th>'.$dailyrep->down.'</th>
                                <th>'.$dailyrep->car_reg.'</th>
                                <th>'.$dailyrep->gate_out.'</th>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        // $pdf->writeHTML($tbl, true, true, true, true, '');
        
        $pdf->Output('dailyreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    public function dailyreportspdf(){
        // echo'<pre>'; print_r($_REQUE); die;
        
        $this->load->model('reports_model');
        $dailyreports = $this->reports_model->getDailyReports(); 
        
        // echo'<pre>'; print_r(sizeof($dailyreports));
        if (sizeof($dailyreports) == 1) {
            $dailyrep = $dailyreports[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($dailyreports) > 1) {
            $dailyreports = $dailyreports;
        }
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
        $pdf->Write(27, '', '', 0, 'C', true, 21, false, false, 0);
        $pdf->Write(18, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 21, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Daily Reports </strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        
        //if(!empty($dailyreports)){
        if (sizeof($dailyreports) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Date Received</th>
                        <th  >Consignee</th>
                        <th  >Container nr.</th>
                        <th  >Size</th>
                        <th  >ETA</th>
                        <th  >Charges</th>
                        <th  >D.O. Status</th>
                        <th  >Down</th>
                        <th  >Car Reg</th>
                        <th  >Gate Out</th>
                    </tr>
                    <tr>
                        <td>'.$dailyrep->date_received.'</td>
                        <td>'.$dailyrep->consignee_id.'</td>
                        <th>'.$dailyrep->container_nr.'</th>
                        <th>'.$dailyrep->container_size.'</th>
                        <th>'.$dailyrep->eta_ata.'</th>
                        <th>'.$dailyrep->charges.'</th>
                        <th>'.$dailyrep->do_status.'</th>
                        <th>'.$dailyrep->down.'</th>
                        <th>'.$dailyrep->car_reg.'</th>
                        <th>'.$dailyrep->gate_out.'</th>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($dailyreports) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Date Received</th>
                            <th  >Consignee</th>
                            <th  >Container nr.</th>
                            <th  >Size</th>
                            <th  >ETA</th>
                            <th  >Charges</th>
                            <th  >D.O. Status</th>
                            <th  >Down</th>
                            <th  >Car Reg</th>
                            <th  >Gate Out</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($dailyreports as $dailyrep){
                        $tbl .= '
                            <tr>
                                <td>'.$dailyrep->date_received.'</td>
                                <td>'.$dailyrep->consignee_id.'</td>
                                <th>'.$dailyrep->container_nr.'</th>
                                <th>'.$dailyrep->container_size.'</th>
                                <th>'.$dailyrep->eta_ata.'</th>
                                <th>'.$dailyrep->charges.'</th>
                                <th>'.$dailyrep->do_status.'</th>
                                <th>'.$dailyrep->down.'</th>
                                <th>'.$dailyrep->car_reg.'</th>
                                <th>'.$dailyrep->gate_out.'</th>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // ob_clean();
        ob_start();
        $pdf->Output('dailyreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
        ob_end_flush();
    }
    public function dailyreports(){
        // echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['clientlist'] = $this->reports_model->getListOfClients();
            
            $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/dailyreports", $this->global, $data, NULL);
        // }
    }
    
    // filterinterchange
    public function filterinterchange(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>Search Request: '; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/interchangefiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredinterchangepdf(){
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        // die;
        // $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start'));
        $enddate = trim(filter_input(INPUT_GET, 'end'));
        
        $interchangerecords = $this->reports_model->getInterchangeReportsByFilter($startdate, $enddate);
        // echo'<pre>'; print_r(($startdate)); 
        // echo'<pre>'; print_r(($enddate)); 
        // echo'<pre>'; print_r(($interchangerecords)); 
        // die;
        
        if (sizeof($interchangerecords) == 1) {
            $interch = $interchangerecords[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($interchangerecords) > 1) {
            $interchangerecords = $interchangerecords;
        }
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        // set page format (read source code documentation for further information)
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Daily Report. </strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        
        if (sizeof($interchangerecords) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Driver Name</th>
                        <th>Depot</th>
                        <th>Date Entered</th>
                        <th>Container No.</th>
                        <th>Interchange Date</th>
                        <th>Container Size</th>
                        <th>Truck Reg.</th>
                        <th>Shipping Line</th>
                    </tr>
                    <tr>
                        <td>'.$interch->driver_name.'</td>
                        <td>'.$interch->depot.'</td> 
                        <td>'.$interch->entry_date.'</td>
                        <td>'.$interch->container_no.'</td> 
                        <td>'.$interch->interchange_date.' </td>
                        <td>'.$interch->container_size.'</td> 
                        <td>'.$interch->truck_no.' </td>
                        <td>'.$interch->agent_shipping_line.'</td>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($interchangerecords) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Depot</th>
                            <th>Date Entered</th>
                            <th>Container No.</th>
                            <th>Interchange Date</th>
                            <th>Container Size</th>
                            <th>Truck Reg.</th>
                            <th>Shipping Line</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($interchangerecords as $interch){
                        $tbl .= '
                            <tr>
                                <td>'.$interch->depot.'</td> 
                                <td>'.$interch->entry_date.'</td>
                                <td>'.$interch->container_no.'</td> 
                                <td>'.$interch->interchange_date.' </td>
                                <td>'.$interch->container_size.'</td> 
                                <td>'.$interch->truck_no.' </td>
                                <td>'.$interch->agent_shipping_line.'</td>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        // $pdf->writeHTML($tbl, true, true, true, true, '');
        
        $pdf->Output('dailyreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    public function interchangepdf(){
        $this->load->model('reports_model');
        $interchangerecords = $this->reports_model->getInterchangeReport();
        // echo'<pre>'; print_r(($interchangerecords)); 
        // die;
        
        if (sizeof($interchangerecords) == 1) {
            $interch = $interchangerecords[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($interchangerecords) > 1) {
            $interchangerecords = $interchangerecords;
        }
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
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Interchange Report</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($interchangerecords) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Entry Date</th>
                        <th width="10%">Container No.</th>
                        <th>Interchange Date</th>
                        <th width="15%">Depot</th>
                        <th>Container Size</th>
                        <th>Truck</th>
                        <th>Driver</th>
                        <th>Shipping Line</th>
                        <th align="right">Charge</th>
                        <th>Deposit</th>
                        <th>Client </th>
                    </tr>
                    <tr>
                        <td>'.$interch->entry_date.'</td>
                        <td width="10%">'.$interch->container_no.'</td> 
                        <td>'.$interch->interchange_date.'</td>
                        <td width="15%">'.$interch->depot.'</td> 
                        <td>'.$interch->container_size.' </td>
                        <td>'.$interch->truck_no.'</td> 
                        <td>'.$interch->driver.' </td>
                        <td>'.$interch->agent_shipping_line.'</td>
                        <td align="right">'.number_format($interch->charge, 2).' </td>
                        <td>'.$interch->deposit.'</td>
                        <td>'.$interch->client_name.'</td>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($interchangerecords) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Entry Date</th>
                            <th width="10%">Container No.</th>
                            <th>Interchange Date</th>
                            <th width="15%">Depot</th>
                            <th>Container Size</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>Shipping Line</th>
                            <th align="right">Charge</th>
                            <th>Deposit</th>
                            <th>Client </th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($interchangerecords as $interch){
                        $tbl .= '
                            <tr>
                                <td>'.$interch->entry_date.'</td>
                                <td width="10%">'.$interch->container_no.'</td> 
                                <td>'.$interch->interchange_date.'</td>
                                <td width="15%">'.$interch->depot.'</td> 
                                <td>'.$interch->container_size.' </td>
                                <td>'.$interch->truck_no.'</td> 
                                <td>'.$interch->driver.' </td>
                                <td>'.$interch->agent_shipping_line.'</td>
                                <td align="right">'.number_format($interch->charge, 2).' </td>
                                <td>'.$interch->deposit.'</td>
                                <td>'.$interch->client_name.'</td>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('interchangereport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    public function innterchangereport(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Interchange Report';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/interchange", $this->global, $data, NULL);
        // }
    }

    //Filter customer jobs
    public function filtercustomerjobs(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>Search HTTPS Request: '; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/customerjobsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredcustomerjobspdf(){
        // echo'<pre>HTTPS Request:  '; print_r($_REQUEST); 
        // die;
        
        // $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start'));
        $enddate = trim(filter_input(INPUT_GET, 'end'));
        
        $this->load->model('reports_model');
        $customerjobs = $this->reports_model->getFilteredCustomerJobsReport($startdate, $enddate);
        // echo'<pre>Filtered Response:  '; print_r($customerjobs); 
        // die;
        
        //$job
        if (sizeof($customerjobs) == 1) {
            $job = $customerjobs[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($customerjobs) > 1) {
            $customerjobs = $customerjobs;
        }
        // echo'<pre>'; print_r($customerjobs); die;
        
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
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Customer Jobs</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($customerjobs) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Container No.</th>
                        <th>Date Received</th>
                        <th>Consignee</th>
                        <th>Shipping Line</th>
                        <th>BL No.</th>
                        <th>Cargo Type</th>
                        <th>Client</th>
                    </tr>
                    <tr>
                        <td>'.$job->container_nr.'</td> 
                        <td>'.$job->date_received.' </td>
                        <td>'.$job->consignee_id.'</td> 
                        <td>'.$job->shipping_line.' </td>
                        <td>'.$job->bill_of_landing.'</td> 
                        <td>'.$job->cargo_type.' </td>
                        <td>'.$job->client_id.'</td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($customerjobs) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Container No.</th>
                            <th>Date Received</th>
                            <th>Consignee</th>
                            <th>Shipping Line</th>
                            <th>BL No.</th>
                            <th>Cargo Type</th>
                            <th>Client</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($customerjobs as $job){
                        $tbl .= '
                            <tr>
                                <td>'.$job->container_nr.'</td> 
                                <td>'.$job->date_received.' </td>
                                <td>'.$job->consignee_id.'</td> 
                                <td>'.$job->shipping_line.' </td>
                                <td>'.$job->bill_of_landing.'</td> 
                                <td>'.$job->cargo_type.' </td>
                                <td>'.$job->client_id.'</td>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('customerjobs'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function customerjobspdf(){
        $this->load->model('reports_model');
        $customerjobs = $this->reports_model->getCustomerJobsReport();
        
        //$job
        if (sizeof($customerjobs) == 1) {
            $job = $customerjobs[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($customerjobs) > 1) {
            $customerjobs = $customerjobs;
        }
        // echo'<pre>'; print_r($customerjobs); die;
        
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
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Customer Jobs</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($customerjobs) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Container No.</th>
                        <th>Date Received</th>
                        <th>Consignee</th>
                        <th>Shipping Line</th>
                        <th>BL No.</th>
                        <th>Cargo Type</th>
                        <th>Client</th>
                    </tr>
                    <tr>
                        <td>'.$job->container_nr.'</td> 
                        <td>'.$job->date_received.' </td>
                        <td>'.$job->consignee_id.'</td> 
                        <td>'.$job->shipping_line.' </td>
                        <td>'.$job->bill_of_landing.'</td> 
                        <td>'.$job->cargo_type.' </td>
                        <td>'.$job->client_id.'</td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($customerjobs) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Container No.</th>
                            <th>Date Received</th>
                            <th>Consignee</th>
                            <th>Shipping Line</th>
                            <th>BL No.</th>
                            <th>Cargo Type</th>
                            <th>Client</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($customerjobs as $job){
                        $tbl .= '
                            <tr>
                                <td>'.$job->container_nr.'</td> 
                                <td>'.$job->date_received.' </td>
                                <td>'.$job->consignee_id.'</td> 
                                <td>'.$job->shipping_line.' </td>
                                <td>'.$job->bill_of_landing.'</td> 
                                <td>'.$job->cargo_type.' </td>
                                <td>'.$job->client_id.'</td>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('customerjobs'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function customerjobs(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['customerjobs'] = $this->reports_model->getCustomerJobsReport();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Customer Jobs';

            $this->loadViews("reports/customerjobs", $this->global, $data, NULL);
        // }
    }
    
    function clientslistpdf(){
        $title = 'CLIENTS LIST';
        $this->load->model('reports_model');
        $clientlist = $this->reports_model->getListOfClients();
        
        if (sizeof($clientlist) == 1) {
            $client = $clientlist[0];
        }
        else if (sizeof($clientlist) > 1) {
            $clientlist = $clientlist;
        }
        
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
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>List of Clients</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        //$client
        if (sizeof($clientlist) == 1) {
            $tbl = '
                <style>
                </style>
                <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                    <thead  width="100%">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                           <th>Client Name</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Tel. No</th>
                            <th>Email</th>
                            <th>Reg. Date</th>
                            <th>VAT No.</th>
                            <th>PIN No.</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <tr>
                            <td>'.$client->client_name.'</td> 
                            <td>'.$client->contact_person.' </td>
                            <td>'.$client->address.'</td> 
                            <td>'.$client->tel_no.' </td>
                            <td>'.$client->email.'</td> 
                            <td>'.$client->created_at.' </td>
                            <td> '.$client->vat_no.' </td> 
                            <td>'.$client->pin_no.' </td> 
                        </tr>
                    </tbody>
                </table>
                '
            ;
        }
        else if(sizeof($clientlist) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Client Name</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Tel. No</th>
                            <th>Email</th>
                            <th>Reg. Date</th>
                            <th>VAT No.</th>
                            <th>PIN No.</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($clientlist as $client){
                        $tbl .= '
                            <tr>
                                <td>'.$client->client_name.'</td> 
                                <td>'.$client->contact_person.' </td>
                                <td>'.$client->address.'</td> 
                                <td>'.$client->tel_no.' </td>
                                <td>'.$client->email.'</td> 
                                <td>'.$client->created_at.' </td>
                                <td> '.$client->vat_no.' </td> 
                                <td>'.$client->pin_no.' </td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('clientslist'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function clientslist(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['clientlist'] = $this->reports_model->getListOfClients();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : List of Clients';

            $this->loadViews("reports/clientslist", $this->global, $data, NULL);
        // }
    }
    
    function transporterslistpdf(){
        $this->load->model('reports_model');
        $transporterslist = $this->reports_model->getListOfTransporters();
        
        //$transporter
        if (sizeof($transporterslist) == 1) {
            $transporter = $transporterslist[0];
            // echo'<pre>'; print_r($client); 
        }
        else if (sizeof($transporterslist) > 1) {
            $transporterslist = $transporterslist;
        }
        
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
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>List of Transporters</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        
        //$transporter
        if (sizeof($transporterslist) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Transporter Name</th>
                        <th>Contact Person</th>
                        <th>Tel. No</th>
                        <th>Email</th>
                        <th>Physical Address</th>
                    </tr>
                    <tr>
                        <td>'.$transporter->transporter_name.'</td> 
                        <td>'.$transporter->contact_person.' </td>
                        <td>'.$transporter->mobile_no.' </td>
                        <td>'.$transporter->email.' </td>
                        <td>'.$transporter->physical_address.'</td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($transporterslist) > 1){
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
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Transporter Name</th>
                            <th>Contact Person</th>
                            <th>Tel. No</th>
                            <th>Email</th>
                            <th>Physical Address</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($transporterslist as $transporter){
                        $tbl .= '
                            <tr>
                                <td>'.$transporter->transporter_name.'</td> 
                                <td>'.$transporter->contact_person.' </td>
                                <td>'.$transporter->mobile_no.' </td>
                                <td>'.$transporter->email.' </td>
                                <td>'.$transporter->physical_address.'</td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('transporterslist'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function transporterslist(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['transporterslist'] = $this->reports_model->getListOfTransporters();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : List of Transporters';

            $this->loadViews("reports/transporterslist", $this->global, $data, NULL);
        // }
    }
    
    function truckslistpdf(){
        $this->load->model('reports_model');
        $truckslist = $this->reports_model->getListOfTrucks();
        // echo'<pre>Trucklist:  '; print_r($truckslist);
        // die;
        
        if (sizeof($truckslist) == 1) {
            $truck = $truckslist[0];
            // echo'<pre>Trucklist: '; print_r($truck); 
        }
        else if (sizeof($truckslist) > 1) {
            $truckslist = $truckslist;
            // echo'<pre>Trucklist:  '; print_r($truckslist);
        }
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h1 style="margin-left:33%;"><strong>List of Trucks</strong></h1>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        //truck
        if (sizeof($truckslist) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Transporter Name</th>
                        <th>Truck Reg. No.</th>
                        <th>Driver Name</th>
                        <th>Telephone</th>
                    </tr>
                    <tr>
                        <td>'.$truck->transporter_name.' </td>
                        <td>'.$truck->truck_no.'</td> 
                        <td>'.$truck->driver_name.' </td>
                        <td>'.$truck->driver_mobile_no.'</td> 
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($truckslist) > 1){
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Transporter Name</th>
                            <th>Truck Reg. No.</th>
                            <th>Driver Name</th>
                            <th>Telephone</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($truckslist as $truck){
                        $tbl .= '
                            <tr>
                                <td>'.$truck->transporter_name.' </td>
                                <td>'.$truck->truck_no.'</td> 
                                <td>'.$truck->driver_name.' </td>
                                <td>'.$truck->driver_mobile_no.'</td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('truckslist'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function truckslist(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['truckslist'] = $this->reports_model->getListOfTrucks();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : List of Trucks';

            $this->loadViews("reports/truckslist", $this->global, $data, NULL);
        // }
    }
    
    function shippinglineslistpdf(){
        $this->load->model('reports_model');
        $shippinglines = $this->reports_model->getListOfShippinglines();
        
        if (sizeof($shippinglines) == 1) {
            $line = $shippinglines[0];
            // echo'<pre>'; print_r($client); 
        }
        else if (sizeof($shippinglines) > 1) {
            $shippinglines = $shippinglines;
        }
        
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
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong> Shipping Lines</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($shippinglines) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Shipper Name</th>
                        <th>Contact Paerson</th>
                        <th>Email</th>
                        <th>Telephone no.</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>Date Registered</th>
                    </tr>
                    <tr>
                        <td>'.$line->shipper_name.'</td> 
                        <td>'.$line->contact_person.' </td>
                        <td>'.$line->email.' </td>
                        <td>'.$line->telephone_no.' </td> 
                        <td>'.$line->address.' </td> 
                        <td>'.$line->country.' </td> 
                        <td>'.$line->created_at.' </td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($shippinglines) > 1){
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Shipper Name</th>
                            <th>Contact Paerson</th>
                            <th>Email</th>
                            <th>Telephone no.</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>Date Registered</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($shippinglines as $line){
                        $tbl .= '
                            <tr>
                                <td>'.$line->shipper_name.'</td> 
                                <td>'.$line->contact_person.' </td>
                                <td>'.$line->email.' </td>
                                <td>'.$line->telephone_no.' </td> 
                                <td>'.$line->address.' </td> 
                                <td>'.$line->country.' </td> 
                                <td>'.$line->created_at.' </td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('shippinglineslist'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function shippinglineslist(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['shippinglines'] = $this->reports_model->getListOfShippinglines();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : List of Shipping Lines';

            $this->loadViews("reports/shippinglineslist", $this->global, $data, NULL);
        // }
    }
    
    //Filter files and containers
    public function filterfilesandcontainers(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTPS Request:'; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Filtered Files & Containers';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/filesandcontainersfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredcontainerfilespdf(){
        // echo'<pre>HTTPS Request:'; print_r($_REQUEST); 
        // die;
        
        $startdate = trim(filter_input(INPUT_GET, 'startdate'));
        $enddate = trim(filter_input(INPUT_GET, 'enddate'));
        $filesncontainers = $this->reports_model->getFilesAndContainersByDate($startdate, $enddate);
        // echo'<pre>HTTPS FIles Request:'; print_r($filesncontainers); 
        // die;
        
        //$file
        if (sizeof($filesncontainers) == 1) {
            $file = $filesncontainers[0];
            // echo'<pre>'; print_r($client); 
        }
        else if (sizeof($filesncontainers) > 1) {
            $filesncontainers = $filesncontainers;
        }
        //echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($filesncontainers); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Files & Containers</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($filesncontainers) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Container No.</th>
                        <th>Date Received</th>
                        <th>Consignee</th>
                        <th>Consignment</th>
                    </tr>
                    <tr>
                        <td>'.$file->container_nr.'</td> 
                        <td>'.$file->date_received.' </td>
                        <td>'.$file->consignee_id.' </td>
                        <td>'.$file->consignement.' </td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($filesncontainers) > 1){
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Container No.</th>
                            <th>Date Received</th>
                            <th>Consignee</th>
                            <th>Consignment</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($filesncontainers as $file){
                        $tbl .= '
                            <tr>
                                <td>'.$file->container_nr.'</td> 
                                <td>'.$file->date_received.' </td>
                                <td>'.$file->consignee_id.' </td>
                                <td>'.$file->consignement.' </td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('filesandcontainers'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function filesandcontainerspdf(){
        $this->load->model('reports_model');
        $filesncontainers = $this->reports_model->getFilesAndContainers();
        
        //$file
        if (sizeof($filesncontainers) == 1) {
            $file = $filesncontainers[0];
            // echo'<pre>'; print_r($client); 
        }
        else if (sizeof($filesncontainers) > 1) {
            $filesncontainers = $filesncontainers;
        }
        //echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($filesncontainers); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Files & Containers</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        if (sizeof($filesncontainers) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Container No.</th>
                        <th>Date Received</th>
                        <th>Consignee</th>
                        <th>Consignment</th>
                    </tr>
                    <tr>
                        <td>'.$file->container_nr.'</td> 
                        <td>'.$file->date_received.' </td>
                        <td>'.$file->consignee_id.' </td>
                        <td>'.$file->consignement.' </td>  
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($filesncontainers) > 1){
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Container No.</th>
                            <th>Date Received</th>
                            <th>Consignee</th>
                            <th>Consignment</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($filesncontainers as $file){
                        $tbl .= '
                            <tr>
                                <td>'.$file->container_nr.'</td> 
                                <td>'.$file->date_received.' </td>
                                <td>'.$file->consignee_id.' </td>
                                <td>'.$file->consignement.' </td> 
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        
        // $tbl = '
        //     <style>
        //         tr:nth-child(even) {
        //             background-color: #dddddd;
        //         }
        //         table {
        //           font-family: arial, sans-serif;
        //           border-collapse: collapse;
        //           width: 100%;
        //         }
                
        //         td, th {
        //           border: 1px solid #dddddd;
        //           text-align: left;
        //         }
        //     </style>
        //     <table border="0" cellpadding="2" cellspacing="2" align="left" width="630">
        //       <thead  width="630">
        //           <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     
        //           </tr>
        //       </thead>
        //       <tbody>';
               
        //       foreach($filesncontainers as $c){
        //             $tbl .= '
        //                 <tr nobr="true"> 
                            
        //                 </tr>'
        //             ;
        //         } 
               
        //         $tbl .= '
        //             <br />
        //             <br />
        //         </tbody>
        //     </table>'
        // ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('filesandcontainers'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function filesandcontainers(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['filesncontainers'] = $this->reports_model->getFilesAndContainers();
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            // echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Files &amp; Containers';

            $this->loadViews("reports/filesandcontainers", $this->global, $data, NULL);
        // }
    }
    
    //Filter Trip Statements
    public function filtertripstatements(){
        if($this->isAdmin() == TRUE)
        // {
            $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTPS Request'; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Filtered Trip Statements';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/tripstatementsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredtripstatementspdf(){
        // echo'<pre>HTTPS Request'; print_r($_REQUEST); 
        // die;
        // $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'startdate'));
        $enddate = trim(filter_input(INPUT_GET, 'enddate'));
        // $this->load->model('reports_model');
        // $dailyreports = $this->reports_model->getDailyReportsByFilter($consignee, $startdate, $enddate);
        
        $interchangerecords = $this->reports_model->getInterchangeReportsByFilter($startdate, $enddate);
        // echo'<pre>'; print_r(($startdate)); 
        // echo'<pre>'; print_r(($enddate)); 
        // echo'<pre>HTTPS Interchange Request: '; print_r(($interchangerecords)); 
        // die;
        
        if (sizeof($interchangerecords) == 1) {
            $interch = $interchangerecords[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($interchangerecords) > 1) {
            $interchangerecords = $interchangerecords;
        }
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        // set page format (read source code documentation for further information)
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Daily Report. </strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        
        if (sizeof($interchangerecords) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Driver Name</th>
                        <th>Depot</th>
                        <th>Date Entered</th>
                        <th>Container No.</th>
                        <th>Interchange Date</th>
                        <th>Container Size</th>
                        <th>Truck Reg.</th>
                        <th>Shipping Line</th>
                    </tr>
                    <tr>
                        <td>'.$interch->driver_name.'</td>
                        <td>'.$interch->depot.'</td> 
                        <td>'.$interch->entry_date.'</td>
                        <td>'.$interch->container_no.'</td> 
                        <td>'.$interch->interchange_date.' </td>
                        <td>'.$interch->container_size.'</td> 
                        <td>'.$interch->truck_no.' </td>
                        <td>'.$interch->agent_shipping_line.'</td>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($interchangerecords) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Depot</th>
                            <th>Date Entered</th>
                            <th>Container No.</th>
                            <th>Interchange Date</th>
                            <th>Container Size</th>
                            <th>Truck Reg.</th>
                            <th>Shipping Line</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($interchangerecords as $interch){
                        $tbl .= '
                            <tr>
                                <td>'.$interch->depot.'</td> 
                                <td>'.$interch->entry_date.'</td>
                                <td>'.$interch->container_no.'</td> 
                                <td>'.$interch->interchange_date.' </td>
                                <td>'.$interch->container_size.'</td> 
                                <td>'.$interch->truck_no.' </td>
                                <td>'.$interch->agent_shipping_line.'</td>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        // $pdf->writeHTML($tbl, true, true, true, true, '');
        
        $pdf->Output('dailyreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function tripstatementspdf(){
        $this->load->model('reports_model');
        $tripstatements = $this->reports_model->getTransportExpenses();
        // tbl_transport_extra_fees
        $tripextrafees = $this->reports_model->getTransportExtraFees();
        $transporters = $this->reports_model->getListOfTransporters();
        // echo'<pre>'; print_r($tripstatements); //$tripextrafees
        // echo'<pre>'; print_r($tripextrafees); //$tripextrafees
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 21, false, false, 0);
        $pdf->Write(18, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 21, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Trips Statements</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
                #trip_tbl{
                    padding: 6px;
                }
                .border {
                	border:solid 1px #000;
                }
                .border-head {
                	border-bottom:solid 1px #000;
                }
                .border-right {
                	border-right:solid 1px #000;
                }
                .border-heading {
                    border-head: solid 1px #000;
                }
            </style>
            <table id="trip_tbl" border="0" cellpadding="2" cellspacing="0" align="left" width="100%"  class="border" >
                <thead  width="100%">
                    <tr class="border-head" nobr="true" style="background-color:#3c8dbc;color:#ffffff;">
                        <th width="15%" class="border-head border-heading">Transporter</th>
                        <th width="10%" class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                        <th class="border-head border-heading"></th>
                    </tr>
                </thead>
                <tbody>';
                
                foreach($transporters as $t){
                   $tbl .= '
                        <tr nobr="true">
                            <td width="15%" class="border-head border-right">'.$t->transporter_name.'</td>  
                            <td width="10%" class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">Transport Date</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">Vehicle No.</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">Consignee</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">Container No.</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">T810</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;">T812</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;" align="right">Clearing Charge</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;" align="right">Extra Paid</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;" align="right">Expenses</td>
                            <td class="border-head border-right"  style="background-color:#3c8dbc;color:#ffffff;" align="right">Total Payable</td>
                        </tr>';
                    
                    $totalAmountPaid = 0;    
                    foreach($tripstatements as $stmt){
                        if($stmt->transporter_id == $t->id){
                            $totalPayable = $stmt->clearing_charge + $stmt->extra_paid;
                            $tbl .= '
                                <tr nobr="true">
                                    <td width="15%" class="border-head border-right"></td>
                                    <td width="10%" class="border-head border-right">'.$stmt->transport_date.'</td>
                                    <td class="border-head border-right">'.$stmt->vehicle_number.'</td>
                                    <td class="border-head border-right">'.$stmt->consignee.'</td>
                                    <td class="border-head border-right">'.$stmt->container_no.'</td>
                                    <td class="border-head border-right">'.$stmt->t810_no.'</td>
                                    <td class="border-head border-right">'.$stmt->t812_no.'</td>
                                    <td align="right" class="border-head border-right">'.number_format($stmt->clearing_charge, 2).'</td>
                                    <td align="right" class="border-head border-right">'.number_format($stmt->extra_paid, 2).'</td>
                                    <td align="right" class="border-head border-right">'.number_format($stmt->expenses, 2).'</td>
                                    <td align="right" class="border-head border-right">'.number_format(($stmt->clearing_charge + $stmt->extra_paid), 2).'</td>
                                </tr>
                                
                                <tr nobr="true" border="2">
                                    <th width="15%" class="border-head border-right" style="background-color: #3c8dbc; color: #fff;" align="">Fee type</th>
                                    <th width="10%" class="border-head border-right" style="background-color: #3c8dbc; color: #fff;" align="">Fee details</th>
                                    <th class="border-head border-right" align="right" style="background-color: #3c8dbc; color: #fff;" align="right">Fee amount</th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                    <th class="border-head"></th>
                                </tr>
                            ';
                            
                            foreach($tripextrafees as $exp){
                                if($exp->file_no == $stmt->file_no){
                                    $totalAmountPaid = $totalAmountPaid + $exp->fee_amount;
                                    $tbl .= '
                                        <tr border="1" cellpadding="0" cellspacing="0">
                                            <td width="15%" class="border-head">'.$exp->fee_type.'</td>
                                            <td width="10%" class="border-head">'.$exp->fee_note.'</td>
                                            <td class="border-head border-right" align="right">'.number_format($exp->fee_amount, 2).'</td>
                                            <td class="border-head"></td>
                                            <td class="border-head"></td>
                                            <td class="border-head"></td>
                                            <td class="border-head"></td>
                                            <td class="border-head"></td> 
                                            <td class="border-head"></td>
                                            <td class="border-head"></td>
                                            <td class="border-head"></td>
                                        </tr>
                                    ';
                                }    
                            }
                            
                            $tbl .= '
                                <tr nobr="true">
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td> 
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                </tr>
                                <tr nobr="true">
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td> 
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head" align="right" style="background-color:#3c8dbc;color:#ffffff;">Balance Payable:</td>
                                    <td class="border-head" align="right">'.number_format(($totalPayable - $totalAmountPaid), 2).'</td>
                                </tr>
                                <tr nobr="true">
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td> 
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head" align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                                    <td class="border-head" align="right">'.number_format($totalAmountPaid, 2).'</td>
                                </tr>
                                <tr nobr="true">
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head" align="right" style="background-color:#3c8dbc;color:#ffffff;">Trips Total Agreed:</td>
                                    <td class="border-head" align="right">'.number_format($totalPayable, 2).'</td>
                                </tr>
                                <tr nobr="true">
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                    <td class="border-head"></td>
                                </tr>
                            ';
                        }
                    }    
                        
                    $tbl .='
                        <br/>
                        <br/>
                    ';
                    
               }
               
                $tbl .= '
                </tbody>
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('tripstmt'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function tripstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['tripstatements'] = $this->reports_model->getTransportExpenses();
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Trip Statements';

            $this->loadViews("reports/tripstatements", $this->global, $data, NULL);
        // }
    }
    
    //Filter receipt statements
    public function filterreceiptstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/receiptstatementsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredreceiptstatementspdf(){
        // echo'<pre>'; print_r($_REQUEST); 
        // die;
        // $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start_date'));
        $enddate = trim(filter_input(INPUT_GET, 'end_date'));
        
        $receiptstmts = $this->reports_model->getReceiptStatementsByDate($startdate, $enddate);
        // echo'<pre>'; print_r($receiptstmts); 
        // die;
        
        $totalAmount = 0;
        foreach($receiptstmts as $r){
            if(empty($r->amount)) $r->amount = 0;
            $totalAmount += $r->amount;
        }
        // echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($receiptstmts); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
                <h2 style="margin-left:33%;"><strong>Transaction Receipts</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" >
              <thead  width="630">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Receipt No.</th>
                     <th>Transaction Date</th>
                     <th>Payment Mode</th>
                     <th>Client</th>
                     <th>Transaction Details</th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
              
              $totalAmount = 0; 
              foreach($receiptstmts as $c){
                    $totalAmount = $c->amount + $totalAmount;
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->receipt_no.'</td> 
                            <td>'.$c->transaction_date.' </td>
                            <td>'.$c->payment_mode.' </td>
                            <td>'.$c->client.'</td> 
                            <td>'.$c->transaction_details.'</td>
                            <td align="right">'.number_format($c->amount, 2).'</td>
                        </tr>'
                    ;
                } 
                
               
                $tbl .= '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td nobr="true" style="background-color:#3c8dbc;color:#fff;" align="right">TOTAL AMOUNT:</td>
                        <td align="right">'.number_format($totalAmount,2).'</td>
                    </tr>
                </tbody>
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, true, false, false, '');
        
        $pdf->Output('receiptsstatements'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function receiptstatementspdf(){
        $this->load->model('reports_model');
        $receiptstmts = $this->reports_model->getReceiptStatements();
        
        $totalAmount = 0;
        foreach($receiptstmts as $r){
            if(empty($r->amount)) $r->amount = 0;
            $totalAmount += $r->amount;
        }
        // echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($receiptstmts); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
                <h2 style="margin-left:33%;"><strong>Transaction Receipts</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" >
              <thead  width="630">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Receipt No.</th>
                     <th>Transaction Date</th>
                     <th>Payment Mode</th>
                     <th>Client</th>
                     <th>Transaction Details</th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
              
              $totalAmount = 0; 
              foreach($receiptstmts as $c){
                    $totalAmount = $c->amount + $totalAmount;
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->receipt_no.'</td> 
                            <td>'.$c->transaction_date.' </td>
                            <td>'.$c->payment_mode.' </td>
                            <td>'.$c->client.'</td> 
                            <td>'.$c->transaction_details.'</td>
                            <td align="right">'.number_format($c->amount, 2).'</td>
                        </tr>'
                    ;
                } 
                
               
                $tbl .= '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td nobr="true" style="background-color:#3c8dbc;color:#fff;" align="right">TOTAL AMOUNT:</td>
                        <td align="right">'.number_format($totalAmount,2).'</td>
                    </tr>
                </tbody>
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, true, false, false, '');
        
        $pdf->Output('receiptsstatements'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function receiptstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['receiptstmts'] = $this->reports_model->getReceiptStatements();
            //echo'<pre>'; print_r($data); die;
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            // echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Transaction Receipts ';

            $this->loadViews("reports/receiptstatements", $this->global, $data, NULL);
        // }
    }
    
    //Filter voucher statements
    public function filtervoucherstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTPS REQUEST: '; print_r($_REQUEST); 
            // die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            // $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Filtered Voucher Statements';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/voucherstatementsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredvoucherstatementspdf(){
        // echo'<pre>HTTPS REQUEST: '; print_r($_REQUEST); 
        // die;
        // $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start_date'));
        $enddate = trim(filter_input(INPUT_GET, 'end_date'));
        $vouchertstmts = $this->reports_model->getVoucherStatementsByDate($startdate, $enddate); //getVoucherStatements
        // echo'<pre>HTTPS Vouchers REQUEST: '; print_r($vouchertstmts); 
        // die;
        
        $totalAmount = 0;
        foreach($vouchertstmts as $v){
            if(empty($v->amount)) $v->amount = 0;
            $totalAmount += $v->amount;
        }
        
        // echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($vouchertstmts); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Voucher Statements</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5">
              <thead>
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Voucher. No.</th>
                     <th>Payment date</th>
                     <th>Transporter</th>
                     <th>Payment Mode</th>
                     <th>Payment Details</th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
               
              foreach($vouchertstmts as $c){
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->voucher_no.'</td> 
                            <td>'.$c->payment_date.' </td>
                            <td>'.$c->transporter.' </td>
                            <td>'.$c->payment_mode.'</td> 
                            <td>'.$c->ref.'</td>
                            <td align="right">'.number_format($c->amount, 2).' </td>
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td nobr="true" style="background-color:#3c8dbc;color:#fff;">TOTAL AMOUNT:</td>
                        <td align="right">'.number_format($totalAmount, 2).'</td>
                    </tr>
                    <tr>
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
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('voucherstatements'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function voucherstatementspdf(){
        $this->load->model('reports_model');
        $vouchertstmts = $this->reports_model->getVoucherStatements();
        
        $totalAmount = 0;
        foreach($vouchertstmts as $v){
            if(empty($v->amount)) $v->amount = 0;
            $totalAmount += $v->amount;
        }
        
        // echo'<pre>'; print_r($data); 
        // echo'<pre>'; print_r($vouchertstmts); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Voucher Statements</strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5">
              <thead>
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>Voucher. No.</th>
                     <th>Payment date</th>
                     <th>Transporter</th>
                     <th>Payment Mode</th>
                     <th>Payment Details</th>
                     <th align="right">Amount</th>
                  </tr>
              </thead>
              <tbody>';
               
              foreach($vouchertstmts as $c){
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->voucher_no.'</td> 
                            <td>'.$c->payment_date.' </td>
                            <td>'.$c->transporter.' </td>
                            <td>'.$c->payment_mode.'</td> 
                            <td>'.$c->ref.'</td>
                            <td align="right">'.number_format($c->amount, 2).' </td>
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td nobr="true" style="background-color:#3c8dbc;color:#fff;">TOTAL AMOUNT:</td>
                        <td align="right">'.number_format($totalAmount, 2).'</td>
                    </tr>
                    <tr>
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
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('voucherstatements'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function voucherstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['vouchertstmts'] = $this->reports_model->getVoucherStatements();
            $data['transporters'] = $this->reports_model->getListOfTransporters();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Payment Voucher';

            $this->loadViews("reports/voucherstatements", $this->global, $data, NULL);
        // }
    }
    
    //Filter client statements
    public function filterclientstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTP Request: '; print_r($_REQUEST); 
            // die;
            $this->load->model('client_model');
            
            $data['client_name'] = trim(filter_input(INPUT_POST, 'tid'));
            $data['clients'] = $this->client_model->getClientList();
            // $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            // echo'<pre>HTTP Request: '; print_r($data['client_name']);  //client_name
            // die;
            
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/clientstatementfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredclientstatementspdf(){
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        //die;
        $this->load->model('client_model');
        $this->load->model('reports_model'); //tbl_transport_expense
        
        $client_name = trim(filter_input(INPUT_GET, 'client_name'));
        // echo'<pre>Client name HTTPS response: '; print_r($client_name);
        // die;
        
        // $clientlist = $this->client_model->getClientList();
        $clientlist = $this->reports_model->getCLientByName($client_name);
        $clientStmt = $this->reports_model->getDailyReports(); //get list of all documents
        $transextracharges = $this->reports_model->getTransporterExtraExpenses();
        // echo'<pre>Clients: '; print_r($clientlist);
        // echo'<pre>Statement: '; print_r($clientStmt);
        // echo'<pre>ExtraPayments: '; print_r($transextracharges); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>'.$client_name.' Statement</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                    <tr nobr="true" style="background-color:#3c8dbc;color:#ffffff;">
                        <th>Client</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
               
                    
                $totalAmountAgreed = 0;
                $totalAmountPaid = 0;
                $totalBalancePayable = 0;
               foreach($clientlist as $t){
                   $tbl .= '
                    <tr nobr="true">
                        <th>'.$t->client_name.'</th>
                        <th style="background-color:#3c8dbc;color:#ffffff;">Transaction</th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Paid <br /> </th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Agreed<br /> </th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Balance Payable<br /> </th>
                    </tr>
                    ';
                    
                    $clientTotalAmountAgreed = 0;
                    $clientTotalAmountPaid = 0;
                    $clientTotalBalancePayable = 0;
                    foreach($clientStmt as $s){
                        if($t->client_name == $s->client_id){
                            $totalAmountAgreed += ($s->clearing_charges + $s->extra_paid);
                            $totalAmountPaid += $s->amount_paid;
                            $totalBalancePayable += ($s->clearing_charges + $s->extra_paid) - $s->amount_paid;
                            
                            $clientTotalAmountAgreed += ($s->clearing_charges + $s->extra_paid);
                            $clientTotalAmountPaid += $s->amount_paid;
                            $clientTotalBalancePayable += ($s->clearing_charges + $s->extra_paid) - $s->amount_paid;
                            $tbl .= '
                                <tr nobr="true">
                                    <td></td>
                                    <td>'.$s->container_nr.'('.$s->consignement.')</td>
                                    <td align="right">'.number_format($s->amount_paid, 2).'</td>
                                    <td align="right">'.number_format($s->clearing_charges + $s->extra_paid, 2).'</td>
                                    <td align="right">'.number_format(($s->clearing_charges + $s->extra_paid) - $s->amount_paid, 2).'</td>
                                </tr>
                          ';
                        }
                    }
                    
                    $tbl .='  
                        <br />
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                            <td align="right">'.number_format($clientTotalAmountAgreed, 2).'</td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                            <td align="right">'.number_format($clientTotalAmountPaid, 2).'</td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                            <td align="right">'.number_format($clientTotalBalancePayable, 2).'</td>
                        </tr>
                        <tr nobr="true" align="right">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td></td>
                            <td align="right"></td>
                        </tr>
                    ';
                    
                    
               }
               
                $tbl .= '
                    <br />
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                        <td align="right">'.number_format($totalAmountAgreed, 2).'</td>
                    </tr>
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                        <td align="right">'.number_format($totalAmountPaid, 2).'</td>
                    </tr>
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                        <td align="right">'.number_format($totalBalancePayable, 2).'</td>
                    </tr>
                </tbody>
            </table>'
        ;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('clientstmts'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function clientstatementspdf(){
        $this->load->model('client_model');
        $this->load->model('reports_model'); //tbl_transport_expense
        
        $clientlist = $this->client_model->getClientList();
        $clientStmt = $this->reports_model->getDailyReports(); //get list of all documents
        $transextracharges = $this->reports_model->getTransporterExtraExpenses();
        // echo'<pre>Clients: '; print_r($clientlist);
        // echo'<pre>Statement: '; print_r($clientStmt);
        // echo'<pre>ExtraPayments: '; print_r($transextracharges); 
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
        $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);

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
        $pdf->Write(27, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(21, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Clients Statements</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                    <tr nobr="true" style="background-color:#3c8dbc;color:#ffffff;">
                        <th>Client</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
               
                    
                $totalAmountAgreed = 0;
                $totalAmountPaid = 0;
                $totalBalancePayable = 0;
               foreach($clientlist as $t){
                   $tbl .= '
                    <tr nobr="true">
                        <th>'.$t->client_name.'</th>
                        <th style="background-color:#3c8dbc;color:#ffffff;">Transaction</th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Paid <br /> </th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Agreed<br /> </th>
                        <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Balance Payable<br /> </th>
                    </tr>
                    ';
                    
                    $clientTotalAmountAgreed = 0;
                    $clientTotalAmountPaid = 0;
                    $clientTotalBalancePayable = 0;
                    foreach($clientStmt as $s){
                        if($t->client_name == $s->client_id){
                            $totalAmountAgreed += ($s->clearing_charges + $s->extra_paid);
                            $totalAmountPaid += $s->amount_paid;
                            $totalBalancePayable += ($s->clearing_charges + $s->extra_paid) - $s->amount_paid;
                            
                            $clientTotalAmountAgreed += ($s->clearing_charges + $s->extra_paid);
                            $clientTotalAmountPaid += $s->amount_paid;
                            $clientTotalBalancePayable += ($s->clearing_charges + $s->extra_paid) - $s->amount_paid;
                            $tbl .= '
                                <tr nobr="true">
                                    <td></td>
                                    <td>'.$s->container_nr.'('.$s->consignement.')</td>
                                    <td align="right">'.number_format($s->amount_paid, 2).'</td>
                                    <td align="right">'.number_format($s->clearing_charges + $s->extra_paid, 2).'</td>
                                    <td align="right">'.number_format(($s->clearing_charges + $s->extra_paid) - $s->amount_paid, 2).'</td>
                                </tr>
                          ';
                        }
                    }
                    
                    $tbl .='  
                        <br />
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                            <td align="right">'.number_format($clientTotalAmountAgreed, 2).'</td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                            <td align="right">'.number_format($clientTotalAmountPaid, 2).'</td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                            <td align="right">'.number_format($clientTotalBalancePayable, 2).'</td>
                        </tr>
                        <tr nobr="true" align="right">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td></td>
                            <td align="right"></td>
                        </tr>
                    ';
                    
                    
               }
               
                $tbl .= '
                    <br />
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                        <td align="right">'.number_format($totalAmountAgreed, 2).'</td>
                    </tr>
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                        <td align="right">'.number_format($totalAmountPaid, 2).'</td>
                    </tr>
                    <tr nobr="true">
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                        <td align="right">'.number_format($totalBalancePayable, 2).'</td>
                    </tr>
                </tbody>
            </table>'
        ;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('clientstmts'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function clientstatements(){
        // echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['receiptstmts'] = $this->reports_model->getReceiptStatements();
            
            
            $this->load->model('client_model');
            $data['clients'] = $this->client_model->getClientList();
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Client Statement ';

            $this->loadViews("reports/clientstatement", $this->global, $data, NULL);
        // }
    }
    
    //Filter transporter statements
    public function filtertransporterstatements(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
            // die;
            
            $data['transporter_name'] = trim(filter_input(INPUT_POST, 'tid'));
            $data['transporters'] = $this->reports_model->getListOfTransporters();
            // $data['clientlist'] = $this->reports_model->getListOfClients();
            
            // $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/transporterfilteredstatement", $this->global, $data, NULL);
        // }
    }
    public function filteredtransporterstatementspdf(){
        // echo'<pre>HTTPS request: '; print_r($_REQUEST);
        // die;
        
        $this->load->model('reports_model'); //tbl_transport_expense
        $this->load->model('transporter_model');
        
        $tname = trim(filter_input(INPUT_GET, 'transporter'));
        $transporters = $this->reports_model->getTransporterByName($tname); //getListOfTransporters()
        
        // echo'<pre>Transporter info request: '; print_r($transporter);
        // die;
        
        // $transporterStmt = $this->reports_model->getDailyReports(); //get list of all documents
        $transporterStmt = $this->transporter_model->getTranportersExpenseList();//getTranportersExpenseList();
        $transextracharges = $this->reports_model->getTransporterExtraExpenses();
        
        //Do the the sumations here
        //Get the payments from the payments table
        // $payments = $this->reports_model->getTransPayments();
        $this->load->model('payment_model');
        $payments = $this->payment_model->getAllVouchers();
        
        $allPays = $this->reports_model->getAllPayments();
        
        //Get transporters
        // echo'<pre>'; print_r($allPays);
        // echo'<pre>TransporterStmt: '; print_r($transporterStmt);
        // echo'<pre>Trip Expenses: '; print_r($transextracharges); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>'.$tname.' Statements</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                    <tr nobr="true" style="background-color:#3c8dbc;color:#ffffff;">
                        <th>Transporter</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
               
                    
                $totalAmountAgreed = 0;
                $totalAmountPaid = 0;
                $totalBalance = 0;
                $totalExpenses = 0;
                foreach($transporters as $t){
                   $tbl .= '
                        <tr nobr="true">
                            <td>'.$t->transporter_name.'</td> 
                            <th style="background-color:#3c8dbc;color:#ffffff;">Consignee</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Container No. </th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Consignment</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Date</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Agreed</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Expenses</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Paid</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Balance</th>
                        </tr>
                    ';
                    foreach($transporterStmt as $s){
                        if($t->id == $s->transporter_id){
                            $totalAmountAgreed += $s->clearing_charge + $s->extra_paid;
                            $totalAmountPaid += ($s->clearing_charge + $s->extra_paid) - $s->balance;
                            $totalBalance += $s->balance;
                            $totalExpenses += $s->expenses;
                            $tbl .= '
                                <tr nobr="true">
                                    <td></td>
                                    <td>'.$s->consignee.'</td>
                                    <td>'.$s->container_no.'</td>
                                    <td>'.$s->consignment.' </td>
                                    <td>'.$s->transport_date.'</td>
                                    <td align="right">'.number_format(($s->clearing_charge + $s->extra_paid), 2).'</td>
                                    <td align="right">'.number_format($s->expenses, 2).'</td>
                                    <td align="right">'.number_format(($s->clearing_charge + $s->extra_paid) - $s->balance, 2).'</td>
                                    <td align="right">'.number_format($s->balance, 2).'</td>
                                </tr>
                           ';
                        }
                    }
                    
                    $tbl .= '
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
               }
               
                $tbl .= '
                    <br />
                    <br />
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                            <td align="right">'.number_format($totalAmountAgreed, 2).'</td>
                            <td></td>
                            <td></td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td> </td>
                            <td> </td> 
                            <td></td>
                            <td></td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Expenses:</td>
                            <td></td>
                            <td align="right">'.number_format($totalExpenses, 2).'</td>
                            <td></td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td> </td>
                            <td> </td> 
                            <td></td>
                            <td></td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                            <td></td>
                            <td></td>
                            <td align="right">'.number_format($totalAmountPaid, 2).'</td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right">'.number_format($totalBalance, 2).'</td>
                        </tr>
                </tbody>
            </table>'
        ;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('transporterstmts'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function transporterstatementspdf(){
        $this->load->model('reports_model'); //tbl_transport_expense
        $this->load->model('transporter_model');
        
        $transporters = $this->reports_model->getListOfTransporters();
        // $transporterStmt = $this->reports_model->getDailyReports(); //get list of all documents
        $transporterStmt = $this->transporter_model->getTranportersExpenseList();//getTranportersExpenseList();
        $transextracharges = $this->reports_model->getTransporterExtraExpenses();
        
        //Do the the sumations here
        //Get the payments from the payments table
        // $payments = $this->reports_model->getTransPayments();
        $this->load->model('payment_model');
        $payments = $this->payment_model->getAllVouchers();
        
        $allPays = $this->reports_model->getAllPayments();
        
        //Get transporters
        // echo'<pre>'; print_r($allPays);
        // echo'<pre>TransporterStmt: '; print_r($transporterStmt);
        // echo'<pre>Trip Expenses: '; print_r($transextracharges); 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(24, '', '', 0, 'C', true, 9, false, false, 0);
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Transporter Statements</strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $tbl = '
            <style>
            </style>
            <table border="1" cellpadding="5" cellspacing="0" align="left" width="100%">
                <thead  width="100%">
                    <tr nobr="true" style="background-color:#3c8dbc;color:#ffffff;">
                        <th>Transporter</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
               
                    
                $totalAmountAgreed = 0;
                $totalAmountPaid = 0;
                $totalBalance = 0;
                $totalExpenses = 0;
                foreach($transporters as $t){
                   $tbl .= '
                        <tr nobr="true">
                            <td>'.$t->transporter_name.'</td> 
                            <th style="background-color:#3c8dbc;color:#ffffff;">Consignee</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Container No. </th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Consignment</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;">Date</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Amount Agreed</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Expenses</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Paid</th>
                            <th style="background-color:#3c8dbc;color:#ffffff;" align="right">Balance</th>
                        </tr>
                    ';
                    foreach($transporterStmt as $s){
                        if($t->id == $s->transporter_id){
                            $totalAmountAgreed += $s->clearing_charge + $s->extra_paid;
                            $totalAmountPaid += ($s->clearing_charge + $s->extra_paid) - $s->balance;
                            $totalBalance += $s->balance;
                            $totalExpenses += $s->expenses;
                            $tbl .= '
                                <tr nobr="true">
                                    <td></td>
                                    <td>'.$s->consignee.'</td>
                                    <td>'.$s->container_no.'</td>
                                    <td>'.$s->consignment.' </td>
                                    <td>'.$s->transport_date.'</td>
                                    <td align="right">'.number_format(($s->clearing_charge + $s->extra_paid), 2).'</td>
                                    <td align="right">'.number_format($s->expenses, 2).'</td>
                                    <td align="right">'.number_format(($s->clearing_charge + $s->extra_paid) - $s->balance, 2).'</td>
                                    <td align="right">'.number_format($s->balance, 2).'</td>
                                </tr>
                           ';
                        }
                    }
                    
                    $tbl .= '
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
               }
               
                $tbl .= '
                    <br />
                    <br />
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Agreed:</td>
                            <td align="right">'.number_format($totalAmountAgreed, 2).'</td>
                            <td></td>
                            <td></td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td> </td>
                            <td> </td> 
                            <td></td>
                            <td></td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Expenses:</td>
                            <td></td>
                            <td align="right">'.number_format($totalExpenses, 2).'</td>
                            <td></td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td> </td>
                            <td> </td> 
                            <td></td>
                            <td></td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Amount Paid:</td>
                            <td></td>
                            <td></td>
                            <td align="right">'.number_format($totalAmountPaid, 2).'</td>
                            <td> </td>
                        </tr>
                        <tr nobr="true">
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td align="right" style="background-color:#3c8dbc;color:#ffffff;">Total Balance:</td>
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td align="right">'.number_format($totalBalance, 2).'</td>
                        </tr>
                </tbody>
            </table>'
        ;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('transporterstmts'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function transporterstatements(){
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            $data['receiptstmts'] = $this->reports_model->getReceiptStatements();
            $data['transporters'] = $this->reports_model->getListOfTransporters();
            // echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Transporter Statement ';

            $this->loadViews("reports/transporterstatement", $this->global, $data, NULL);
        // }
    }
    
    //Bond report: It shows bond name, containerno, consignment. Date , released , value and total.
    public function filterbondreports(){
        // if($this->isAdmin() == TRUE)
        // {
            // $this->loadThis();
        // }
        // else
        // {
            //echo'<pre>'; print_r($_REQUEST); die;
            //filtereddailyreports
            // [start_date] => 02/01/2020
            // [end_date] => 02/19/2020
            $data['consignee'] = trim(filter_input(INPUT_POST, 'consignee'));
            $data['startdate'] = trim(filter_input(INPUT_POST, 'start_date'));
            $data['enddate'] = trim(filter_input(INPUT_POST, 'end_date'));
            
            $data['clientlist'] = $this->reports_model->getListOfClients();
            
            $data['interchangerecords'] = $this->reports_model->getInterchangeReport();
            $this->global['pageTitle'] = 'Amey Trading : Daily Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/dailyreportsfiltered", $this->global, $data, NULL);
        // }
    }
    public function filteredbondreportspdf(){
        $consignee = trim(filter_input(INPUT_GET, 'con'));
        $startdate = trim(filter_input(INPUT_GET, 'start'));
        $enddate = trim(filter_input(INPUT_GET, 'end'));
        $this->load->model('reports_model');
        $dailyreports = $this->reports_model->getDailyReportsByFilter($consignee, $startdate, $enddate);
        
        // echo'<pre>'; print_r(sizeof($dailyreports));
        // echo'<pre>';print_r($_REQUEST); 
        // echo'<pre>';print_r($dailyreports);
        if (sizeof($dailyreports) == 1) {
            $dailyrep = $dailyreports[0];
            // echo'<pre>'; print_r($dailyrep); 
        }
        else if (sizeof($dailyreports) > 1) {
            $dailyreports = $dailyreports;
        }
        // die; 
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

        // Add a page
        // This method has several options, check the source code documentation for more information.
        // set page format (read source code documentation for further information)
        $pdf->AddPage();
        
        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $pdf->Write(27, '', '', 0, 'C', true, 21, false, false, 0);
        $pdf->Write(18, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 21, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h3 style="margin-left:33%;"><strong>Daily Report. </strong></h3>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        if (sizeof($dailyreports) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Date Received</th>
                        <th  >Consignee</th>
                        <th  >Container nr.</th>
                        <th  >Size</th>
                        <th  >ETA</th>
                        <th  >Charges</th>
                        <th  >D.O. Status</th>
                        <th  >Down</th>
                        <th  >Car Reg</th>
                        <th  >Gate Out</th>
                    </tr>
                    <tr>
                        <td>'.$dailyrep->date_received.'</td>
                        <td>'.$dailyrep->consignee_id.'</td>
                        <th>'.$dailyrep->container_nr.'</th>
                        <th>'.$dailyrep->container_size.'</th>
                        <th>'.$dailyrep->eta_ata.'</th>
                        <th>'.$dailyrep->charges.'</th>
                        <th>'.$dailyrep->do_status.'</th>
                        <th>'.$dailyrep->down.'</th>
                        <th>'.$dailyrep->car_reg.'</th>
                        <th>'.$dailyrep->gate_out.'</th>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($dailyreports) > 1){
            $tbl = '
                <style>
                    
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Date Received</th>
                            <th  >Consignee</th>
                            <th  >Container nr.</th>
                            <th  >Size</th>
                            <th  >ETA</th>
                            <th  >Charges</th>
                            <th  >D.O. Status</th>
                            <th  >Down</th>
                            <th  >Car Reg</th>
                            <th  >Gate Out</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($dailyreports as $dailyrep){
                        $tbl .= '
                            <tr>
                                <td>'.$dailyrep->date_received.'</td>
                                <td>'.$dailyrep->consignee_id.'</td>
                                <th>'.$dailyrep->container_nr.'</th>
                                <th>'.$dailyrep->container_size.'</th>
                                <th>'.$dailyrep->eta_ata.'</th>
                                <th>'.$dailyrep->charges.'</th>
                                <th>'.$dailyrep->do_status.'</th>
                                <th>'.$dailyrep->down.'</th>
                                <th>'.$dailyrep->car_reg.'</th>
                                <th>'.$dailyrep->gate_out.'</th>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        // $pdf->writeHTML($tbl, true, true, true, true, '');
        
        $pdf->Output('dailyreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    public function bondreportspdf(){
        $this->load->model('bond_model');
        $bondsreport = $this->bond_model->getAllServicedBonds(); 
        // echo'<pre>Bonds Reports HTTPS Response: '; print_r($bondsreport); 
        // die;
        if (sizeof($bondsreport) == 1) {
            $bondrep = $bondsreport[0];
            // echo'<pre>'; print_r($bondrep); 
        }
        else if (sizeof($bondsreport) > 1) {
            $bondsreport = $bondsreport;
        }
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
        $pdf->Write(27, '', '', 0, 'C', true, 21, false, false, 0);
        $pdf->Write(18, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 21, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h2 style="margin-left:33%;"><strong>Bond Reports </strong></h2>
        <hr>
        ';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        // $this->setCellHeightRatio(2); //
        // bond name, containerno, consignment. Date , released , value and total.
        if (sizeof($bondsreport) == 1) {
            $tbl = '
                <style>
                </style>
                <table border = "1" cellpadding = "5">
                    <tr style="background-color:#3c8dbc;color:#fff;">
                        <th>Bond name</th>
                        <th>Container no</th>
                        <th>Consignment</th>
                        <th>Date Released</th>
                        <th align="right">Value</th>
                    </tr>
                    <tr>
                        <th>'.$bondrep->bond_name.'</th>
                        <th>'.$bondrep->container_no.'</th>
                        <th>'.$bondrep->consignment.'</th>
                        <th>'.$bondrep->date_released.'</th>
                        <th align="right">'.number_format($bondrep->value, 2).'</th>
                    </tr>
                </table>
                '
            ;
        }
        else if(sizeof($bondsreport) > 1){
            $tbl = '
                <style>
                </style>
                    <table border = "1" cellpadding = "5">
                        <tr style="background-color:#3c8dbc;color:#fff;">
                            <th>Bond name</th>
                            <th>Container no</th>
                            <th>Consignment</th>
                            <th>Date Released</th>
                            <th align="right">Value</th>
                        </tr>
                      </thead>
                      <tbody>';
                   
                  foreach($bondsreport as $bondrep){
                        $tbl .= '
                            <tr>
                                <th>'.$bondrep->bond_name.'</th>
                                <th>'.$bondrep->container_no.'</th>
                                <th>'.$bondrep->consignment.'</th>
                                <th>'.$bondrep->date_released.'</th>
                                <th align="right">'.number_format($bondrep->value, 2).'</th>
                            </tr>'
                        ;
                    } 
                   
                    $tbl .= '
                        <br />
                        <br />
                    </tbody>
                </table>'
            ;
            // //echo $tbl;
        }
        else {
            $tbl = 'No records found';
        }
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        // ob_clean();
        ob_start();
        $pdf->Output('bondreport'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
        ob_end_flush();
    }
    public function bondreports(){
        // echo'<pre>HTTPS Request: '; print_r($_REQUEST); 
        // die;
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('bond_model');
            $data['bonds'] = $this->bond_model->getAllServicedBonds();
            
            $this->global['pageTitle'] = 'Amey Trading : Bond Reports';
            
            //$this->pdf->Output( 'page.pdf' , 'I' );
            $this->loadViews("reports/bondsreport", $this->global, $data, NULL);
        }
    }
    
    //TODO: VERSION TWO
    function releasedcargopdf(){
        $this->load->model('reports_model');
        $clientlist = $this->reports_model->getListOfClients();
        //echo'<pre>'; print_r($data); 
        echo'<pre>'; print_r($clientlist); 
        die;
        
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
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h1 style="margin-left:33%;"><strong>CUSTOMER JOBS</strong></h1>
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
            <table border="0" cellpadding="2" cellspacing="2" align="left" width="630">
              <thead  width="630">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>File No.</th>
                     <th>Date Received</th>
                     <th>Consignee</th>
                     <th>Shipping Line</th>
                     <th>BL No.</th>
                     <th>Cargo Type</th>
                     <th>Client</th>
                  </tr>
              </thead>
              <tbody>';
               
              foreach($clientlist as $c){
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->file_no.'</td> 
                            <td>'.$c->date_received.' </td>
                            <td>'.$c->consignee_id.'</td> 
                            <td>'.$c->shipping_line.' </td>
                            <td>'.$c->bill_of_landing.'</td> 
                            <td>'.$c->cargo_type.' </td>
                            <td>'.$c->client_id.'</td>  
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <br />
                    <br />
                </tbody>
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('customerjobs'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function releasedcargo(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $data['releasedcargo'] = $this->reports_model->getReleasedCargo();
            echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Payment Voucher';

            $this->loadViews("reports/interchange", $this->global, $data, NULL);
        }
    }
    
    function cargotransferpdf(){
        $this->load->model('reports_model');
        $clientlist = $this->reports_model->getListOfClients();
        //echo'<pre>'; print_r($data); 
        echo'<pre>'; print_r($clientlist); 
        die;
        
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
        $pdf->Write(24, '7th Floor , Cannon II Tower, Moi Avenue, Mombasa Kenya', '', 0, 'C', true, 9, false, false, 0);
        $pdf->SetFont('helvetica', '', 6);

        // Print text using writeHTMLCell()// Set some content to print
        $html = '
            <h1 style="margin-left:33%;"><strong>CUSTOMER JOBS</strong></h1>
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
            <table border="0" cellpadding="2" cellspacing="2" align="left" width="630">
              <thead  width="630">
                  <tr nobr="true" style="background-color:#3c8dbc;color:#fff;">
                     <th>File No.</th>
                     <th>Date Received</th>
                     <th>Consignee</th>
                     <th>Shipping Line</th>
                     <th>BL No.</th>
                     <th>Cargo Type</th>
                     <th>Client</th>
                  </tr>
              </thead>
              <tbody>';
               
              foreach($clientlist as $c){
                    $tbl .= '
                        <tr nobr="true"> 
                            <td>'.$c->file_no.'</td> 
                            <td>'.$c->date_received.' </td>
                            <td>'.$c->consignee_id.'</td> 
                            <td>'.$c->shipping_line.' </td>
                            <td>'.$c->bill_of_landing.'</td> 
                            <td>'.$c->cargo_type.' </td>
                            <td>'.$c->client_id.'</td>  
                        </tr>'
                    ;
                } 
               
                $tbl .= '
                    <br />
                    <br />
                </tbody>
            </table>'
        ;
        // //echo $tbl;
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
        $pdf->Output('customerjobs'.strtotime(date("Y-m-d H:i:s")).'.pdf', 'I'); //
    }
    function cargotransfer(){
        echo'<pre>'; print_r($_REQUEST); die;
    }
}