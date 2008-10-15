<?php
/**
 *	TestUnit of StopWatch.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media..StopWatch' );
/**
 *	TestUnit of StopWatch.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Tests_StopWatchTest extends PHPUnit_Framework_TestCase
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
		$watch	= new Tests_StopWatchInstance();
		$assertion	= 1;
		$creation	= preg_match( "@^0\.[0-9]+ [0-9]+$@", $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'start'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStart()
	{
		$watch	= new Tests_StopWatchInstance();
		$assertion	= 1;
		$creation	= preg_match( "@^0\.[0-9]+ [0-9]+$@", $watch->getProtectedVar( 'microtimeStart' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'stop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStop()
	{
		$watch	= new Tests_StopWatchInstance();
		$watch->stop();
		$assertion	= 1;
		$creation	= preg_match( "@^[0-9]+\.[0-9]+$@", $watch->stop() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTime'.
	 *	@access		public	
	 *	@return		void
	 */
	public function testGetTime()
	{
		$watch	= new Tests_StopWatchInstance();

		$watch->setProtectedVar( 'microtimeStart', "0.00000000 ".time() );
		$watch->setProtectedVar( 'microtimeStop', "0.12345678 ".time() );
		
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
class Tests_StopWatchInstance extends StopWatch
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