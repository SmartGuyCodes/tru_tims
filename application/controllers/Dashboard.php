<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Dashboard (DashboardController)
 * 
 * @author : Charles Evans Ogego Otieno
 * @version : 1.1
 * @since : 15 November 2019
 */
class DashboardController extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('login_model');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }
    
    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login');
        }
        else
        {
            $data['clientRecords'] = $this->client_model->getClientList();
            echo'<pre>'; print_r($data); die;
            $this->load->view('dashboard');
            //redirect('/dashboard');
        }
    }
}

?>