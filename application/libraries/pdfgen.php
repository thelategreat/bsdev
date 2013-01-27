<?php

/**
 * Pure PHP generator
 *
 * also see: http://github.com/sandal/prawn/
 *
 * @author J Knight <jim AT barkingdogstudios DOT com>
 * @author  Marko Djukic (http://devzone.zend.com/article/1254-PDF-Generation-Using-Only-PHP---Part-1)
 * @version 1.0
 * @copyright J. Knight, 17 February, 2009
 * @package default
 **/

/**
 * Define DocBlock
 **/


/**
 * PDF Factory
 *
 * @package default
 * @author J Knight
 */
class Pdfgen
{
	public function &factory($orientation = 'P', $format = 'A4', $units = 'pt')
	{
	    /* Create the PDF object. */
		switch( $units ) {
			case 'mm':
			$units = (72 / 25.4);
			break;
			
			case 'pt':
			$units = 1;
			break;
			
			default:
			throw new Exception( "Unknown units to Pdfgen: $units" );
		}
	
		// create a new PDF object
	    $pdf = &new PDFObj( $units );

	    /* Page format. */
	    $format = strtolower($format);
	    if ($format == 'a3') {           // A3 page size.
	        $format = array(841.89, 1190.55);
	    } elseif ($format == 'a4') {     // A4 page size.
	        $format = array(595.28, 841.89);
	    } elseif ($format == 'a5') {     // A5 page size.
	        $format = array(420.94, 595.28);
	    } elseif ($format == 'letter') { // Letter page size.
	        $format = array(612, 792);
	    } elseif ($format == 'legal') {  // Legal page size.
	        $format = array(612, 1008);
	    } else {
	        throw new Exception("Unknown page format: $format");
	    }   
	    $pdf->_w = $format[0];
	    $pdf->_h = $format[1];

	    /* Page orientation. */
	    $orientation = strtolower($orientation);
	    if ($orientation == 'l' || $orientation == 'landscape') {
	        $w = $pdf->_w;
	        $pdf->_w = $pdf->_h;
	        $pdf->_h = $w;
	    } elseif ($orientation != 'p' && $orientation != 'portrait') {
	        throw new Exception("Incorrect orientation: $orientation");
	    }

	    /* Turn on compression by default. */
	    $pdf->setCompression(true);

	    return $pdf;
	}
}
	
/**
 * PDF Object
 *
 * @package default
 * @author J Knight
 */
class PDFObj
{
	
	var $_buffer = '';          // Buffer holding in-memory PDF.
	var $_state = 0;            // Current document state.
	var $_page = 0;             // Current page number.
	var $_n = 2;                // Current object number.
	var $_offsets = array();    // Array of object offsets.
	var $_pages = array();      // Array containing the pages.
	var $_w;                    // Page width in points.
	var $_h;                    // Page height in points
	var $_fonts = array();      // An array of used fonts.
	var $_font_family = '';     // Current font family.
	var $_font_style = '';      // Current font style.
	var $_current_font;         // Array with current font info.
	var $_font_size = 12;       // Current font size in points.
	var $_compress;             // Flag to compress or not.
	// built in fonts
	var $_core_fonts = array('courier'      => 'Courier',
	                         'courierB'     => 'Courier-Bold',
	                         'courierI'     => 'Courier-Oblique',
	                         'courierBI'    => 'Courier-BoldOblique',
	                         'helvetica'    => 'Helvetica',
	                         'helveticaB'   => 'Helvetica-Bold',
	                         'helveticaI'   => 'Helvetica-Oblique',
	                         'helveticaBI'  => 'Helvetica-BoldOblique',
	                         'times'        => 'Times-Roman',
	                         'timesB'       => 'Times-Bold',
	                         'timesI'       => 'Times-Italic',
	                         'timesBI'      => 'Times-BoldItalic',
	                         'symbol'       => 'Symbol',
	                         'zapfdingbats' => 'ZapfDingbats');
	var $_fill_color = '0 g';   // Color used on text and fills.
	var $_draw_color = '0 G';   // Line draw color.
	var $_line_width = 1;       // Drawing line width.
	var $_images = array();     // An array of used images.
	
	/**
	 * CTOR
	 *
	 * @param float $cvt conversion factor
	 */
	public function __construct( $cvt = 1 )
	{
		$this->cvt = $cvt;
	}
	
	/**
	 * Get the compression on the doc
	 *
	 * @param bool $compress 
	 * @return void
	 */
	public function setCompression($compress)
	{   
	    /* If no gzcompress function is available then default to
	     * false. */
	    $this->_compress = (function_exists('gzcompress') ? $compress : false);
	}	
		
	/**
	 * Open the document
	 *
	 * @return void
	 */
	public function open()
	{   
	    $this->_state = 1;          // Set state to initialised.
	    $this->_out('%PDF-1.3');    // Output the PDF header.
	}
	
	/**
	 * Add a new page to the document
	 *
	 * @return void
	 */
	public function addPage()
	{   
	    $this->_page++;                   // Increment page count.
	    $this->_pages[$this->_page] = ''; // Start the page buffer.
	    $this->_state = 2;                // Set state to page
	                                      // opened.
	    /* Check if font has been set before this page. */
	    if ($this->_font_family) {
	        $this->setFont($this->_font_family, $this->_font_style, $this->_font_size);
	    }
	    /* Check if fill color has been set before this page. */
	    if ($this->_fill_color != '0 g') {
	        $this->_out($this->_fill_color);
	    }   
	    /* Check if draw color has been set before this page. */
	    if ($this->_draw_color != '0 G') {
	        $this->_out($this->_draw_color);
	    }
	    /* Check if line width has been set before this page. */
	    if ($this->_line_width != 1) {
	        $this->_out($this->_line_width);
	    }
	}
	
	/**
	 * Set the font
	 *
	 * @param string $family 
	 * @param string $style 
	 * @param string $size 
	 * @return void
	 */
	public function setFont($family, $style = '', $size = null)
	{
	    $family = strtolower($family);
	    if ($family == 'arial') {               // Use helvetica.
	        $family = 'helvetica';
	    } elseif ($family == 'symbol' ||        // No styles for
	              $family == 'zapfdingbats') {  // these two fonts.
	        $style = '';
	    }

	    $style = strtoupper($style);
	    if ($style == 'IB') {                   // Accept any order
	        $style = 'BI';                      // of B and I.
	    }

	    if (is_null($size)) {                   // No size specified,
	        $size = $this->_font_size;          // use current size.
	    }

	    if ($this->_font_family == $family &&   // If font is already
	        $this->_font_style == $style &&     // current font
	        $this->_font_size == $size) {       // simply return.
	        return;
	    }

	    /* Set the font key. */
	    $fontkey = $family . $style;

	    if (!isset($this->_fonts[$fontkey])) {  // Test if cached.
	        $i = count($this->_fonts) + 1;      // Increment font
	        $this->_fonts[$fontkey] = array(    // object count and
	            'i'    => $i,                   // store cache.
	            'name' => $this->_core_fonts[$fontkey]);
	    }

	    /* Store current font information. */
	    $this->_font_family  = $family;
	    $this->_font_style   = $style;
	    $this->_font_size    = $size;
	    $this->_current_font = $this->_fonts[$fontkey];

	    /* Output font information if at least one page has been
	     * defined. */
	    if ($this->_page > 0) {
	        $this->_out(sprintf('BT /F%d %.2f Tf ET', $this->_current_font['i'], $this->_font_size));
	    }
	}
	
	/**
	 * Set the font size
	 *
	 * @param string $size 
	 * @return void
	 */
	public function setFontSize($size)
	{
	    if ($this->_font_size == $size) {   // If already current
	        return;                         // size simply return.
	    }

	    $this->_font_size = $size;          // Set the font.

	    /* Output font information if at least one page has been
	     * defined. */
	    if ($this->_page > 0) {
	        $this->_out(sprintf('BT /F%d %.2f Tf ET',
	                            $this->_current_font['i'],
	                            $this->_font_size));
	    }
	}

	/**
	 * undocumented function
	 *
	 * @param string $x 
	 * @param string $y 
	 * @param string $text 
	 * @param string $rot 
	 * @return void
	 */
	public function text($x, $y, $text, $rot = 0)
	{
		$x = $x * $this->cvt;
		$y = $y * $this->cvt;
		
		$out = 'BT ';
	    $text = $this->_escape($text);    // Escape any harmful
	                                      // characters.
		
		if( $rot ) {
			$rad = $rot * 3.1415926 / 180.0;
			$out .= sprintf("%.3f %.3f %.3f %.3f %.3f %.3f Tm (%s) Tj ET ", cos($rad), sin($rad), -sin($rad), cos($rad), $x, $this->_h - $y, $text );
		} else {

		    $out .= sprintf('%.2f %.2f Td (%s) Tj ET',
		                   $x, $this->_h - $y, $text);			
		}
		
	    $this->_out($out);
	}
	
	/**
	 * undocumented function
	 *
	 * @param string $cs 
	 * @param string $c1 
	 * @param string $c2 
	 * @param string $c3 
	 * @param string $c4 
	 * @return void
	 */
	public function setFillColor($cs = 'rgb', $c1, $c2 = 0, $c3 = 0, $c4 = 0)
	{
	    $cs = strtolower($cs);
	    if ($cs == 'rgb') {
	        /* Using a three component RGB color. */
	        $this->_fill_color = sprintf('%.3f %.3f %.3f rg', $c1, $c2, $c3);
	    } elseif ($cs == 'cmyk') {
	        /* Using a four component CMYK color. */
	        $this->_fill_color = sprintf('%.3f %.3f %.3f %.3f k', $c1, $c2, $c3, $c4);
	    } else {
	        /* Grayscale one component color. */
	        $this->_fill_color = sprintf('%.3f g', $c1);
	    }
	    /* If document started output to buffer. */
	    if ($this->_page > 0) {
	        $this->_out($this->_fill_color);
	    }
	}
	
	/**
	 * Set the drawing color
	 *
	 * @param string $cs 
	 * @param string $c1 
	 * @param string $c2 
	 * @param string $c3 
	 * @param string $c4 
	 * @return void
	 */
	public function setDrawColor($cs = 'rgb', $c1, $c2 = 0, $c3 = 0, $c4 = 0)
	{   
	    $cs = strtolower($cs);
	    if ($cs == 'rgb') {
	        $this->_draw_color = sprintf('%.3f %.3f %.3f RG',
	                                     $c1, $c2, $c3);
	    } elseif ($cs == 'cmyk') {
	        $this->_draw_color = sprintf('%.3f %.3f %.3f %.3f K',
	                                     $c1, $c2, $c3, $c4);
	    } else {
	        $this->_draw_color = sprintf('%.3f G', $c1);
	    }   
	    /* If document started output to buffer. */
	    if ($this->_page > 0) {
	        $this->_out($this->_draw_color);
	    }
	}
	
	/**
	 * Draw a line
	 *
	 * @param string $x1 
	 * @param string $y1 
	 * @param string $x2 
	 * @param string $y2 
	 * @return void
	 */
	public function line($x1, $y1, $x2, $y2)
	{   
	    $this->_out(sprintf('%.2f %.2f m %.2f %.2f l S', $x1 * $this->cvt, $this->_h - ($y1 * $this->cvt), $x2 * $this->cvt, $this->_h - ($y2 * $this->cvt)));
	}
	
	/**
	 * Draw a rectangle
	 *
	 * @param string $x 
	 * @param string $y 
	 * @param string $width 
	 * @param string $height 
	 * @param string $style 
	 * @return void
	 */
	public function rect($x, $y, $width, $height, $style = '')
	{
		$x = $x * $this->cvt;
		$y = $y * $this->cvt;
		$width = $width * $this->cvt;
		$height = $height * $this->cvt;
		
	    $style = strtolower($style);
	    if ($style == 'f') {
	        $op = 'f';      // Style is fill only.
	    } elseif ($style == 'fd' || $style == 'df') {
	        $op = 'B';      // Style is fill and stroke.
	    } else {
	        $op = 'S';      // Style is stroke only.
	    }

	    $this->_out(sprintf('%.2f %.2f %.2f %.2f re %s', $x , $this->_h - $y, $width, -$height, $op));
	}			
	
	/**
	 * Draw a circle
	 *
	 * @param string $x 
	 * @param string $y 
	 * @param string $r 
	 * @param string $style 
	 * @return void
	 */
	public function circle($x, $y, $r, $style = '')
	{
		$x = $x * $this->cvt;
		$y = $y * $this->cvt;
		$r = $r * $this->cvt;
		
	    $style = strtolower($style);
	    if ($style == 'f') {
	        $op = 'f';      // Style is fill only.
	    } elseif ($style == 'fd' || $style == 'df') {
	        $op = 'B';      // Style is fill and stroke.
	    } else {
	        $op = 'S';      // Style is stroke only.
	    }

	    $y = $this->_h - $y;                 // Adjust y value.
	    $b = $r * 0.552;                     // Length of the Bezier
	                                         // controls.
	    /* Move from the given origin and set the current point
	     * to the start of the first Bezier curve. */
	    $c = sprintf('%.2f %.2f m', $x - $r, $y);
	    $x = $x - $r;
	    /* First circle quarter. */
	    $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
	                  $x, $y + $b,           // First control point.
	                  $x + $r - $b, $y + $r, // Second control point.
	                  $x + $r, $y + $r);     // Final point.
	    /* Set x/y to the final point. */
	    $x = $x + $r;
	    $y = $y + $r;
	    /* Second circle quarter. */
	    $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
	                  $x + $b, $y,
	                  $x + $r, $y - $r + $b,
	                  $x + $r, $y - $r);
	    /* Set x/y to the final point. */
	    $x = $x + $r;
	    $y = $y - $r;
	    /* Third circle quarter. */
	    $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c',
	                  $x, $y - $b,
	                  $x - $r + $b, $y - $r,
	                  $x - $r, $y - $r);
	    /* Set x/y to the final point. */
	    $x = $x - $r;
	    $y = $y - $r;
	    /* Fourth circle quarter. */
	    $c .= sprintf(' %.2f %.2f %.2f %.2f %.2f %.2f c %s',
	                  $x - $b, $y,
	                  $x - $r, $y + $r - $b,
	                  $x - $r, $y + $r,
	                  $op);
	    /* Output the whole string. */
	    $this->_out($c);
	}
	
	/**
	 * Set the line width
	 *
	 * @param string $width 
	 * @return void
	 */
	public function setLineWidth($width)
	{
	    $this->_line_width = $width;
	    if ($this->_page > 0) {
	        $this->_out(sprintf('%.2f w', $width));
	    }
	}		
		
	/**
	 * Draw an image
	 *
	 * @param string $file 
	 * @param string $x 
	 * @param string $y 
	 * @param string $width 
	 * @param string $height 
	 * @return void
	 */
	public function image($file, $x, $y, $width = 0, $height = 0)
	{
		$x = $x * $this->cvt;
		$y = $y * $this->cvt;
		if( $width ) {
			$width = $width * $this->cvt;
		}
		if( $height ) {
			$height = $height * $this->cvt;			
		}
		
	    if (!isset($this->_images[$file])) {
	        /* First use of requested image, get the extension. */
	        if (($pos = strrpos($file, '.')) === false) {
	            die(sprintf('Image file %s has no extension and no type was specified', $file));
	        }
	        $type = strtolower(substr($file, $pos + 1));

	        /* Check the image type and parse. */
	        if ($type == 'jpg' || $type == 'jpeg') {
	            $info = $this->_parseJPG($file);
	        } else {
	            die(sprintf('Unsupported image file type: %s', $type));
	        }
	        /* Set the image object id. */
	        $info['i'] = count($this->_images) + 1;
	        /* Set image to array. */
	        $this->_images[$file] = $info;
	    } else {
	        $info = $this->_images[$file];          // Known image, retrieve
	                                                // from array.
	    }

	    /* If not specified, do automatic width and height
	     * calculations, either setting to original or
	     * proportionally scaling to one or the other given
	     * dimension. */
	    if (empty($width) && empty($height)) {
	        $width = $info['w'];
	        $height = $info['h'];
	    } elseif (empty($width)) {
	        $width = $height * $info['w'] / $info['h'];
	    } elseif (empty($height)) {
	        $height = $width * $info['h'] / $info['w'];
	    }

	    $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q', $width, $height, $x, $this->_h - ($y + $height), $info['i']));
	}		
			
	/**
	 * Close the document
	 *
	 * @return void
	 * @author J Knight
	 */
	public function close()
	{
	    if ($this->_page == 0) {    // If not yet initialised, add
	        $this->addPage();       // one page to make this a valid
	    }                           // PDF.

	    $this->_state = 1;          // Set the state page closed.
	
		/* Pages and resources. */
	    $this->_putPages();
	    $this->_putResources();	
		/* Print some document info. */
	    $this->_newobj();
	    $this->_out('<<');
	    $this->_out('/Producer (My First PDF Class)');
	    $this->_out(sprintf('/CreationDate (D:%s)',
	                        date('YmdHis')));
	    $this->_out('>>');
	    $this->_out('endobj');
	
	   /* Print catalog. */
	    $this->_newobj();
	    $this->_out('<<');
	    $this->_out('/Type /Catalog');
	    $this->_out('/Pages 1 0 R');
	    $this->_out('/OpenAction [3 0 R /FitH null]');
	    $this->_out('/PageLayout /OneColumn');
	    $this->_out('>>');
	    $this->_out('endobj');
	   /* Print cross reference. */
	    $start_xref = strlen($this->_buffer); // Get the xref offset.
	    $this->_out('xref');                  // Announce the xref.
	    $this->_out('0 ' . ($this->_n + 1));  // Number of objects.
	    $this->_out('0000000000 65535 f ');
	    /* Loop through all objects and output their offset. */
	    for ($i = 1; $i <= $this->_n; $i++) {
	        $this->_out(sprintf('%010d 00000 n ', $this->_offsets[$i]));
	    }

	    /* Print trailer. */
	    $this->_out('trailer');
	    $this->_out('<<');
	    /* The total number of objects. */
	    $this->_out('/Size ' . ($this->_n + 1));
	    /* The root object. */
	    $this->_out('/Root ' . $this->_n . ' 0 R');
	    /* The document information object. */
	    $this->_out('/Info ' . ($this->_n - 1) . ' 0 R');
	    $this->_out('>>');
	    $this->_out('startxref');
	    $this->_out($start_xref);  // Where to find the xref.
	    $this->_out('%%EOF');
	    $this->_state = 3;         // Set the document state to
	                               // closed.
	}

	/**
	 * Output the docment to the browser
	 *
	 * @param string $filename 
	 * @return void
	 * @author J Knight
	 */
	public function output($filename)
	{
	    if ($this->_state < 3) {    // If document not yet closed
	        $this->close();         // close it now.
	    }

	    /* Make sure no content already sent. */
	    if (headers_sent()) {
	        die('Unable to send PDF file, some data has already been output to browser.');
	    }

	    /* Offer file for download and do some browser checks
	     * for correct download. */
	    $agent = trim($_SERVER['HTTP_USER_AGENT']);
	    if ((preg_match('|MSIE ([0-9.]+)|', $agent, $version)) ||
	        (preg_match('|Internet Explorer/([0-9.]+)|', $agent, $version))) {
	        header('Content-Type: application/x-msdownload');
	        Header('Content-Length: ' . strlen($this->_buffer));
	        if ($version == '5.5') {
	            header('Content-Disposition: filename="' . $filename . '"');
	        } else {
	            header('Content-Disposition: attachment; filename="' . $filename . '"');
	        }
	    } else {
	        Header('Content-Type: application/pdf');
	        Header('Content-Length: ' . strlen($this->_buffer));
	        Header('Content-disposition: attachment; filename=' . $filename);
	    }
	    echo $this->_buffer;
	}

	// ===================================================================
	// P R I V A T E / P R O T E C T E D  M E T H O D S
	// ===================================================================
	
	/**
	 * Create a new pdf object
	 *
	 * @return void
	 */
	protected function _newobj()
	{
	    /* Increment the object count. */
	    $this->_n++;
	    /* Save the byte offset of this object. */
	    $this->_offsets[$this->_n] = strlen($this->_buffer);
	    /* Output to buffer. */
	    $this->_out($this->_n . ' 0 obj');
	}
	
	/**
	 * Output the pages to the buffer
	 *
	 * @return void
	 * @author J Knight
	 */
	protected function _putPages()
	{
	    /* If compression is required set the compression tag. */
	    $filter = ($this->_compress) ? '/Filter /FlateDecode ' : '';
	    /* Print out pages, loop through each. */
	    for ($n = 1; $n <= $this->_page; $n++) {
	        $this->_newobj();                 // Start a new object.
	        $this->_out('<</Type /Page');     // Object type.
	        $this->_out('/Parent 1 0 R');
	        $this->_out('/Resources 2 0 R');
	        $this->_out('/Contents ' . ($this->_n + 1) . ' 0 R>>');
	        $this->_out('endobj');

	        /* If compression required gzcompress() the page content. */
	        $p = ($this->_compress) ? gzcompress($this->_pages[$n]) : $this->_pages[$n];

	        /* Output the page content. */
	        $this->_newobj();                 // Start a new object.
	        $this->_out('<<' . $filter . '/Length ' . strlen($p) . '>>');
	        $this->_putStream($p);            // Output the page.
	        $this->_out('endobj');
	    }

	    /* Set the offset of the first object. */
	    $this->_offsets[1] = strlen($this->_buffer);
	    $this->_out('1 0 obj');
	    $this->_out('<</Type /Pages');
	    $kids = '/Kids [';
	    for ($i = 0; $i < $this->_page; $i++) {
	        $kids .= (3 + 2 * $i) . ' 0 R ';
	    }   
	    $this->_out($kids . ']');
	    $this->_out('/Count ' . $this->_page);
	    /* Output the page size. */
	    $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',
	                        $this->_w, $this->_h));
	    $this->_out('>>');
	    $this->_out('endobj');
	}
	
	/**
	 * Output to the stream 
	 *
	 * @param string $s 
	 * @return void
	 */
	protected function _putStream($s)
	{
	    $this->_out('stream');
	    $this->_out($s);
	    $this->_out('endstream');
	}
	
	/**
	 * Save the resources
	 *
	 * @return void
	 */
	protected function _putResources()
	{
	    /* Output any fonts. */
	    $this->_putFonts();
		$this->_putimages();
		

	    /* Resources are always object number 2. */
	    $this->_offsets[2] = strlen($this->_buffer);
	    $this->_out('2 0 obj');
	    $this->_out('<</ProcSet [/PDF /Text]');
	    $this->_out('/Font <<');
	    foreach ($this->_fonts as $font) {
	        $this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
	    }
	    $this->_out('>>');
		if (count($this->_images)) {     // Loop through any images
		        $this->_out('/XObject <<');  // and output the objects.
		        foreach ($this->_images as $image) {
		            $this->_out('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
		        }
		        $this->_out('>>');
		    }
		$this->_out('>>');
	    $this->_out('endobj');
	}		
	
	/**
	 * Output the fonts
	 *
	 * @return void
	 */
	protected function _putFonts()
	{
	    /* Print out font details. */
	    foreach ($this->_fonts as $k => $font) {
	        $this->_newobj();
	        $this->_fonts[$k]['n'] = $this->_n;
	        $name = $font['name'];
	        $this->_out('<</Type /Font');
	        $this->_out('/BaseFont /' . $name);
	        $this->_out('/Subtype /Type1');
	        if ($name != 'Symbol' && $name != 'ZapfDingbats') {
	            $this->_out('/Encoding /WinAnsiEncoding');
	        }
	        $this->_out('>>');
	        $this->_out('endobj');
	    }
	}

	/**
	 * Output the images
	 *
	 * @return void
	 */
	protected function _putImages()
	{
	    /* Output any images. */
	    $filter = ($this->_compress) ? '/Filter /FlateDe ' : '';
	    foreach ($this->_images as $file => $info) {
	        $this->_newobj();
	        $this->_images[$file]['n'] = $this->_n;
	        $this->_out('<</Type /XObject');
	        $this->_out('/Subtype /Image');
	        $this->_out('/Width ' . $info['w']);    // Image width.
	        $this->_out('/Height ' . $info['h']);   // Image height.
	        $this->_out('/ColorSpace /' . $info['cs']); //Colorspace
	        if ($info['cs'] == 'DeviceCMYK') {
	            $this->_out('/De [1 0 1 0 1 0 1 0]');
	        }
	        $this->_out('/BitsPerComponent ' . $info['bpc']); // Bits
	        $this->_out('/Filter /' . $info['f']);  // Filter used.
	        $this->_out('/Length ' . strlen($info['data']) . '>>');
	        $this->_putStream($info['data']);       // Image data.
	        $this->_out('endobj');
	    }
	}	
	
	/**
	 * Parse jpeg header
	 *
	 * @param string $file 
	 * @return void
	 */
	protected function _parseJPG($file)
	{   
	    /* Extract info from the JPEG file. */
	    $img = getimagesize($file);
	    if (!$img) {
	        die(sprintf('Missing or incorrect image file: %s', $file));
	    }
	    /* Check if dealing with an actual JPEG. */
	    if ($img[2] != 2) {
	        die(sprintf('Not a JPEG file: %s', $file));
	    }
	    /* Get the image colorspace. */
	    if (!isset($img['channels']) || $img['channels'] == 3) {
	        $colspace = 'DeviceRGB';
	    } elseif ($img['channels'] == 4) {
	        $colspace = 'DeviceCMYK';
	    } else {
	        $colspace = 'DeviceGray';
	    }
	    $bpc = isset($img['bits']) ? $img['bits'] : 8;

	    /* Read the whole file. */
	    $f = fopen($file, 'rb');
	    $data = fread($f, filesize($file));
	    fclose($f);

	    return array('w' => $img[0], 'h' => $img[1], 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'DCTDecode', 'data' => $data);
	}		

		
	/**
	 * Escae a string for postscript
	 *
	 * @param string $s 
	 * @return void
	 */
	protected function _escape($s)
	{   
	    $s = str_replace('\\', '\\\\', $s);   // Escape any '\\'
	    $s = str_replace('(', '\\(', $s);     // Escape any '('
	    return str_replace(')', '\\)', $s);   // Escape any ')'
	}	
	
	/**
	 * Output
	 *
	 * @param string $s 
	 * @return void
	 */
	protected function _out($s)
	{
	    if ($this->_state == 2) {
	        $this->_pages[$this->_page] .= $s . "\n";
	    } else {
	        $this->_buffer .= $s . "\n";
	    }
	}
			
}