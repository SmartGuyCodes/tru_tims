<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Shippinglines_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Shippingline_model extends CI_Model{
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewShippingline($clientInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_shippingline', $clientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    function getShipperInfo($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_shippingline');
            $this->db->where('id =', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOExcpetion $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    /**
     * Get list
     * 
     * */
     function getShippinglines(){
          try{
                $this->db->select('*');
                $this->db->from('tbl_shippingline');
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
    function getShippinglineInfo($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_shippingline');
        $this->db->where('isDeleted', 0);
		$this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editShippingline($userInfo, $userId)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_shippingline', $userInfo);
        
        return TRUE;
    }
    
    function editShipper($shipperInfo, $id){
        try{
            $this->db->where('id', $id);
            $this->db->update('tbl_shippingline', $shipperInfo);
            
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
    function deleteShippingline($shipId)
    {
        $this->db->where('id', $shipId);
        $this->db->delete('tbl_shippingline');
        
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
        $this->db->from('tbl_shippingline');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
}
