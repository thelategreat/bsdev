<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Pdf extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
	}

	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->load->library('pdfgen');

		// Set up the pdf object.
		$pdf = &$this->pdfgen->factory('p', 'letter', 'mm');      
		$pdf->open();                         	// Start the document.
		$pdf->setCompression(false);           	// Activate compression.
		
		$pdf->addPage();                      	// Start a page.
		$pdf->setLineWidth( 0.5 );
		$pdf->setFont('Arial', 'B', 20);     	// Set font to arial bold
		$pdf->setFillColor('rgb', 0, 0, 0);   	// Set text color to black.

		$pdf->output('foo.pdf');     // Output the file named foo.pdf
		exit(0);	
	}
}
	