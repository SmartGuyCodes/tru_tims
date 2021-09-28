<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transporter_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Transporter_model extends CI_Model{   
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id addNewTransporter
     */
    function addNewTransporter($transporterInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_transporter', $transporterInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }  
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id addNewTransporter
     */
    function addNewTruck($truckInfo)
    {
       try{
            $this->db->trans_start();
            $this->db->insert('tbl_trucks', $truckInfo);
            
            $insert_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
       }
       catch(PDOException $e){
           $resp = $e->getMessage();
       }
       
       return $resp;
    }
    
    function getTranportersExpenseList(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTranporterExpense($container_no){ //getTranporterExpense($data['container_no'])
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $this->db->where('container_no=', $container_no);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTranporterExpenseListByTrucks($truck_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $this->db->where('truck_no =', $truck_no);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTranporterExpenseList($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $this->db->where('transporter_id =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTranporterPayments($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('transporter_id  =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransportExpense($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $this->db->where('id =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTruckInfoByID($truck_id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_trucks');
            $this->db->where('id =', $truck_id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function updateTruckByID($truck_id, $truckInfo){
        try{
            // $this->db->trans_start();
            
            //Update balance in the transport_expense table
            // $this->db->set('balance', $totalbalance);
            // $this->db->where('id', $truck_id);
            // $this->db->update(' tbl_trucks', $truckInfo);
            
            // $this->db->trans_complete();
            
            $this->db->where('id', $truck_id);
            $this->db->update('tbl_trucks', $truckInfo);
        
            $resp = $this->db->affected_rows();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function deleteTruckByID($truck_id){
        $this->db->where('id', $truck_id);
        $this->db->delete('tbl_trucks');
        
        return TRUE ;
    }
    
    function addNewTransporterExtraExpense($expenseInfo, $container_no, $paymentInfo, $totalbalance){ //addNewTransporterExtraExpense($expenseInfo, $container_no, $paymentInfo, $newBalance)
        try{
            $this->db->trans_start();
            
            //Update balance in the transport_expense table
            $this->db->set('balance', $totalbalance);
            $this->db->where('container_no', $container_no);
            $this->db->update('tbl_transport_expense');
            
            $this->db->insert('tbl_transport_extra_fees', $expenseInfo);
            
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
    
    //tbl_payments
    function getTransporterAdvances($transporterId){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('transporter_id =', $transporterId);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransporteExtraFeeByTruck($truck_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_extra_fees');
            $this->db->where('truck_no =', $truck_no);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getTransporteExtraFee($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_extra_fees');
            $this->db->where('transporter_id =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function removeTransporterExtraExpense($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_transport_extra_fees');
        
        $result = TRUE ;
    }
    
    function getExpenseByFileNo($fileNo){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transport_expense');
            $this->db->where('file_no =', $fileNo);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function addNewTransporterExpense($expenseInfo, $container_no, $tid, $amount){ //addNewTransporterExpense($expenseInfo, $container_no, $transporterId, $expenses);
       try{
            $this->db->trans_start();
            
            $this->db->set('expenses', 'expenses +'.$amount, FALSE);
            $this->db->set('transporter_id', $tid);
            $this->db->where('container_nr', $container_no);
            $this->db->update('tbl_document_manager');
            
            //Then insert expense info
            $this->db->insert('tbl_transport_expense', $expenseInfo);
            $insert_id = $this->db->insert_id();
            
            // $this->db->insert('');
            $data = array(
                'container_no' => $container_no,
                'trip_id' => $insert_id
            );
            
            $this->db->insert('tbl_containers', $data);
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
       }
       catch(PDOException $e){
           $resp = $e->getMessage();
       }
       
       return $resp;
    }
    
    function updateTransporterExpense($id, $expenseInfo, $paymentInfo, $totalbalance, $file_no){//, $expenseInfo){ //, $expenseInfo, $paymentInfo)
        try{
            $this->db->trans_start();
            
            $this->db->where('id', $id);
            $this->db->update('tbl_transport_expense', $expenseInfo);
            
            //insert new payment into the payment table
            $this->db->insert('tbl_payments', $paymentInfo);
            $insert_id = $this->db->insert_id();
            
            if($insert_id > 1){
                $this->db->set('balance', $totalbalance);
                $this->db->where('file_no', $file_no);
                $this->db->update('tbl_transport_expense');
            }

            
            $this->db->trans_complete();
            
            $result = TRUE ;
        }
        catch(PDOException $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
    
    function removeTransporterExpense($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_transport_expense');
        
        //Remember to re-do sumation for the file_no expenses update deducts
        
        $result = TRUE ;
    }
    
    function addNewInterchange($interchangeInfo){
        try{
            $this->db->trans_start();
            $this->db->insert('tbl_interchange', $interchangeInfo);
            
            $insert_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
       }
       catch(PDOException $e){
           $resp = $e->getMessage();
       }
       
       return $resp;
    }
    
    function getTransporterTrucks($transporterID){
        try{
            $this->db->select('*');
            $this->db->from('tbl_trucks');
            $this->db->where('transporter_id =', $transporterID);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getAlltrucks(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_trucks');
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    /**
     * Get client list
     * 
     * */
     function getTranportersList(){
        try{
            $this->db->select('*');
            $this->db->from('tbl_transporter');
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
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getTransporterInfo($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_transporter');
        //$this->db->where('isDeleted', 0);
        //$this->db->where('roleId !=', 1);
        $this->db->where('id', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editTransporter($transporterInfo, $userId)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_transporter', $transporterInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteTransporter($userId)
    {
        $this->db->trans_start();
        
        $this->db->where('id', $userId);
        $this->db->delete('tbl_transporter');
        
        //Delete trucks where transporter_id = $userId
        $this->db->where('transporter_id', $userId);
        $this->db->delete('tbl_trucks');
        
        $this->db->trans_complete();
        
        return TRUE ;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getTransporterInfoById($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_transporter');
        //$this->db->where('isDeleted', 0);
        $this->db->where('id', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
}
