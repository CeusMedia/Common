<?php
/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Clock.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Time_Clock
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
final class Test_Alg_Time_ClockTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		Test_MockAntiProtection::createMockClass( "Alg_Time_Clock" );
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
		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'sleep' when enough time to sleep has elapsed.
	 *	@access		public
	 *	@return		void
	 */
	public function testSleep1()
	{
		$time	= microtime( TRUE );

		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->setProtectedVar( 'microtimeStart', $time - 2 );
		$watch->sleep( 1 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microtimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'sleep' when not enough time to sleep has elapsed.
	 *	@access		public
	 *	@return		void
	 */
/*	public function testSleep2()
	{
		$time	= microtime( TRUE );

		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->setProtectedVar( 'microtimeStart', $time );
		$watch->sleep( 1 );

		$assertion	= $time;
		$creation	= $watch->getProtectedVar( 'microtimeStart' );
		$this->assertEquals( $assertion, $creation );
	}*/

	/**
	 *	Tests Method 'speed'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSpeed()
	{
		$time	= microtime( TRUE );

		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->setProtectedVar( 'microtimeStart', $time );
		$watch->speed( 1 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microtimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'start'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStart()
	{
		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStop()
	{
		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->stop();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", $watch->stop() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'usleep' when enough time to sleep has elapsed.
	 *	@access		public
	 *	@return		void
	 */
	public function testUsleep1()
	{
		$time	= microtime( TRUE );

		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->setProtectedVar( 'microtimeStart', $time - 2 );
		$watch->usleep( 1000000 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microtimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'speed'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUspeed()
	{
		$time	= microtime( TRUE );

		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$watch->setProtectedVar( 'microtimeStart', $time );
		$watch->uspeed( 1000 );

		$assertion	= $time - 0.001;
		$creation	= $watch->getProtectedVar( 'microtimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTime'.
	 *	@access		public	
	 *	@return		void
	 */
	public function testGetTime()
	{
		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();

		$watch->setProtectedVar( 'microtimeStart', (float) time().".00000000" );
		$watch->setProtectedVar( 'microtimeStop', (float) time().".12345678" );
		
		$assertion	= 123.457;
		$creation	= $watch->getTime( 3, 3 );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 123457;
		$creation	= $watch->getTime( 6, 0 );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0.123;
		$creation	= $watch->getTime( 0 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>