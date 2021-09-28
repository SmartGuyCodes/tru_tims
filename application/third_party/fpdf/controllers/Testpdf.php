<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Name:  Testpdf
*
* Version: 1.0.0
*
* Author: Pedro Ruiz Hidalgo
*		  ruizhidalgopedro@gmail.com
*         @pedroruizhidalg
*
* Location: application/controllers/Testpdf.php
*
* Created:  208-02-27
*
* Description:  This demonstrates pdf library is working.
*
* Requirements: PHP5 or above
*
*/


class Testpdf extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->add_package_path( APPPATH . 'third_party/fpdf');
        $this->load->library('pdf');
    }

	public function index()
	{
        $this->pdf = new Pdf();
        $this->pdf->Add_Page('P','A4',0);
        $this->pdf->AliasNbPages();
        
        $this->pdf->Output( 'page.pdf' , 'I' );
	}
}

/*
* application/controllers/Testpdf.php
*/
