<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Clock.
 *	@package		Tests.Alg.Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Time;

use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\Test\Alg\Time\ClockMockAntiProtection as Mock;
use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\Test\MockAntiProtection;

/**
 *	TestUnit of Clock.
 *	@package		Tests.Alg.Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class ClockTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		MockAntiProtection::createMockClass( Clock::class );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$watch	= new Mock();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->getProtectedVar( 'microtimeStart' ) );
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

		$watch	= new Mock();
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

		$watch	= new Mock();
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
		$watch	= new Mock();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStop()
	{
		$watch	= new Mock();
		$watch->stop();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->stop() );
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

		$watch	= new Mock();
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

		$watch	= new Mock();
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
		$watch	= new Mock();

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
