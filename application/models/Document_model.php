<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Document_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Document_model extends CI_Model{   
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id addNewTransporter
     */
    function addNewDocument($documentInfo, $containerInfo) //addNewDocument($documentInfo, $documentInfo['container_nr'])
    {
        $this->db->trans_start();
        
        $this->db->insert('tbl_containers', $containerInfo);
        
        $this->db->insert('tbl_document_manager', $documentInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    function deleteDocument($id){
        $this->db->where('id', $id);
        $this->db->delete('tbl_document_manager');
        
        return TRUE ;
    }
    
    function addNewContainer($containerInfo)
    {
        $this->db->trans_start();
        $this->db->insert(' tbl_containers', $containerInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    function getClientAdvancePays($container_nr){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('payment_for = ', $container_nr);
            $this->db->where('txn_type =', 'CLIENT_ADVANCE');
            $query = $this->db->get();
            $result = $query->result();
        }
        catch(PDOException $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
    
    function getFilePaymentsByCont($container_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('payment_for = ', $container_no);
            $query = $this->db->get();
            $result = $query->result();
        }
        catch(PDOException $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
    
    function getFilePayments($fileNo){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('payment_for = ', $fileNo);
            $query = $this->db->get();
            $result = $query->result();
        }
        catch(PDOException $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
    
    function containerExists($container_nr){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            $this->db->where('container_nr =', $container_nr);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getDocumentDataByCont($container_no){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            $this->db->where('container_nr =', $container_no);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
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
    
    function getClientDocuments($clientName){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            $this->db->where(' 	client_id  =', $clientName);
            $query = $this->db->get();

            $result = $query->result();
        }
        catch(PDOException $e){
                $result = $e->getMessage();
        }

        return $result;
    }
    
    function getDocumentData($fileNo){
        try{
            $this->db->select('*');
            $this->db->from('tbl_document_manager');
            $this->db->where(' 	file_no =', $fileNo);
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
    // function editDocumentInfo($documentInfo, $docID, $paid)
    function editDocumentInfo($documentInfo, $docID)
    {
        try{
            $this->db->trans_start();
        
            $this->db->where('id', $docID);
            $this->db->update('tbl_document_manager', $documentInfo);
            $resp = $this->db->affected_rows();
            
            $this->db->trans_complete();
            
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
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
    
    function updateContainerConsignment($container_nr, $consignee, $consignment){ //updateContainerConsignment($container_nr, $consignee, $consignment)
        $this->db->trans_start();
        
        $this->db->set('consignee_id ', $consignee);
        $this->db->set('consignement ', $consignment);
        $this->db->where('container_nr', $container_nr);
        $this->db->update('tbl_document_manager'); 
        
        $this->db->trans_complete();
        
        return TRUE;
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
