<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bond_model
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Bond_model extends CI_Model{  
    
    //Ensure a bond with a unique ref no is added
    function bondExists($bond_ref)
    {
        try{
            $this->db->select('*');
            $this->db->from('tbl_bond');
            $this->db->where('bond_ref =', $bond_ref);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function addNewBond($bondInfo)
    {
        try{
            $this->db->trans_start();
        
            $this->db->insert('tbl_bond', $bondInfo);
            
            $insert_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
            $resp = $insert_id;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    } 
    
    function updateBond($bondId, $bondInfo)
    {
        try{
            $this->db->trans_start();
        
           
            
            $this->db->trans_complete();
            
            // $resp = $insert_id;
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getAllBonds()
    {
        try{
            $this->db->select('*');
            $this->db->from('tbl_bond');
            $this->db->where('is_deleted =', 0);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getAllServicedBonds()
    {
        try{
            $this->db->select('*');
            $this->db->from('tbl_bonds');
            $this->db->where('released =', 1);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getBondProfileIdId($id)
    {
        try{
            $this->db->trans_start();
            $profileInfo = '';
            $this->db->trans_complete();
            
            $resp = $profileInfo;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getBondProfileByName($name) 
    {
        try{
            $this->db->select('*');
            $this->db->from('tbl_bond');
            $this->db->where('is_deleted =', 0);
            $this->db->where('bond_name', $name);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function attachBond($bondId, $bondAmount, $containerInfo)
    {
        try{
            $this->db->trans_start();
            
            // $profileInfo = '';
            
            $this->db->trans_complete();
            
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;    
    }
    
    function insertAttachment($bondInfo, $bondRef, $newParentBondValue)
    {
        try{
            $this->db->trans_start();
            
            $this->db->insert('tbl_bonds', $bondInfo);
            $insert_id = $this->db->insert_id();
            
            //
            $this->db->set('bond_value', $newParentBondValue, FALSE);
            $this->db->where('bond_ref', $bondRef);
            $this->db->update('tbl_bond');
            
            //
            // $this->db->set('');
            // $this->db->where('container_nr', $container_nr);
            // $this->db->update('tbl_document_manager', $documentInfo);
            
            $this->db->trans_complete();
            
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getAllAttachedBondsByRef($bond_ref){
        try{
            $this->db->select('*');
            $this->db->from('tbl_bonds');
            $this->db->where('attached =', 1);
            $this->db->where('bond_ref', $bond_ref);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function getAttachedBondProfile($id){
        try{
            $this->db->select('*');
            $this->db->from('tbl_bonds');
            $this->db->where('attached =', 1);
            $this->db->where('id', $id);
            $query = $this->db->get();

            $resp = $query->result();
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function releaseBond($bond_id, $bond_ref, $bond_value){
        try{
            $this->db->trans_start();
            
            $this->db->set('bond_value', 'bond_value+'.$bond_value, FALSE);
            $this->db->where('bond_ref', $bond_ref);
            $this->db->update('tbl_bond');
            
            //attached	released	date_released
            $this->db->set('attached', 0);
            $this->db->set('released', 1);
            $this->db->set('date_released', date("Y-m-d H:i:s"));
            $this->db->set('updated_at', date("Y-m-d H:i:s"));
            $this->db->where('id', $bond_id);
            $this->db->update('tbl_bonds');
            
            $this->db->trans_complete();
            
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
    }
    
    function deleteBond($bod_ref)
    {
        try{
            $this->db->trans_start();
            
            $this->db->set('is_deleted', 1);
            $this->db->where('bond_ref', $bod_ref);
            $this->db->update('tbl_bond'); 
            
            $this->db->trans_complete();
            
            $resp = TRUE;
        }
        catch(PDOException $e){
            $resp = $e->getMessage();
        }
        
        return $resp;
        // $this->db->where('bond_ref', $bond_ref);
        // $this->db->delete('tbl_bond');
        
        // return TRUE ;
    }
}
