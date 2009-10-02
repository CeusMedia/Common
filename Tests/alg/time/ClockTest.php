<?php
/**
 *	TestUnit of Alg_Time_Clock.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Time_Clock
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.alg.time.Clock' );
/**
 *	TestUnit of Alg_Time_Clock.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Time_Clock
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Tests_Alg_Time_ClockTest extends PHPUnit_Framework_TestCase
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
		$clock	= new Tests_Alg_Time_ClockInstance();
		$assertion	= 1;
		$creation	= preg_match( "@^0\.[0-9]+ [0-9]+$@", $clock->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'start'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStart()
	{
		$clock	= new Tests_Alg_Time_ClockInstance();
		$assertion	= 1;
		$creation	= preg_match( "@^0\.[0-9]+ [0-9]+$@", $clock->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStop()
	{
		$clock	= new Tests_Alg_Time_ClockInstance();
		$clock->stop();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", $clock->stop() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTime'.
	 *	@access		public	
	 *	@return		void
	 */
	public function testGetTime()
	{
		$clock	= new Tests_Alg_Time_ClockInstance();

		$clock->setProtectedVar( 'microtimeStart', "0.00000000 ".time() );
		$clock->setProtectedVar( 'microtimeStop', "0.12345678 ".time() );
		
		$assertion	= 123.457;
		$creation	= $clock->getTime( 3, 3 );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 123457;
		$creation	= $clock->getTime( 6, 0 );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0.123;
		$creation	= $clock->getTime( 0 );
		$this->assertEquals( $assertion, $creation );
	}
}
class Tests_Alg_Time_ClockInstance extends Alg_Time_Clock
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function setProtectedVar( $varName, $varValue )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		$this->$varName	= $varValue;
	}
}
?>