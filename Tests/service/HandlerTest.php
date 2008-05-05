<?php
/**
 *	TestUnit of Service_Handler.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Handler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.service/Handler' );
/**
 *	TestUnit of Service_Handler.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Handler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Service_HandlerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Service_Handler::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'handle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHandle()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Service_Handler::handle();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'handle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHandleException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		Service_Handler::handle();
	}

	/**
	 *	Tests Exception of Method 'handle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHandleException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'RuntimeException' );
		Service_Handler::handle();
	}

	/**
	 *	Tests Method 'compressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCompressResponse()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Service_Handler::compressResponse();
		$this->assertEquals( $assertion, $creation );
	}
}
?>