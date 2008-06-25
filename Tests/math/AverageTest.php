<?php
/**
 *	TestUnit of Math_Average.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Average
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.math.Average' );
/**
 *	TestUnit of Math_Average.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Average
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Tests_Math_AverageTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Method 'arithmetic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testArithmetic()
	{
		$assertion	= 2;
		$creation	= Math_Average::arithmetic( array( 1, 2, 3 ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2.5;
		$creation	= Math_Average::arithmetic( array( 1, 2, 3, 4 ), 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 10;
		$creation	= Math_Average::arithmetic( array( 5, 5, 15, 15, 10, 0, 20 ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= round( 3 / 2, 4 );
		$creation	= Math_Average::arithmetic( array( 1, 2 ), 4 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'geometric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGeometric()
	{
		$assertion	= 2;
		$creation	= Math_Average::geometric( array( 1, 2, 3 ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2.2;
		$creation	= Math_Average::geometric( array( 1, 2, 3, 4 ), 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 13;
		$creation	= Math_Average::geometric( array( 10, 16.9 ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>