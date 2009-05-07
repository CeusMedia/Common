<?php
/**
 *	TestUnit of Math_Factorial.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.math.Factorial' );
/**
 *	TestUnit of Math_Factorial.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Math_FactorialTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Exception of Method 'calculate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculateException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Math_Factorial::calculate( -1 );
	}

	/**
	 *	Tests Exception of Method 'calculate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculateException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Math_Factorial::calculate( "no_integer" );
	}

	/**
	 *	Tests Method 'calculate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculate()
	{
		$assertion	= 1;
		$creation	= Math_Factorial::calculate( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= Math_Factorial::calculate( 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 120;
		$creation	= Math_Factorial::calculate( 5 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>