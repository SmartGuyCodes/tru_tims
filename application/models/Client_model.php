<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Client_model extends CI_Model{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function clientListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function clientListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewClient($clientInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_clients', $clientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * Get client list
     * 
     * */
     function getClientList(){
          try{
                $this->db->select('*');
                $this->db->from('tbl_clients');
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
    function getClientInfo($userId)
    {
        try{
            $this->db->select('*');
            $this->db->from('tbl_clients');
            $this->db->where('id', $userId);
            $query = $this->db->get();
            
            $resp = $query->row();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getClientPayments($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_payments');
            $this->db->where('client_id ', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getClientDetale($clientName){
        try{
            $this->db->select('*');
            $this->db->from('tbl_clients');
            $this->db->where('client_name', $clientName);
            $query = $this->db->get();
            
            $resp = $query->row();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editClient($userInfo, $userId)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_clients', $userInfo);
        
        return TRUE;
    }
    
    function updateClientCharges($container_nr, $clearing_charges, $extra_charge, $total){//updateClientCharges($client_id, $documentInfo){
        try{
            $this->db->trans_start();
            
            $this->db->set('clearing_charges', 'clearing_charges +'.$clearing_charges, FALSE);
            $this->db->set('extra_paid', 'extra_paid +'.$extra_charge, FALSE);
            $this->db->set('amount_agreed', 'amount_agreed +'.$total, FALSE);
            $this->db->set('updated_at', date('d-m-Y H:m:s'));
            $this->db->where('container_nr', $container_nr);
            $this->db->update('tbl_document_manager');
            
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
    function deleteClient($userId)
    {
        $this->db->where('id', $userId);
        $this->db->delete('tbl_clients');
        
        return TRUE ;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getClientInfoById($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
}
