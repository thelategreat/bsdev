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
		$pdf->text(20, 30, 'Danby');   			
		
		$pdf->setFillColor('rgb', 1, 1, 1);   	
		//$pdf->rect(20, 40, 130, 60, 'fd'); 		// pic holder
		
		$pdf->image("proMed-dac10007ee.jpg", 20, 35, 100 );
		
		$pdf->setFillColor('rgb', 0, 0, 0);   	
		$pdf->setDrawColor('rgb', 0, 0, 0);   	
		$pdf->setFont('Arial', 'I', 14);     	
		$pdf->text( 20, 125, "Features");
		$pdf->setDrawColor('rgb', 0.7, 0.7, 0.7); 
		$pdf->line( 20, 126, 150, 126 );

		$pdf->setDrawColor('rgb', 0, 0, 0);   	
		$pdf->setFillColor('rgb', 0, 0, 0);   	
		$pdf->setFont('Arial', '', 9);     	
		$pdf->circle( 20, 130, 0.3 );
		$pdf->text( 22, 131, "Cools approximately 450 square feet depending on conditions");
		$pdf->circle( 20, 134, 0.3 );
		$pdf->text( 22, 135, "Energy Efficiency Rating (EER) of 10.8");
		
		$pdf->setFont('Arial', 'I', 14);     	
		$pdf->text( 20, 180, "Specifications");
		$pdf->setDrawColor('rgb', 0.7, 0.7, 0.7); 
		$pdf->line( 20, 181, 150, 181 );

		$pdf->setDrawColor('rgb', 0, 0, 0);   	
		$pdf->setFillColor('rgb', 0, 0, 0);   	
		$pdf->setFont('Arial', 'B', 8);     	
		$pdf->text( 20, 187, "DIMENSIONS");
		$pdf->text( 100, 187, "CAPACITY");
		$pdf->text( 20, 207, "WEIGHT");
		$pdf->setFont('Arial', '', 8);     	
		$pdf->text( 20, 192, "Product Width");
		$pdf->text( 20, 196, "Product Depth");
		$pdf->text( 20, 200, "Product Height");

		$pdf->text( 20, 212, "Shipping Weight");

		$pdf->text( 100, 192, "By Capacity");


		$pdf->setFont('Arial', 'I', 14);     	
		$pdf->text( 20, 220, "Warranty");
		$pdf->setDrawColor('rgb', 0.7, 0.7, 0.7); 
		$pdf->line( 20, 221, 150, 221 );
		
		$pdf->setDrawColor('rgb', 0, 0, 0);   	
		$pdf->setFillColor('rgb', 0, 0, 0);   	
		$pdf->setFont('Arial', 'B', 8);     	
		$pdf->text( 20, 227, "WARRANTY INFORMATION");
		$pdf->setFont('Arial', '', 8);     	
		$pdf->text( 20, 231, "12 months parts and labour coverage...");
		
		// sidebar
		$pdf->setFillColor('rgb', 1, 1, 1);   	
		$pdf->rect(175, 30, 20, 200, 'fd'); 	
		$pdf->setFillColor('rgb', 0, 0, 0);   	
		$pdf->rect(176, 31, 18, 198, 'fd'); 	
		$pdf->setFillColor('rgb', 1, 1, 1);   	// Set text color to white.
		$pdf->setFont('times', 'I', 23);     	
		$pdf->text(183, 40, 'Window Air Conditioner ~ DAC10007EE', -90);   			
		
		$pdf->setFont('Arial', 'B', 7);     	
		//$pdf->text( 181, 222, 'SR/SA' );
		$pdf->text( 180, 225, date('d/m/y') );
		$pdf->setDrawColor('rgb', 0.6, 0.6, 0.6);   	
		$pdf->setFillColor('rgb', 0.6, 0.6, 0.6);   	
		$pdf->text( 182, 228, 'DMY' );

		$pdf->output('foo.pdf');     // Output the file named foo.pdf
		exit(0);	
	}
}
	