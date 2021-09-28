<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Payment_model extends CI_Model{   
    
    //1. Vouchers
    //Insert new voucher
    function insertNewVoucher($voucherInfo){
        try{
            $this->db->trans_start();
            $this->db->insert('tbl_voucher ', $voucherInfo);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
            
            $resp = $insert_id;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //INsert new transporter payment
    function insertNewPayment($paymentInfo, $voucherInfo, $container_no, $amount, $payable_balance){ //insertNewPayment($paymentInfo, $payInfo, $payInfo['container_no'], $payInfo['amount'], $payable_balance)
        try{
            $this->db->trans_start();
            
            $this->db->insert('tbl_voucher', $voucherInfo);
            $insert_id = $this->db->insert_id();
            
            //insert new payment into the payment table
            $this->db->insert('tbl_payments', $paymentInfo);
            $insert_id = $this->db->insert_id();
            
            //now update file expense by adding to the expense
            // $this->db->set('balance', $amount, FALSE);
            // $this->db->where('file_no', $file_no);
            // $this->db->update('tbl_document_manager'); 
            
            //deduct this from the agreed transporter payment //
            $this->db->set('advance', 'advance+'.$amount, FALSE);
            $this->db->set('balance', $payable_balance);
            $this->db->where('container_no', $container_no);
            $this->db->update('tbl_transport_expense'); 
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //Get all Vouchers
    function getAllVouchers(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_voucher');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //Get Voucher Info by id
    function getVoucherProfile($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_voucher');
            $this->db->where('id =', $id);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    //Delete voucher
    function removeVoucherProfile($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_voucher');
        
        //Get voucher file_no
        
        //update the transporter balance by adding the 
        
        
            
        //now update file expense by adding to the expense
        // $this->db->set('balance', $amount, FALSE);
        // $this->db->where('file_no', $file_no);
        // $this->db->update('tbl_document_manager'); 
        
        //insert new payment into the payment table
        // $this->db->insert('tbl_payments', $paymentInfo);
        // $insert_id = $this->db->insert_id();
        
        return TRUE;
    }
    
    //2. Receipts
    //Insert new Receipt
    function insertNewReceipt($receiptInfo, $amountPaid, $balancePayable, $container_no, $paymentInfo){//insertNewReceipt($receiptInfo, $amountPaid, $balancePayable, $container_nr, $paymentInfo)
        try{
            $this->db->trans_start();
            
            //update the document
            $this->db->set('amount_paid', $amountPaid);
            $this->db->set('balance', $balancePayable);
            $this->db->where('container_nr', $container_no);
            $this->db->update('tbl_document_manager');
            
            //Now insert the payment receipt
            $this->db->insert('tbl_receipt', $receiptInfo);
            $insert_id = $this->db->insert_id();
            
            //Update the payments table
            //insert new payment into the payment table
            $this->db->insert('tbl_payments', $paymentInfo);
            $insert_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //Get all Receipts
    function getAllReceipts(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_receipt');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //Get Receipt Info by id
    function getReceiptProfile($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_receipt');
            $this->db->where('id =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    //Get advance payment....
    function getClientDepositPayment($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('id =', $id);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function deleteDepositPayment($new_amount_paid, $new_balance, $pay_id, $doc_id){
        $this->db->trans_start();
        
        //Update clearing document
        //Add amount paid to the doc_manager_tbl
        $this->db->set('amount_paid', $new_amount_paid);
        $this->db->set('balance', $new_balance);
        $this->db->where('id', $doc_id);
        $this->db->update('tbl_document_manager');
        
        //delete the payment via payment id
        $this->db->where('id', $pay_id);
        $this->db->delete('tbl_payments');
        
        $this->db->trans_complete();
        
        return TRUE;
    }
    
    //Delete Receipt
    function removeReceiptProfile($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_receipt');
        
        return TRUE;
    }
}
