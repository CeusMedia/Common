<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Clock.
 *	@package		Tests.Alg.Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Time;

use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;
use CeusMedia\CommonTest\MockAntiProtection as Mock;

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
		Mock::createMockClass( Clock::class );
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
		$watch		= Mock::getInstance( Clock::class );
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->getProtectedVar( 'microTimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'sleep' when enough time to sleep has elapsed.
	 *	@access		public
	 *	@return		void
	 */
	public function testSleep1()
	{
		$time	= microTime( TRUE );

		$watch	= Mock::getInstance( Clock::class );
		$watch->setProtectedVar( 'microTimeStart', $time - 2 );
		$watch->sleep( 1 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microTimeStart' );
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
		$watch->setProtectedVar( 'microTimeStart', $time );
		$watch->sleep( 1 );

		$assertion	= $time;
		$creation	= $watch->getProtectedVar( 'microTimeStart' );
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

		$watch	= Mock::getInstance( Clock::class );
		$watch->setProtectedVar( 'microTimeStart', $time );
		$watch->speed( 1 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microTimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'start'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStart()
	{
		$watch	= Mock::getInstance( Clock::class );
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->getProtectedVar( 'microTimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStop()
	{
		$watch	= Mock::getInstance( Clock::class );
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

		$watch	= Mock::getInstance( Clock::class );
		$watch->setProtectedVar( 'microTimeStart', $time - 2 );
		$watch->usleep( 1000000 );

		$assertion	= $time - 1;
		$creation	= $watch->getProtectedVar( 'microTimeStart' );
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

		$watch	= Mock::getInstance( Clock::class );
		$watch->setProtectedVar( 'microTimeStart', $time );
		$watch->uspeed( 1000 );

		$assertion	= $time - 0.001;
		$creation	= $watch->getProtectedVar( 'microTimeStart' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTime'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTime()
	{
    /** @var MockAntiProtection $watch */
		$watch	= Mock::getInstance( Clock::class );

		$watch->setProtectedVar( 'microTimeStop', (float) ( time().".12345678" ) );
		$watch->setProtectedVar( 'microTimeStart', (float) ( time().".00000000" ) );

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

