<?php
/** 
 * A simple unit tester and suite for CodeIgniter.
 *
 * Note: this is not strictly a CI library. It is not loadable
 * via the normal means of $CI->load->library('unittest'); Don't
 * do that!!!
 *
 * <code>
 * $ut = new UnitTester( "path/to/tests" );
 * if( $ut->run()) {
 *   $this->load->view('results', array('results' => $ut ));
 * } else {
 *   echo "Error running tests.";   
 * } 
 * </code> 
 *
 * test files should be called Test[*].php and should have a class of the same
 * name that extends UnitTest within. Individual methods that should be run
 * begin with a lower cast test*. Methods 'setUp' and 'tearDown' are run before
 * and after all test are run.
 *
 * TestSomething.php
 * <code>
 * class TestSomething extends UnitTest
 * {
 *    function testStuff()
 *    {
 *       $this->assertEqual( 1, 1 );
 *    }
 * }
 * </code>
 *
 * - updated naming scheme to match pythons - 20100401 - jk
 * - adapted for CodeIgniter 								- 20090130 - jk
 *
 * @category       UnitTesting
 * @package        Talon
 * @author         J. Knight <jim at talonedge dot com>
 * @copyright      Copyright (c) 2006-2010, J. Knight
 */

/** **********************************************************************
 * Base class for a unit test
 *
 * Tests should extend this class. The file the test resides in should
 * be called Test[SomeName].php and the class called TestSomeName.
 *
 * Individual test methods inside the class should begin with the word "test"
 * e.g. <code>function testMyFunkyThang()</code>
 *
 * Always use the "assert..." methods to pass or fail tests. A failed assert
 * will stop further running of that test method but any other methods will
 * still be run and the setUp and tearDown methods will always run.
 *
 * Access to the CodeIgniter core is already setup via $this->CI, so you
 * can load stuff through that in your constructor or test methods.
 * e.g. 
 * <code>
 *	$this->CI->load->model('ticket/Ticket_model', '', true);
 *  $this->CI->Ticket_model->get_something_or_other();
 * </code>
 *
 */
class UnitTest extends StdClass
{
  public $assert_count = 0;
  
  public function __construct()
  {
      $this->CI =& get_instance();  
  }

  /**
   * Runs before any tests are run
   */
  function setUp()
  {
  }
  
  /**
   * Runs after all tests are run
   */
  function tearDown()
  {
  }
  
  /**
   * Assert that $val == TRUE
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertTrue( $val, $msg = "assertTrue failed" )
  {
    if( !$val ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }

  /**
   * Assert that $val != TRUE
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertFalse( $val, $msg = "assertFalse failed" )
  {
    if( $val ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }
  
  /**
   * Assert that $val == NULL
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertNull( $val, $msg = "assertNull failed" )
  {
    if( $val != NULL ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }

  /**
   * Assert that $val != NULL
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertNotNull( $val, $msg = "assertNotNull failed" )
  {
    if( $val == NULL ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }
  
  /**
   * Assert that $val1 == $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertEquals( $val1, $val2, $msg = NULL ) {
    if( $val1 != $val2 ) {
      if( $msg == NULL ) {
        $msg = "Expected: '$val1' == '$val2'";
      }
      throw new Exception( $msg );
    }
    $this->assert_count++;
  }

  /**
   * Assert that $val1 == $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertEqual( $val1, $val2, $msg = NULL )
  {
    $this->assertEquals( $val1, $val2, $msg );
  }

  /**
   * Assert that $val1 != $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertNotEquals( $val1, $val2, $msg = "assertNotEquals failed" ) {
    if( $val1 == $val2 ) {
      throw new Exception( $msg );
    }
    $this->assert_count++;
  }    

  /**
   * Assert that $val1 != $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertNotEqual( $val1, $val2, $msg = NULL )
  {
    $this->assertNotEquals( $val1, $val2, $msg );
  }

}

/** **********************************************************************
 * A class for unit testing CodeIgniter.
 *
 * This is the class that collects and runs all the tests.
 * I put all my tests in 'APPPATH/tests' but you can put them wherever.
 *
 * e.g.
 * <code>
 *	include_once( APPPATH . '/libraries/unittest.php');
 *
 *	class TestSuite extends Controller
 *	{
 *		function __construct()
 *		{
 *			parent::__construct();
 *		}
 *
 *		function index()
 *		{
 *			$ut = new UnitTestSuite( APPPATH . '/tests' );
 *			$ut->run();
 *			$this->load->view('unittest/header');
 *			$this->load->view('unittest/results', array('results' => $ut));
 *			$this->load->view('unittest/footer');
 *		}
 *
 *	}
 * </code>
 */
class UnitTestSuite
{
	/**
	 * A string used to store the directory in which to look for tests.
	 */
	var $dir;

	// various statistics
	var $num_files = 0;
	var $num_tests = 0;
	var $num_passed = 0;
	var $num_failed = 0;

	/**
	 * An array used to store tests run.
	 */
	var $tests = null;

	/**
	 * Constructor.
	 * 
	 * @param $dir The directory in which to look for tests.
	 */
	function __construct( $dir )
	{
		$this->dir = $dir;
	}
	
	/**
	 * Runs all tests and collects the results in $this->tests.
	 * 
	 * @return false on error or true if everything ran ok
	 */
	function run()
	{
		// reset
		$this->tests = null;
		$this->num_files = 0;
		$this->num_tests = 0;
		$this->num_passed = 0;
		$this->num_failed = 0;
	
		$testlist = $this->get_tests();
		if( $testlist === false ) {
			return false;
		}
			
		foreach( $testlist as $testfile ) {
			$res = $this->run_test( $testfile );
			$this->num_files++;
			foreach( $res as $key => $value ) {
				$this->tests[$key] = $value;
			}
		}
		return true;
	}
	
		
	/**
	 * Runs one unit test file.
	 *
	 * This function returns an array of test results from the tests in the
	 * named file. The array looks like:
	 * <pre>
	 * Array
	 * (
	 *     [TestName] => Array
	 *		(
	 *			[0] => Array
	 * 				(
	 *					["test"]   => "methodname"
	 *					["result"] => result (1 or 0)
	 *					["error"]  => "error message if any"
	 *				)
	 *			...
	 *		)
	 * }
	 * </pre>
	 *
	 * @param $testfile
	 *	A string containing the file path to the test file.
	 * @return
	 *	An array of test results.
	 */
	function run_test( $testfile )
	{
		$result = array();
	
		//include_once( $testfile );
		$fname = basename( $testfile );
		//print "$fname\n";
		$class = substr( $fname, 4, strlen( $fname ) - 8 );
		$cname = 'Test' . $class;
		//print $cname . "\n";
		if( class_exists( $cname )) {
			$test = new $cname();
			
			$result[$cname] = array();
			
			// run the setup method
			if( method_exists( $test, 'setUp' )) {
				$test->setup();
			}
			
			$m = get_class_methods( $test );
			foreach( $m as $mname ) {
				if( substr( $mname, 0, 4 ) == 'test' ) {
					$test->error = '';				
					// run the test
					$ret = TRUE;
					$msg = "";
					try {
					  $test->$mname();
					} catch( Exception $e ) {
					  $ret = FALSE;
					  $msg = $e->getMessage();
					}
					$this->num_tests++;
					if( $ret )
						$this->num_passed++;
					else
						$this->num_failed++;
						
					$result[$cname][] = array( "test" => $mname, 
											               "result" => $ret, 
											               "error" => $msg,
											               "asserts" => $test->assert_count 
											               );
				}
			}
			
			// run the tearDown method
			if( method_exists( $test, 'tearDown' )) {
				$test->tearDown();
			}
		}
		
		return $result;
	}
	
	/**
	 * Returns an array where each element contains the full path to
	 * any valid test file found.
	 *
	 * @return
	 *	An array with the paths to any valid tests found.
	 */
	function get_tests()
	{
	  
		if( !file_exists( $this->dir ))
			return false;
	
		if( !is_readable( $this->dir ))
			return false;
	
		$tests = array();
	
		$dh = opendir( $this->dir );
		while(($file = readdir($dh)) !== false ) {
			if( strlen( $file ) > 8 && substr( $file, -3 ) == 'php' && $file[0] != '.' ) {
				include_once( $this->dir . "/" . $file );
				$class = substr( $file, 4, strlen($file) - 8 );
				$cname = 'Test' . $class;
				if( class_exists( $cname )) {
					$tests[] = "$this->dir/$file";
				}
			}
		}
		return $tests;
	}	
}

?>