<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Clearing & Forwarding
 *
 * @author Charles Evans Ogego Otieno
 * 
 */
class Clearingforwarding extends BaseController{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document_model');
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
    
    public function launchclearing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            //$data['documents'] = $this->document_model->getDocumentsList();
            $data['docs'] = $this->document_model->getDocs();
            
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            $this->global['pageTitle'] = 'Amey Trading : Registered Shipping Lines ';
            $this->loadViews("clearing/index", $this->global, $data, NULL);
        }
    }
    
    function loadfile(){
        //echo'<pre>'; print_r($_REQUEST); die;
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            //echo'<pre>'; print_r($_REQUEST); die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            //$data['documents'] = $this->document_model->getDocumentsList();
            $data['docs'] = $this->document_model->getDocs();
            $data['file_no'] = trim(filter_input(INPUT_GET, 'id'));
            
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            $this->global['pageTitle'] = 'Amey Trading : Clearing & Forwarding  ';
            $this->loadViews("clearing/loadfile", $this->global, $data, NULL);
        }
    }
    
    function clearingfees(){
        //Get user inputs
        $data['file_no'] = trim(filter_input(INPUT_GET, 'file_no'));
        //$fees = trim(filter_input(INPUT_POST, 'fees'));
        $fees= $_POST['fees'];

        if(isset($fees)) {
            //echo 'You have chosen:' . '<br>' . '<br>';
            foreach ($fees as $key => $value)
            {
                if($value == 'IDF_fee'){
                    $data['IDF_fee'] = '1';
                } 
                else if($value == 'misc_fee'){
                    $data['misc_fee'] = '1';
                }
                else if($value == 'port_charges'){
                    $data['port_charges'] = '1';
                }
                else if($value == 'shipping_line_fees'){
                    $data['shipping_line_fees'] = '1';
                }
                else if($value == 'storage_fees'){
                    $data['storage_fees'] = '1';
                }
                else if($value == 'tax'){
                    $data['tax'] = '1';
                }
                else if($value == 'transport_fee'){
                    $data['transport_fee'] = '1';
                }
                //echo $value . '<br>';
            }
        }
        else 
        {
            echo "You haven't selected any fee"; die;
        }
        //Get data file documents via file no
        $this->load->model('document_model');
        $data['file'] = $this->document_model->getDocumentData($data['file_no']);
        $data['date_of_entry'] = $data["file"][0]->date_received;
        $data['client'] = $data["file"][0]->client_id; //
        $data['consignee'] = $data["file"][0]->consignee_id;
        $data['consignment'] = $data["file"][0]->consignement;
        $data['containers'] = ""; //To get via file number
        $data['container_size'] = $data["file"][0]->container_size;
        $data['bill_of_landing'] = $data["file"][0]->bill_of_landing;
        $data['shipping_line'] = $data["file"][0]->shipping_line;
        $data['date_of_loading'] = ""; //$data["file"][0]->;
        $data['idf_no'] = $data["file"][0]->idf_no;
        $data['collection_status'] = $data["file"][0]->collection_status;
        $data['date_of_collection'] = $data["file"][0]->collection_date;
        $data['amount_agreed'] = $data["file"][0]->amount_agreed;
        // $data[''] = "";
        echo'<pre>'; print_r($data); die;
        
        $this->global['pageTitle'] = 'Amey Trading : Clearing & Forwarding  ';
        $this->loadViews("clearing/document", $this->global, $data, NULL);
    }
    
    public function fileNoGenerator($digits = 6){
        $i = 0;
        $fileNo = "";
        while($i < $digits){
                //Generate a random unique number
                $fileNo .=mt_rand(0,9);
                $i++;
        }

        return $fileNo;
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('document_model');
            $this->load->model('client_model');
            $this->load->model('shippingline_model');
            
            $data['roles'] = '';//$this->client_model->getUserRoles();
            $data['fileNo'] = $this->fileNoGenerator($digits = 6);
            $data['fileNo'] = 'FL0'.$data['fileNo'];
            
            $data['clients'] = $this->client_model->getClientList();
            $data['shippinglines'] = $this->shippingline_model->getShippinglines();
            #echo'<pre>'; print_r($data['shippinglines']); die;
            
            $this->global['pageTitle'] = 'Amey Trading : Add New File';

            $this->loadViews("documents/addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewDocument()
    {
        //echo'<pre>'; print_r($_REQUEST); die;
        if($this->isAdmin() == TRUE)
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
                $documentInfo = array();
                $documentInfo['file_no'] = trim(filter_input(INPUT_POST, 'file_no'));
                $documentInfo['date_received'] = trim(filter_input(INPUT_POST, 'date_received'));
                $documentInfo['bill_of_landing'] = trim(filter_input(INPUT_POST, 'bill_of_landing'));
                $documentInfo['vessel'] = trim(filter_input(INPUT_POST, 'vessel'));
                $documentInfo['manifest_no'] = trim(filter_input(INPUT_POST, 'manifest_no'));
                $documentInfo['eta_ata'] = trim(filter_input(INPUT_POST, 'eta_ata'));
                $documentInfo['client_id'] = trim(filter_input(INPUT_POST, 'client_id'));
                $documentInfo['consignee_id'] = trim(filter_input(INPUT_POST, 'consignee_id'));
                $documentInfo['shipping_line'] = trim(filter_input(INPUT_POST, 'shipping_line'));
                $documentInfo['cargo_type'] = trim(filter_input(INPUT_POST, 'cargo_type'));
                $documentInfo['collection_status'] = trim(filter_input(INPUT_POST, 'collection_status'));
                $documentInfo['container_size'] = trim(filter_input(INPUT_POST, 'container_size'));
                $documentInfo['consignement'] = trim(filter_input(INPUT_POST, 'consignement'));
                $documentInfo['idf_no'] = trim(filter_input(INPUT_POST, 'idf_no'));
                
                $this->load->model('document_model');
                $result = $this->document_model->addNewDocument($documentInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Document created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Document creation failed');
                }
                
                redirect('/documents/add');
            //}
        }
    }
    
    function containers(){
        //Get documents details by id
        $docID = $this->input->get("id");
        
        //Get file info by id
        $this->load->model('document_model');
        $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
        $data['containers'] = $this->document_model->getDocumentContainers($data['documentInfo'][0]->file_no);
        
        //echo'<pre>'; print_r($data['containers']); die;
        
        $this->global['pageTitle'] = 'Amey Trading : Containers ';
        $this->loadViews("documents/containers", $this->global, $data, NULL);
    }
    
    function addContainer(){
        //echo'<pre>'; print_r($_REQUEST); die;
        $containerInfo = array();
        $containerInfo['id'] =  $this->input->post("id");
        $containerInfo['file_no'] =  $this->input->post("file_no");
        $containerInfo['container_chasis_no'] =  $this->input->post("container_chasis_no");
        $containerInfo['description'] =  $this->input->post("description");
        
        //Get file info by id
        $this->load->model('document_model');
        $insert = $this->document_model->addNewContainer($containerInfo);
        //echo'<pre>'; print_r($insert); die;
        if($insert > 0)
        {
            $this->session->set_flashdata('success', 'Container added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Container addition failed');
        }
        
        redirect('/documents/containers?id='.$containerInfo['id']);
    }
    
    /**
     * This function is for updating the clearing info in the file
     * */
    function clearing(){
        if($this->isAdmin() == TRUE){
            $this->loadThis();
        }
        else{
            //Get file id
            $docID = $this->input->get("id");
            
            //Get file info by id
            $this->load->model('document_model');
            $data['documentInfo'] = $this->document_model->getDocumentInfo($docID);
            //echo'<pre>'; print_r($data['documentInfo']); die;
            
            //display info to the user
            $this->global['pageTitle'] = 'Amey Trading : Registered Shipping Lines ';
            $this->loadViews("documents/clearing", $this->global, $data, NULL);
        }
    }
    
    /**
     * Updating clearance inforrmation
     * */
     function updateClearance(){
        $documentInfo = [];
        $documentInfo['id'] =  $this->input->post("id");
        $documentInfo['charges'] =  $this->input->post("charges");
        $documentInfo['do_status'] =  $this->input->post("do_status");
        $documentInfo['down'] =  $this->input->post("down");
        $documentInfo['car_reg'] =  $this->input->post("car_reg");
        $documentInfo['gate_out'] =  $this->input->post("gate_out");
        
        $this->load->model('document_model');
        $update = $this->document_model->editDocumentInfo($documentInfo, $documentInfo['id']);
        if($result = 1)
        {
            $this->session->set_flashdata('success', 'Document updated successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Document update failed');
        }
        
        redirect('/documents/clearing?id='.$documentInfo['id']);
     }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if($this->isAdmin() == TRUE || $userId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('clientListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Amey Trading : Edit Client';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
      
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array();
                
                if(empty($password))
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('userListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        if($this->isAdmin() == TRUE)
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