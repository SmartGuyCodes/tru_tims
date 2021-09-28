<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reports_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Reports_model extends CI_Model{   
    function getAllPayments(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments ');
            
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp; 
    }
    
    function getDailyReports(){
        try{
            $date = new DateTime("now");
            //$curr_date = $date->format('Y-m-d ');
            
            $this->db->select('*');
            $this->db->from('tbl_document_manager ');
            // WHERE DATE(`timestamp`) = CURDATE()
            // $this->db->where('created_at', DATE(NOW()));
            // $this->db->where("(created_at >= now())");
            //$this->db->where('DATE(created_at)',$curr_date);//use date function
            
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    function getDailyReportsByFilter($consignee, $startdate, $enddate){
        try{
            $date = new DateTime("now");
            //$curr_date = $date->format('Y-m-d '); date_received, consignee_id
            
            $this->db->select('*');
            $this->db->from('tbl_document_manager ');
            //"SELECT * FROM TABLE WHERE date BETWEEN '$startDate' AND '$endDate'"
            if(empty($consignee)){
                $where = "date_received >='$startdate' AND date_received <='$enddate'";
            }
            else{
                $where = "consignee_id='$consignee' AND date_received >='$startdate' AND date_received <='$enddate'";
            }
            //$where = "date_received >='$startdate' AND date_received <='$enddate'";
            $this->db->where($where);
            
            
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp; 
    }
    
    function getInterchangeReportsByFilter($startdate, $enddate){
        try{
            $this->db->select('*');
            $this->db->from('tbl_interchange');
            $where = "entry_date  >='$startdate' AND entry_date  <='$enddate'";
            $this->db->where($where);
            $query = $this->db->get();
    
            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp; 
    }
    
    //Get all Vouchers
    function getInterchangeReport(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_interchange ');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //customer jobs
    function getCustomerJobsReport(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager ');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    function getFilteredCustomerJobsReport($startdate, $enddate){
        try{
            $this->db->select('*');
            $where = "date_received >='$startdate' AND date_received <='$enddate'";
            $this->db->from('tbl_document_manager');
            $this->db->where($where);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //List of clients
    function getListOfClients(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_clients');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    //List of Transporters
    function getListOfTransporters(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transporter');
            $query = $this->db->get();
            
            // $this->db->select('tbl_transporter.*, count(tbl_trucks.*) AS trucks');
            // $this->db->from('tbl_transporter');
            // $this->db->join('tbl_trucks', 'tbl_trucks.transporter_id = tbl_transporter.transporter_id');
            // $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    //List of Trucks
    function getListOfTrucks(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_trucks');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    //List of Shipping Lines
    function getListOfShippinglines(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_shippingline');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;        
    }
    
    
    
    //List of files and containers 
    function getTransporterStatement(){
        try{
            $this->db->select('dm.*');
            $this->db->from('tbl_document_manager dm');
            // $this->db->join('tbl_transport_extra_fees tex', 'tex.file_no = dm.file_no'); // 	file_no 
            // $this->db->join('tbl_transport_expense d', 'd.file_no = dm.file_no'); 
            // $this->db->group_by("transporter_name");
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransporterExtraExpenses(){
        try{
            $this->db->select('tex.*');
            $this->db->from('tbl_transport_extra_fees tex');
            // $this->db->join('tbl_transport_extra_fees tex', 'tex.file_no = d.file_no'); // 	file_no
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //List of files and containers
    function getFilesAndContainers(){
        try{
            $this->db->select('d.file_no, d.container_nr, d.date_received, d.consignee_id, d.consignement');
            $this->db->from('tbl_document_manager d');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getFilesAndContainersByDate($startdate, $enddate){
        try{
            $this->db->select('d.file_no, d.container_nr, d.date_received, d.consignee_id, d.consignement');
            // $this->db->from('tbl_document_manager d');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
            $where = "date_received >='$startdate' AND date_received <='$enddate'";
            $this->db->from('tbl_document_manager d');
            $this->db->where($where); //getReceiptStatementsByDate
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    
    //List of Transport Expense tbl_transport_expense
    function getTransportExpenses(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransportExtraFees(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_extra_fees');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    // tbl_transporter_pay
    function getTransPayments(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transporter_pay');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getReceiptStatements(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_receipt');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getReceiptStatementsByDate($startdate, $enddate){
        try{
            $this->db->select('*');
            $where = "transaction_date >='$startdate' AND transaction_date <='$enddate'";
            $this->db->from('tbl_receipt');
            $this->db->where($where);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getVoucherStatements(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_voucher');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    
    function getVoucherStatementsByDate($startdate, $enddate){
        try{
            $this->db->select('*');
            $where = "payment_date >='$startdate' AND payment_date <='$enddate'";
            $this->db->from('tbl_voucher');
            $this->db->where($where);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getReleasedCargo(){
        try{
            // $this->db->select('d.file_no, d.date_received, d.consignee_id, d.consignement');
            $this->db->select('d.*');
            $this->db->from('tbl_document_manager d');
            // $this->db->join('tbl_containers', 'c.file_no = d.file_no');
             
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransporterByName($tname){
        try{
            $this->db->select('*');
            $this->db->where('transporter_name =', $tname);
            $this->db->from('tbl_transporter');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp; 
    } 
    
    function getCLientByName($client_name){
        try{
            $this->db->select('*');
            $this->db->from('tbl_clients');
            $this->db->where('client_name', $client_name);
            $query = $this->db->get();
            
            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
}
