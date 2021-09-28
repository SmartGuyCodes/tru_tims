<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
    
    //Page header
    // public function Header() {
    //     $file = 'https://cargomis.ameytrading.co.ke/assets/images/logo.jpg';
    //     $this->Image($file, 30, 20, '', '', 'JPG', '', 'C', false, 300, '', false, false, 0, false, false, false);
    //     $this->SetFont('helvetica', 'B', 20);
    // }
    
    /**
    * Overwrite Header() method.
    * @public
    */
    public function Header() {
        // if ($this->tocpage) or
        if ($this->page == 1) {
            // *** replace the following parent::Header() with your code for TOC/page you want page
            // parent::Header();
            // this will add logo and text to first page
            // $file = 'https://cargomis.ameytrading.co.ke/assets/images/logo.jpg';
            // http://dev.tru-tims.com/
            $file = 'https://scontent-mba1-1.xx.fbcdn.net/v/t1.6435-9/61090616_659298581250046_3642534061198540800_n.jpg';
            $this->Image($file, 30, 20, '', '', 'JPG', '', 'C', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', 'B', 20);
            
            // $this->Cell(0, 15, 'First page header text', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        } 
        else {
            // *** replace the following parent::Header() with your code for other pages
            //parent::Header();
            // following will add your own logo ant text to other pages
            // $this->Image('http://localhost/other_pages_logo.png', 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // $this->SetFont('helvetica', 'B', 14);
            // $this->Cell(0, 15, 'Other pages header text', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
    }
    
      // Page footer
    public function Footer() {
        // $footer_text = 'Wilcom Cargo Management System &copy; '. date('Y');               
        $footer_text = '';
        $this->writeHTMLCell(150, 150, 25, 260, $footer_text, 0, 0, 0, true, 'L', true); 
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */