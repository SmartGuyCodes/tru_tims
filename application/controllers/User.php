<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Charles Evans Ogego Otieno
 * @version : 1.1
 * @since : 15 November 2019
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        $this->load->model('user_model');
        $this->load->model('client_model');
        $this->load->model('shippingline_model');
        $this->load->model('transporter_model');
        $this->load->model('document_model');
        $this->load->model('account_model');
        $this->load->model('payment_model');
        $this->load->model('bond_model');
        
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Amey Trading : Dashboard';
        $data['clientRecords'] = $this->client_model->getClientList();
        $data['shippingLines'] = $this->shippingline_model->getShippinglines();
        $data['transporters'] = $this->transporter_model->getTranportersList();
        $data['documents'] = $this->document_model->getDocs();
        $data['users'] = $this->user_model->getAllUsers();
        $data['invoices'] = $this->account_model->getInvoices();
        $data['vouchers'] = $this->payment_model->getAllVouchers();
        $data['receipts'] = $this->payment_model->getAllReceipts();
        $data['trucks'] = $this->transporter_model->getAlltrucks();
        $data['bonds'] = $this->bond_model->getAllBonds();
        $data['roles'] = $this->user_model->getUserRoles();
        $data['logins'] = $this->user_model->activityLogsCount();
        // <?php if (in_array("clients_list", $permissions)) {
        $data['permissions'] = unserialize($this->global['permissions']);
        // echo'<pre> Login History logs: '; print_r($data['permissions']); 
        // die;
        //tbl_last_login
        //echo'<pre>'; print_r($data['users']); die;
        
        $this->loadViews("dashboard", $this->global, $data, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "userListing/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);
            // echo'<pre> HTTPS Users list Request: ';print_r($data['userRecords']); 
            // die;
            $this->global['pageTitle'] = 'Amey Trading : User Listing';
            
            $this->loadViews("users", $this->global, $data, NULL);
        // }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            // echo'<pre> Useer Roles HTTPS Request: '; print_r($data['roles']);
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Add New User';

            $this->loadViews("addNew", $this->global, $data, NULL);
        // }
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
    function addNewUser()
    {
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            // echo'<pre> HTTPS POST Request: '; print_r($_REQUEST);
            // die;
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            // $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $perm_array = $this->input->post('permission');
                // echo'<pre> Permissions POST Request: '; print_r($perm_array);
                // die;
                
                $userInfo = array(
                    'email'=>$email, 
                    'password'=>getHashedPassword($password), 
                    'roleId'=>0, 
                    'permissions' => serialize($perm_array),
                    'name'=> $name,
                    'mobile'=>$mobile, 
                    'createdBy'=>$this->vendorId, 
                    'createdDtm'=>date('Y-m-d H:i:s')
                );
                // echo'<pre> User info POST Request: '; print_r($userInfo);
                // die;
                
                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('addNew');
            }
        // }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        // if($this->isAdmin() == TRUE) // || $userId == 1
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            if($userId == null)
            {
                redirect('userListing');
            }
            
            // echo'<pre>HTTPS User info request: '; print_r($_REQUEST);
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            // echo'<pre>HTTPS User info request: '; print_r($data['userInfo']);
            
            $perm_array = unserialize($data['userInfo']->permissions);
            $data['permissions'] = $perm_array;
            // echo'<pre>HTTPS User info request: '; print_r($perm_array);
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Edit User';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        // }
    }
    
    function editAccount()
    {
        // if($this->isAdmin() == TRUE) // || $userId == 1
        // {
        //     $this->loadThis();
        // }
        // else
        // {
            // if($userId == null)
            // {
            //     redirect('userListing');
            // }
            $userId = trim(filter_input(INPUT_GET, 'id'));
            // echo'<pre>HTTPS User info request: '; print_r($_REQUEST);
            // die;
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            // echo'<pre>HTTPS User info request: '; print_r($data['userInfo']);
            
            $perm_array = unserialize($data['userInfo']->permissions);
            $data['permissions'] = $perm_array;
            // echo'<pre>HTTPS User info request: '; print_r($perm_array);
            // die;
            
            $this->global['pageTitle'] = 'Amey Trading : Edit User';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        // }
    }
      
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        $this->load->library('form_validation');
        
        $userId = $this->input->post('userId');
        
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
        // $this->form_validation->set_rules('role','Role','trim|required|numeric');
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
            $roleId = 0;
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            
            $userInfo = array();
            
            $perm_array = $this->input->post('permission');
            // echo'<pre> Permissions POST Request: '; print_r($perm_array);
            // die;
            
            if(empty($password))
            {
                $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name, 'permissions' => serialize($perm_array),
                                'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            }
            else
            {
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,'permissions' => serialize($perm_array),
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
        // }
    }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        $userId = $this->input->post('userId');
        $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        
        $result = $this->user_model->deleteUser($userId, $userInfo);
        
        if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
        else { echo(json_encode(array('status'=>FALSE))); }
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
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        // if($this->isAdmin() == TRUE)
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        $userId = ($userId == NULL ? 0 : $userId);

        $searchText = $this->input->post('searchText');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');

        $data["userInfo"] = $this->user_model->getUserInfoById($userId);

        $data['searchText'] = $searchText;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;
        
        $this->load->library('pagination');
        
        $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

        $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 10, 3);

        $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'Amey Trading : User Login History';
        
        $this->loadViews("loginHistory", $this->global, $data, NULL);
        // }        
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $userId = $_SESSION['userId'];
        // echo'<pre> User sessions request: '; print_r($_SESSION);
        // die;
        
        // $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId); //$userId
        $data["userInfo"] = $this->user_model->getUserInfo($userId); //
        // echo'<pre> User info request: '; print_r($data["userInfo"]);
        // die;
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
    
    
    // Version 2.0.2 : User Roles & Permissions
    //User Roles
    function manageroles(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
        // echo'<pre>'; print_r($this->isAdmin()); die;
        if($this->isDataClerk() == FALSE || $this->isAccountant() == FALSE || $this->isAdmin() == FALSE)
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
            // $data['shippingLines'] = $this->shippingline_model->getShippinglines();
            
            //echo'<pre>'; print_r($data['clientRecords']); DIE;
            $this->global['pageTitle'] = 'Amey Trading : Manage User Roles ';
            $this->loadViews("user/index", $this->global, $data, NULL);
        }
    }
    function addrole(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    function editrole(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    function updaterole(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    function deleterole(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    
    // //User Permissions
    // $route['manage-permissions'] = 'user/managePermissions';
    function managePermissions(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    // $route['add-permission'] = 'user/addPermission';
    function addPermission(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    // $route['edit-permission'] = 'user/editPermission';
    function editPermission(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    // $route['update-permission'] = 'user/updatePermission';
    function updatePermission(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    // $route['delete-permission'] = 'user/deletePermission';
    function deletePermission(){
        echo'<pre>HTTPS Request: '; print_r($_REQUEST);
        die;
    }
    
    
}

?>