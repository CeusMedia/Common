<?php
/**
 *	TestUnit of Service_Point.
 *	@package		Tests.net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Service_Point.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Point
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_PointTest extends PHPUnit_Framework_TestCase
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
	public function testConstruct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'callService'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCallService()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::callService();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDefaultServiceFormat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDefaultServiceFormat()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getDefaultServiceFormat();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServiceClass'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServiceClass()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServiceClass();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServiceDescription'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServiceDescription()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServiceDescription();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServiceFormats'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServiceFormats()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServiceFormats();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServices()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServiceExamples'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServiceExamples()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServiceExamples();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getServiceParameters'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetServiceParameters()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getServiceParameters();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSyntax'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSyntax()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getSyntax();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTitle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTitle()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_Service_Point::getTitle();
		$this->assertEquals( $assertion, $creation );
	}
}
?>