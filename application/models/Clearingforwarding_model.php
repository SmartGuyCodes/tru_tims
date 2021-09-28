<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Clearingforwarding_model extends CI_Model{   
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id addNewTransporter
     */
    function addNewFee($feeInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_fees', $feeInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    function getFees(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_fees');
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function deleteFee($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_fees');
        
        return TRUE;
    }
    
    //tbl_accounts
    function addNewAccount($accInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_accounts', $accInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    function getAccounts(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_accounts');
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function deleteAccount($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_accounts');
        
        return TRUE;
    }
    
    //tbl_invoices
    function getInvoiceRecords(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoices');
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getInvoiceRecord($invoiceid){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoices');
            $this->db->where('id =', $invoiceid);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function deleteInvoiceRecord($id, $invoiceNo){
        try{
            $this->db->trans_start();
            
            $this->db->where('id', $id);
            $this->db->delete('tbl_invoices');
            
            $this->db->where('invoice_no', $invoiceNo);
            $this->db->delete('tbl_invoice_items');
            
            $this->db->trans_complete();
            
            $result = TRUE;
        }
        catch(PDOException $e){
            $result = $e->getMessage();   
        }
        
        return $result;
    }
    
    function addNewInvoice($invoiceInfo){
        $this->db->trans_start();
        $this->db->insert(' tbl_invoices', $invoiceInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    function getInvoiceInfo($invoice_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoices');
            $this->db->where('invoice_no =', $invoice_no);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function addNewInvoiceItem($itemInfo, $amount, $invNo){
        $this->db->trans_start();
        $this->db->insert(' tbl_invoice_items', $itemInfo);
        $insert_id = $this->db->insert_id();
        
        //Add total amount to the invoice table too
        //Do an increment of total amount to the invoice records...
        $this->db->set('amount', 'amount+'.$amount, FALSE);
        $this->db->where('invoice_no', $invNo);
        $this->db->update('tbl_invoices'); 
        
        $this->db->trans_complete();
        
        return TRUE;
    }
    
    function removeInvoiceItem($itemId, $invoiceNo, $itemAmount){
        try{
            $this->db->trans_start();
            
            $this->db->where('id', $itemId);
            $this->db->delete('tbl_invoice_items');
            
            $this->db->set('amount', 'amount-'.$itemAmount, FALSE);
            $this->db->where('invoice_no', $invoiceNo);
            $this->db->update('tbl_invoices'); 
            
            $this->db->trans_complete();
            
            $result = TRUE;
        }
        catch(PDOException $e){
            $result = $e->getMessage();   
        }
        
        return $result;
    }
    
    function getInvoiceItems($invoice_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoice_items');
            $this->db->where('invoice_no =', $invoice_no);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getInvoiceItem($itemId){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoice_items');
            $this->db->where('id =', $itemId);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getInvoices(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_invoices');
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function addNewContainer($containerInfo)
    {
        $this->db->trans_start();
        $this->db->insert(' tbl_containers', $containerInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    function getDocumentInfo($docID){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            $this->db->where(' 	id =', $docID);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getDocumentContainers($file_no){
        try{
            $this->db->select('*');
            $this->db->from(' tbl_containers');
            $this->db->where('file_no  =', $file_no);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
            $result = $e->getMessage();
        }

        return $result;
    }
    
    /**
     * Get documents list
     * 
     * */
     public function getDocs(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            #$this->db->where('roleId !=', 1);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
     }
    
    
    /**
     * This function is used to update the file information
     */
    function editDocumentInfo($documentInfo, $docID)
    {
        $this->db->where('id', $docID);
        $this->db->update('tbl_document_manager', $documentInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteTransporter($userId, $transporterInfo)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_document_manager', $transporterInfo);
        
        return $this->db->affected_rows();
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getTransporterInfoById($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_document_manager');
        //$this->db->where('isDeleted', 0);
        $this->db->where('id', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
}
