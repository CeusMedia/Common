<?php
/**
 *	TestUnit of Alg_StringTrimmer.
 *	@package		Tests.alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Alg_StringTrimmer.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_StringTrimmer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
class Test_Alg_StringTrimmerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->string	= "abcdefghijklmnop";
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
	}

	/**
	 *	Tests Method 'trim'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrim()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_StringTrimmer::trim();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_StringTrimmer::trimCentric( "not_relevant", 2 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_StringTrimmer::trimCentric( "not_relevant", 3 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_StringTrimmer::trimCentric( "not_relevant", 4, "1234" );
	}

	/**
	 *	Tests Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentric()
	{
		$assertion	= "a...p";
		$creation	= Alg_StringTrimmer::trimCentric( $this->string, 5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ab...p";
		$creation	= Alg_StringTrimmer::trimCentric( $this->string, 6 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ab...op";
		$creation	= Alg_StringTrimmer::trimCentric( $this->string, 7 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>