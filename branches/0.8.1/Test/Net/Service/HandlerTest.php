<?php
/**
 *	TestUnit of Net Service Handler.
 *	@package		Tests.net.service
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Net Service Handler.
 *	@package		Tests.net.service
 *	@extends		Test_Case
 *	@uses			Net_Service_Handler
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_HandlerTest extends Test_Case
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
		$creation	= Net_Service_Handler::__construct();
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
		$creation	= Net_Service_Handler::handle();
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
		Net_Service_Handler::handle();
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
		Net_Service_Handler::handle();
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
		$creation	= Net_Service_Handler::compressResponse();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
