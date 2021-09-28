<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Shipping Lines
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Shippinglines extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('shippingline_model');
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
    
    /**
     * This function is used to load the user list
     */
    public function listing()
    {
        // echo'<pre>'; print_r($this->isAdmin()); die;
        if($this->manageShippingLines() == FALSE)
        {
            $this->loadThis();
        }
        else
        {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            //$this->load->library('pagination');

            //$count = $this->client_model->clientListingCount($searchText);

            //$returns = $this->paginationCompress ( "clientListing/", $count, 10 );
            //            
            $data['shippingLines'] = $this->shippingline_model->getShippinglines();
            
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            $this->global['pageTitle'] = 'Amey Trading : Registered Shipping Lines ';
            $this->loadViews("shippinglines/index", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('shippingline_model');
            $data['roles'] = '';
            
            $this->global['pageTitle'] = 'Amey Trading : Add New Shipper';

            $this->loadViews("shippinglines/addNew", $this->global, $data, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewshipper()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
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
                $shipperinfo = array();
                $shipperinfo['shipper_name'] = trim(filter_input(INPUT_POST, 'shipper_name'));
                $shipperinfo['contact_person'] = trim(filter_input(INPUT_POST, 'contact_person'));
                $shipperinfo['email'] = trim(filter_input(INPUT_POST, 'email'));
                $shipperinfo['telephone_no'] = trim(filter_input(INPUT_POST, 'telephone_no'));
                $shipperinfo['country'] = trim(filter_input(INPUT_POST, 'country'));
                $shipperinfo['address'] = trim(filter_input(INPUT_POST, 'address'));
                
                $this->load->model('shippingline_model');
                $result = $this->shippingline_model->addNewShippingline($shipperinfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Shipper created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Shipper creation failed');
                }
                
                redirect('/shippinglines/add');
            //}
        }
    }
    
    function edit(){
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            // $this->load->model('shippingline_model');
            // $data['roles'] = '';
            
            $data['id'] = trim(filter_input(INPUT_GET, 'id'));
            $this->load->model('shippingline_model');
            $data['shipperinfo'] = $this->shippingline_model->getShipperInfo($data['id']);
            //echo'<pre>'; print_r($data); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Shipper';

            $this->loadViews("shippinglines/edit", $this->global, $data, NULL);
        }
        
    }
    
    function updateShipper(){
        $shipperInfo = [];
        $id = trim(filter_input(INPUT_GET, 'id'));
        $shipperInfo['shipper_name'] = trim(filter_input(INPUT_POST, 'shipper_name'));
        $shipperInfo['contact_person'] = trim(filter_input(INPUT_POST, 'contact_person'));
        $shipperInfo['email'] = trim(filter_input(INPUT_POST, 'email'));
        $shipperInfo['telephone_no'] = trim(filter_input(INPUT_POST, 'telephone_no'));
        $shipperInfo['country'] = trim(filter_input(INPUT_POST, 'country'));
        $shipperInfo['address'] = trim(filter_input(INPUT_POST, 'address'));
        
        //Update info by shipper id
        $this->load->model('shippingline_model');
        $result = $this->shippingline_model->editShipper($shipperInfo, $id);
        //echo'<pre>'; print_r($result); die;
        
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Shipping Line record updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Shipping Line updating failed');
        }
                
        redirect('/shippinglines/edit?id='.$id);
    }
    
    function delete(){
        //echo'<pre>'; print_r($_REQUEST); 
        //die;
        $id = trim(filter_input(INPUT_GET, 'id'));
        
        $this->load->model('shippingline_model');
        $result = $this->shippingline_model->deleteShippingline($id);
        //echo'<pre>'; print_r($result); die;
        
        if($result > 0)
        {
            $this->session->set_flashdata('success', 'Shipping Line deleted successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Shipping Line Deletion failed');
        }
        
        redirect('/shippinglines/listing');
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        // if($this->isAdmin() == TRUE)
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
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
