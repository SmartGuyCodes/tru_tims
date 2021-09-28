<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Charles Evans Ogego Otieno
 * @version : 1.1
 * @since : 15 November 2019
 */
class BaseController extends CI_Controller {
    protected $permissions= '';
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $global = array ();
	protected $lastLogin = '';
	
	/**
	 * Takes mixed data and optionally a status code, then creates the response
	 *
	 * @access public
	 * @param array|NULL $data
	 *        	Data to output to the user
	 *        	running the script; otherwise, exit
	 */
	public function response($data = NULL) {
		$this->output->set_status_header ( 200 )->set_content_type ( 'application/json', 'utf-8' )->set_output ( json_encode ( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) )->_display ();
		exit ();
	}
	
	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn() {
		$isLoggedIn = $this->session->userdata ( 'isLoggedIn' );
		
		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			redirect ( 'login' );
		} 
		else {
			$this->permissions = $this->session->userdata ( 'permissions' );
			$this->role = $this->session->userdata ( 'role' );
			$this->vendorId = $this->session->userdata ( 'userId' );
			$this->name = $this->session->userdata ( 'name' );
			$this->roleText = $this->session->userdata ( 'roleText' );
			$this->lastLogin = $this->session->userdata ( 'lastLogin' );
            			
			$this->global['name'] = $this->name;
			$this->global['role'] = $this->role;
			$this->global['role_text'] = $this->roleText;
			$this->global['permissions'] = $this->permissions;
			$this->global['last_login'] = $this->lastLogin;
			
// 			echo'<pre>Session Details: '; print_r($_SESSION);
// 			echo'<pre>Logged in user Details: '; print_r(unserialize($this->global['permissions'])); 
// 			die;
		}
	}
	
    // 	Logged in user Details: Array
    //     (
    //         [0] => shippinglines_list
    //         [1] => clients_list
    //         [2] => documents_manager
    //         [3] => transporters_list
    //         [4] => interchange
    //         [5] => fees_manager
    //         [6] => accounts_manager
    //         [7] => quotations_manager
    //         [8] => voucher_manager
    //         [9] => receipts_manager
    //         [10] => reports
    //         [11] => settings
    //     )
	
	function manageShippingLines(){
	    
        if (in_array("shippinglines_list", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageClientsList(){
	    
        if (in_array("clients_list", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageDocumentsManager(){
	    
        if (in_array("documents_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageTransportersList(){
	    
        if (in_array("transporters_list", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageInterchange(){
	    
        if (in_array("interchange", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageFeesManager(){
	    
        if (in_array("fees_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageAccountsManager(){
	    
        if (in_array("accounts_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageQuotationsManager(){
	    
        if (in_array("quotations_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageVoucherManager(){
	    
        if (in_array("voucher_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageReceiptsManager(){
	    
        if (in_array("receipts_manager", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageReports(){
	    
        if (in_array("reports", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	function manageSettings(){
	    
        if (in_array("settings", unserialize($this->global['permissions']))) {
			return true;
        }else {
			return false;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isDataClerk() {
		if ($this->role = 3) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isAccountant() {
		if ($this->role = 2) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isAdmin() {
		if ($this->role != ROLE_ADMIN) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isTicketter() {
		if ($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This function is used to load the set of views
	 */
	function loadThis() {
		$this->global ['pageTitle'] = 'Amey Trading : Access Denied';
		
		$this->load->view ( 'includes/header', $this->global );
		$this->load->view ( 'access' );
		$this->load->view ( 'includes/footer' );
	}
	
	/**
	 * This function is used to logged out user from system
	 */
	function logout() {
		$this->session->sess_destroy ();
		
		redirect ( 'login' );
	}

	/**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }
	
	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT) {
		$this->load->library ( 'pagination' );

		$config ['base_url'] = base_url () . $link;
		$config ['total_rows'] = $count;
		$config ['uri_segment'] = $segment;
		$config ['per_page'] = $perPage;
		$config ['num_links'] = 5;
		$config ['full_tag_open'] = '<nav><ul class="pagination">';
		$config ['full_tag_close'] = '</ul></nav>';
		$config ['first_tag_open'] = '<li class="arrow">';
		$config ['first_link'] = 'First';
		$config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<li class="arrow">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<li class="arrow">';
		$config ['next_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="active"><a href="#">';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="arrow">';
		$config ['last_link'] = 'Last';
		$config ['last_tag_close'] = '</li>';
	
		$this->pagination->initialize ( $config );
		$page = $config ['per_page'];
		$segment = $this->uri->segment ( $segment );
	
		return array (
				"page" => $page,
				"segment" => $segment
		);
	}
}