<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Pdf.php
*
* Version: 1.0.0
*
* Author: Pedro Ruiz Hidalgo
*		  ruizhidalgopedro@gmail.com
*         @pedroruizhidalg
*
* Location: application/third_party/fpdf/libraries/Pdf.php
*
* Created:  2018-02-27
*
* Description:  This manages FPDF
*
* Requirements: PHP5 or above
*
*/
define('FPDF_FONTPATH', __DIR__ . '/font');

require_once __DIR__ . '/fpdf.php';

class Pdf extends FPDF
{
    protected $orientation;
    protected $size;
    protected $rotation;
    protected $units;
    protected $logo;
    protected $head_title;
    protected $head_subtitle;
    protected $footer_page_literal;


    private $base_url;
    private $format;

    function __construct()
    {
        $ci                     =   & get_instance();
        $ci->load->helper('url');
        $ci->load->config('pdf');

        $this->orientation          =   $ci->config->item('orientation');
        $this->size                 =   $ci->config->item('size');
        $this->rotation             =   $ci->config->item('rotation');
        $this->units                =   $ci->config->item('units');
        $this->format               =   $ci->config->item('format');
        $this->head_title           =   $this->format($ci->config->item('head_title'));
        $this->head_subtitle        =   $this->format($ci->config->item('head_subtitle'));
        $this->footer_page_literal  =   $this->format($ci->config->item('footer_page_literal'));

        $this->base_url         =   $ci->config->item('url_wrapper');
        if ( $this->base_url === TRUE)
            $this->logo = base_url( $ci->config->item('logo') );
        else
            $this->logo = $ci->config->item('logo');


        // lets construct the fpdf objet!
        parent::__construct( $this->orientation , $this->units , $this->size );

    }

    /**
    * header function
    *
    * @param none
    * @return none
    **/
    function header()
    {
        $this->Image($this->logo,10,8,22);
        $this->SetFont( 'Arial' , 'B' ,13 );
        $this->Cell(30);

        $this->Cell(120,10,$this->head_title,0,0,'C');
        $this->Ln('5');
        $this->SetFont('Arial','B',8);
        $this->Cell(30);

        $this->Cell(120,10,$this->head_subtitle,0,0,'C');
        $this->Ln(20);
    }

    /**
    * footer function
    *
    * @param none
    * @return none
    **/
    function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,"{$this->footer_page_literal} ".$this->PageNo().'/{nb}',0,0,'C');
    }

    /**
    * logo getter
    *
    * @param none
    * @return string
    **/
    function get_logo()
    {
        return $this->logo;
    }

    /**
    * orientation getter
    *
    * @param none
    * @return string
    **/
    function get_orientaion()
    {
        return $this->orientation;
    }

    /**
    * size getter
    *
    * @param none
    * @return string
    **/
    function get_size()
    {
        return $this->size;
    }

    /**
    * rotation getter
    *
    * @param none
    * @return int
    **/
    function get_rotation()
    {
        return $this->rotation;
    }

    /**
    * units getter
    *
    * @param none
    * @return string
    **/
    function get_units()
    {
        return $this->units;
    }

    /**
    * Head title getter
    *
    * @param none
    * @return string
    **/
    function get_head_title()
    {
        return $this->head_title;
    }

    /**
    * Head subtitle getter
    *
    * @param none
    * @return string
    **/
    function get_head_subtitle()
    {
        return $this->head_subtitle;
    }

    /**
    * addpage function
    *
    * @param string
    * @param mixed
    * @param int
    * @return void
    **/

    function Add_Page( $orientation=NULL , $size=NULL , $rotation=NULL )
    {
        if( is_null($orientation) )
            $orientation = $this->orientation;
        else
            $this->orientation = $orientation;

        if( is_null($size) )
            $size = $this->size;
        else
            $this->size = $size;

        if( is_null($rotation) )
            $rotation = $this->rotation;
        else
            $this->rotation = $rotation;

        $this->AddPage( $this->orientation , $this->size , $this->rotation );
    }

    /**
    * render function
    *
    * @param string
    * @param string
    * @param bool
    * @return void
    *
    * Behaviour:
    * dest,             indicates where send the documment. It can bo one of following
    *                   'I': send the file inline to the browser. The PDF viewer is used if available.
    *                   'D': send to the browser and force a file download with the name given by name.
    *                   'F': save to a local file with the name given by name (may include a path).
    *                   'S': return the document as a string.
    *
    * name,             The name of the file. It is ignored in case of destination S.
    *                   The default value is doc.pdf.
    *
    * $this->format,    Indicates if name is encoded in ISO-8859-1 (false) or UTF-8 (true).
    *                   Only used for destinations I and D.
    *                   The default value is false.
    **/
    function render($dest='I',$name='document.pdf')
    {
        $this->Output($dest,$name,$this->format);
    }


    /**
    * format function
    *
    * @param string
    * @return string
    **/
    function format($str)
    {
        return utf8_decode($str);
    }
}



/** this ends this file
* application/third_party/fpdf/libraries/Pdf.php
*/
