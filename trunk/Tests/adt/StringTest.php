<?php
/**
 *	TestUnit of ADT_String.
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_String
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2009
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.adt.String' );
/**
 *	TestUnit of ADT_String.
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_String
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2009
 *	@version		0.1
 */
class Tests_ADT_StringTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		Tests_MockAntiProtection::createMockClass( 'ADT_String' );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->string	= new ADT_String( "test" );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct1()
	{
		$string		= new ADT_String;
		$assertion	= "";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct2()
	{
		$string		= new ADT_String( "Hello World!" );
		$assertion	= "Hello World!";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__toString()
	{
		$id			= "test123#%";
		$string		= new Tests_ADT_String_MockAntiProtection();
		$string->setProtectedVar( "string", $id );
		
		$assertion	= $id;
		$creation	= $string->__toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords1()
	{
		$string		= new ADT_String( "this is a sentence." );
		$assertion	= TRUE;
		$creation	= $string->capitalizeWords();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "This Is A Sentence.";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'capitalizeWords'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCapitalizeWords2()
	{
		$string		= new ADT_String( "NOTHING TO CAPITALIZE" );
		$assertion	= FALSE;
		$creation	= $string->capitalizeWords();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= "NOTHING TO CAPITALIZE";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'compareTo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCompareTo()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->string->compareTo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstring1()
	{
		$assertion	= 1;
		$creation	= $this->string->countSubstring( "s" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstring2()
	{
		$assertion	= 2;
		$creation	= $this->string->countSubstring( "t" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstring3()
	{
		$assertion	= 0;
		$creation	= $this->string->countSubstring( "a" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstringException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->countSubstring( 1 );
	}

	/**
	 *	Tests Exception of Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstringException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->countSubstring( "e", "not_integer" );
	}

	/**
	 *	Tests Exception of Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstringException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->countSubstring( "e", 0, "not_integer" );
	}

	/**
	 *	Tests Exception of Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstringException4()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->countSubstring( "e", 0, 10 );
	}

	/**
	 *	Tests Exception of Method 'countSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountSubstringException5()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->countSubstring( "e", 10, 1 );
	}

	/**
	 *	Tests Method 'escape'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEscape()
	{
		$string		= new ADT_String( 'test"test\'test' );

		$assertion	= 2;
		$creation	= $string->escape();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'test\\"test\\\'test';
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtend1()
	{
		$assertion	= 0;
		$creation	= $this->string->extend( 4, "#" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtend2()
	{
		$assertion	= 2;
		$creation	= $this->string->extend( 6, "_" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test__";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtend3()
	{
		$assertion	= 2;
		$creation	= $this->string->extend( 6, new ADT_String( "_" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test__";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtend4()
	{
		$assertion	= 2;
		$creation	= $this->string->extend( 6, "_", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "_test_";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtend5()
	{
		$assertion	= 2;
		$creation	= $this->string->extend( 6, "_", TRUE, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "__test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtendException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->extend( "invalid" );
	}

	/**
	 *	Tests Exception of Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtendException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->extend( 3 );
	}

	/**
	 *	Tests Exception of Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtendException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->extend( 10, FALSE );
	}

	/**
	 *	Tests Exception of Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtendException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->extend( 10, "" );
	}

	/**
	 *	Tests Exception of Method 'extend'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExtendException5()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->extend( 10, "#", FALSE, FALSE );
	}

	/**
	 *	Tests Method 'getLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLength1()
	{
		$assertion	= 4;
		$creation	= $this->string->getLength();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLength2()
	{
		$string		= new ADT_String( "" );
		$assertion	= 0;
		$creation	= $string->getLength();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring1_1()
	{
		$assertion	= "test";
		$creation	= (string) $this->string->getSubstring( 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring1_2()
	{
		$assertion	= "est";
		$creation	= (string) $this->string->getSubstring( 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring1_3()
	{
		$assertion	= "st";
		$creation	= (string) $this->string->getSubstring( 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring1_4()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring1_5()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( 4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring2_1()
	{
		$assertion	= "test";
		$creation	= (string) $this->string->getSubstring( -4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring2_2()
	{
		$assertion	= "est";
		$creation	= (string) $this->string->getSubstring( -3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring2_3()
	{
		$assertion	= "st";
		$creation	= (string) $this->string->getSubstring( -2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring2_4()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( -1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_1()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( 0, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_2()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( 0, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_3()
	{
		$assertion	= "te";
		$creation	= (string) $this->string->getSubstring( 0, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_4()
	{
		$assertion	= "tes";
		$creation	= (string) $this->string->getSubstring( 0, 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_5()
	{
		$assertion	= "test";
		$creation	= (string) $this->string->getSubstring( 0, 4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_6()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( 1, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_7()
	{
		$assertion	= "e";
		$creation	= (string) $this->string->getSubstring( 1, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_8()
	{
		$assertion	= "es";
		$creation	= (string) $this->string->getSubstring( 1, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_9()
	{
		$assertion	= "est";
		$creation	= (string) $this->string->getSubstring( 1, 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_10()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( 2, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_11()
	{
		$assertion	= "s";
		$creation	= (string) $this->string->getSubstring( 2, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_12()
	{
		$assertion	= "st";
		$creation	= (string) $this->string->getSubstring( 2, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_13()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( 3, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_14()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( 3, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring3_15()
	{
		$assertion	= "test";
		$creation	= (string) $this->string->getSubstring( 0, 4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_1()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -1, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_2()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( -1, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_3()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -2, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_4()
	{
		$assertion	= "s";
		$creation	= (string) $this->string->getSubstring( -2, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_5()
	{
		$assertion	= "st";
		$creation	= (string) $this->string->getSubstring( -2, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_6()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -3, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_7()
	{
		$assertion	= "e";
		$creation	= (string) $this->string->getSubstring( -3, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_8()
	{
		$assertion	= "es";
		$creation	= (string) $this->string->getSubstring( -3, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_9()
	{
		$assertion	= "est";
		$creation	= (string) $this->string->getSubstring( -3, 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_10()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -4, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_11()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( -4, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_12()
	{
		$assertion	= "te";
		$creation	= (string) $this->string->getSubstring( -4, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_13()
	{
		$assertion	= "tes";
		$creation	= (string) $this->string->getSubstring( -4, 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring4_14()
	{
		$assertion	= "test";
		$creation	= (string) $this->string->getSubstring( -4, 4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_1()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -1, -1 );
		$this->assertEquals( $assertion, $creation );
	}
	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_2()
	{
		$assertion	= "s";
		$creation	= (string) $this->string->getSubstring( -2, -1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_3()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -2, -2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_4()
	{
		$assertion	= "es";
		$creation	= (string) $this->string->getSubstring( -3, -1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_5()
	{
		$assertion	= "e";
		$creation	= (string) $this->string->getSubstring( -3, -2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_6()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -3, -3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_7()
	{
		$assertion	= "tes";
		$creation	= (string) $this->string->getSubstring( -4, -1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_8()
	{
		$assertion	= "te";
		$creation	= (string) $this->string->getSubstring( -4, -2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_9()
	{
		$assertion	= "t";
		$creation	= (string) $this->string->getSubstring( -4, -3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstring5_10()
	{
		$assertion	= "";
		$creation	= (string) $this->string->getSubstring( -4, -4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->getSubstring( "not_integer" );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException2()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->getSubstring( 10 );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException3()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->getSubstring( -10 );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->getSubstring( 1, "not_integer" );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException5()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->getSubstring( 3, 2 );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException6()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->getSubstring( 3, -2 );
	}

	/**
	 *	Tests Exception of Method 'getSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSubstringException7()
	{
		$this->setExpectedException( 'OutOfBoundsException' );
		$this->string->getSubstring( -1, 2 );
	}

	/**
	 *	Tests Method 'hasSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSubstring1()
	{
		$assertion	= TRUE;
		$creation	= $this->string->hasSubstring( "test" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSubstring2()
	{
		$assertion	= TRUE;
		$creation	= $this->string->hasSubstring( "es" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSubstring3()
	{
		$assertion	= TRUE;
		$creation	= $this->string->hasSubstring( "t" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasSubstring'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasSubstring4()
	{
		$assertion	= FALSE;
		$creation	= $this->string->hasSubstring( "not_having" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'render'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRender()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->string->render();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'repeat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRepeat1()
	{
		$assertion	= 0;
		$creation	= $this->string->repeat( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'repeat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRepeat2()
	{
		$assertion	= 4;
		$creation	= $this->string->repeat( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testtest";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'repeat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRepeat3()
	{
		$assertion	= 8;
		$creation	= $this->string->repeat( 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testtesttest";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'repeat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRepeatException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->repeat( "not_an_integer" );
	}

	/**
	 *	Tests Exception of Method 'repeat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRepeatException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->string->repeat( -1 );
	}

	/**
	 *	Tests Method 'replace'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplace1()
	{
		$assertion	= 0;
		$creation	= $this->string->replace( "T", "_", TRUE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'replace'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplace2()
	{
		$assertion	= 0;
		$creation	= $this->string->replace( "T", "_" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'replace'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplace3()
	{
		$assertion	= 2;
		$creation	= $this->string->replace( "T", "_", FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "_es_";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'reverse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReverse1()
	{
		$assertion	= TRUE;
		$creation	= $this->string->reverse();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "tset";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'reverse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReverse2()
	{
		$string		= new ADT_String( "abba" );

		$assertion	= FALSE;
		$creation	= $string->reverse();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "abba";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );

		$string		= new ADT_String( "radar" );
		$assertion	= FALSE;
		$creation	= $string->reverse();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "radar";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'split'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSplit()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->string->split();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toCamelCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCamelCase1()
	{
		$string		= new ADT_String( "test string" );
		$assertion	= TRUE;
		$creation	= $string->toCamelCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestString";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toCamelCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCamelCase2()
	{
		$string		= new ADT_String( "test string" );
		$assertion	= TRUE;
		$creation	= $string->toCamelCase( FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testString";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toCamelCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCamelCase3()
	{
		$string		= new ADT_String( "TestString" );
		$assertion	= FALSE;
		$creation	= $string->toCamelCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestString";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toLowerCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToLowerCase1()
	{
		$assertion	= FALSE;
		$creation	= $this->string->toLowerCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toLowerCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToLowerCase2()
	{
		$string		= new ADT_String( "TEst" );
		$assertion	= TRUE;
		$creation	= $string->toLowerCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toLowerCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToLowerCase3()
	{
		$string		= new ADT_String( "TEST" );
		$assertion	= TRUE;
		$creation	= $string->toLowerCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toLowerCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToLowerCase4()
	{
		$assertion	= FALSE;
		$creation	= $this->string->toLowerCase( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toLowerCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToLowerCase5()
	{
		$string		= new ADT_String( "TEst" );

		$assertion	= TRUE;
		$creation	= $string->toLowerCase( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "tEst";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toUpperCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToUpperCase1()
	{
		$assertion	= TRUE;
		$creation	= $this->string->toUpperCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TEST";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toUpperCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToUpperCase2()
	{
		$string		= new ADT_String( "TEst" );
		$assertion	= TRUE;
		$creation	= $string->toUpperCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TEST";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toUpperCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToUpperCase3()
	{
		$string		= new ADT_String( "TEST" );
		$assertion	= FALSE;
		$creation	= $string->toUpperCase();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TEST";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toUpperCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToUpperCase4()
	{
		$assertion	= TRUE;
		$creation	= $this->string->toUpperCase( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Test";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toUpperCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToUpperCase5()
	{
		$string		= new ADT_String( "TEst" );

		$assertion	= FALSE;
		$creation	= $string->toUpperCase( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TEst";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'trim'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrim()
	{
		$string		= new ADT_String( " \n test \r\t\n\r " );
		$assertion	= 9;
		$creation	= $string->trim();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'unescape'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUnescape()
	{		
		$string		= new ADT_String( 'test\\"test\\\'test' );

		$assertion	= 2;
		$creation	= $string->unescape();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'test"test\'test';
		$creation	= (string) $string;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'wrap'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrap()
	{
		$assertion	= 2;
		$creation	= $this->string->wrap( "<", ">" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "<test>";
		$creation	= (string) $this->string;
		$this->assertEquals( $assertion, $creation );
	}
}
?>