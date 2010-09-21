<?php
/**
 *	TestUnit of Alg_Text_CamelCase.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Text_CamelCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Alg_Text_CamelCase.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Text_CamelCase
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
class Test_Alg_Text_CamelCaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->string1	= "test_alpha__test___RDF string";
		$this->string2	= "Test_alpha__test___RDF string";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		Alg_Text_CamelCase::$lowercaseFirst	= NULL;
		Alg_Text_CamelCase::$lowercaseLetter	= NULL;
	}

	/**
	 *	Tests Method 'convert' using String 1.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertWithString1()
	{
		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, NULL );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, TRUE );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, NULL, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, FALSE, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, TRUE, TRUE );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, NULL, FALSE  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, FALSE, FALSE  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1, TRUE, FALSE  );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convert' using String 2.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertWithString2()
	{
		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2 );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, NULL );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, FALSE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, TRUE );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, NULL, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, FALSE, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, TRUE, TRUE );
		$this->assertEquals( $assertion, $creation );


		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, NULL, FALSE  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, FALSE, FALSE  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string2, TRUE, FALSE  );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convert' using String 1 and static settings.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertWithString1AndStaticSettings()
	{
		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );

		Alg_Text_CamelCase::$lowercaseFirst	= FALSE;
		Alg_Text_CamelCase::$lowercaseLetter	= NULL;

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );

		Alg_Text_CamelCase::$lowercaseFirst	= TRUE;
		Alg_Text_CamelCase::$lowercaseLetter	= NULL;

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );


		Alg_Text_CamelCase::$lowercaseFirst	= NULL;
		Alg_Text_CamelCase::$lowercaseLetter	= FALSE;

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );

		Alg_Text_CamelCase::$lowercaseFirst	= NULL;
		Alg_Text_CamelCase::$lowercaseLetter	= TRUE;

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );


		Alg_Text_CamelCase::$lowercaseFirst	= FALSE;
		Alg_Text_CamelCase::$lowercaseLetter	= FALSE;

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );

		Alg_Text_CamelCase::$lowercaseFirst	= FALSE;
		Alg_Text_CamelCase::$lowercaseLetter	= TRUE;

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );


		Alg_Text_CamelCase::$lowercaseFirst	= TRUE;
		Alg_Text_CamelCase::$lowercaseLetter	= FALSE;

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1  );
		$this->assertEquals( $assertion, $creation );

		Alg_Text_CamelCase::$lowercaseFirst	= TRUE;
		Alg_Text_CamelCase::$lowercaseLetter	= TRUE;

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::convert( $this->string1 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>