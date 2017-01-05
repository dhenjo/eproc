<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/examples/tcpdf_include.php';

class Pdf extends TCPDF {

	var $htmlHeader;
	var $htmlHeader2;

    public function setHtmlHeader($htmlHeader,$htmlHeader2) {
        $this->htmlHeader = $htmlHeader;
		$this->htmlHeader2 = $htmlHeader2;
    }
	
	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'tcpdf/examples/images/data.PNG';
		$this->Image($image_file, 10, 10, 50, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 12);
		// Title
		$this->Ln(5); 
		$this->Cell(0, 15, $this->htmlHeader, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 10);
		$this->Ln(7);    
		$this->Cell(0, 15, $this->htmlHeader2, 0, false, 'C', 0, '', 0, false, 'M', 'M');		
		
        
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}