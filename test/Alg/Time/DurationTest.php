<?php
/**
 *	TestUnit of Alg_Time_Duration.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Alg_Time_Duration.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class Test_Alg_Time_DurationTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		Test_MockAntiProtection::createMockClass( "Alg_Time_Clock" );
		$hour	= 3600;
		$day	= 24 * $hour;
		$week	= 7 * $day;
		$this->durations	= array(
			''					=> 0,
			'2s'				=> 2,
			'2m'				=> 2 * 60,
			'2h'				=> 2 * $hour,
			'2d'				=> 2 * $day,
			'2w'				=> 2 * $week,
			'2w 2s'				=> 2 * $week + 2,
			'2w 2m'				=> 2 * $week + 2 * 60,
			'2w 2h'				=> 2 * $week + 2 * $hour,
			'2w 2d'				=> 2 * $week + 2 * $day,
			'2w 2d 2s'			=> 2 * $week + 2 * $day + 2,
			'2w 2d 2m'			=> 2 * $week + 2 * $day + 2 * 60,
			'2w 2d 2h'			=> 2 * $week + 2 * $day + 2 * $hour,
			'2w 2d 2h 2s'		=> 2 * $week + 2 * $day + 2 * $hour + 2,
			'2w 2d 2h 2m'		=> 2 * $week + 2 * $day + 2 * $hour + 2 * 60,
			'2w 2d 2h 2m 2s'	=> 2 * $week + 2 * $day + 2 * $hour + 2 * 60 + 2,
		);
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
		$watch	= new Test_Alg_Time_Clock_MockAntiProtection();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", (string) $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'sleep' when enough time to sleep has elapsed.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertDurationToSeconds()
	{
		$obj	= new Alg_Time_Duration();

		foreach( $this->durations as $duration => $assertion ){
			$creation	= $obj->convertDurationToSeconds( $duration );
			$this->assertEquals( $assertion, $creation );
		}

		$hour	= 3600;
		$day	= 24 * $hour;
		$week	= 7 * $day;
		$durations	= array();
		foreach( $durations as $duration => $assertion ){
			$creation	= $obj->convertDurationToSeconds( $duration );
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'convertSecondsToDuration'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertSecondsToDuration()
	{
		$obj	= new Alg_Time_Duration();
		foreach( $this->durations as $assertion => $seconds ){
			$creation	= $obj->convertSecondsToDuration( $seconds, ' ' );
			$this->assertEquals( $assertion, $creation );
		}

		$hour	= 3600;
		$day	= 24 * $hour;
		$week	= 7 * $day;
		$durations	= array();
		foreach( $durations as $assertion => $seconds ){
			$creation	= $obj->convertSecondsToDuration( $seconds, ' ' );
			$this->assertEquals( $assertion, $creation );
		}
	}


	/**
	 *	Tests Method 'sanitize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSanitize()
	{
		$obj	= new Alg_Time_Duration();

		$durations	= array(
			'61s'				=> '1m 1s',
			'61m 61s'			=> '1h 2m 1s',
			'25h 61m 61s'		=> '1d 2h 2m 1s',
			'8d 25h 61m 61s'	=> '1w 2d 2h 2m 1s',
		);
		foreach( $durations as $duration => $assertion ){
			$creation	= $obj->sanitize( $duration );
			$this->assertEquals( $assertion, $creation );
		}
	}
}
