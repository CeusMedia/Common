<?php
/**
 *	TestUnit of Math_FormulaSum.
 *	@package		Tests.math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Math_FormulaSum.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_FormulaSum
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Test_Math_FormulaSumTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Math_FormulaSum( "no_object", "no_object" );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Math_FormulaSum( new Math_Formula( "x", "x" ), "no_object" );
	}

	/**
	 *	Tests Method 'calculate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculate()
	{
		$interval	= new Math_CompactInterval( 1, 4 );
		$formula	= new Math_Formula( "x", "x" );
		$sum		= new Math_FormulaSum( $formula, $interval );
		$creation	= $sum->calculate();
		$assertion	= 10;
		$this->assertEquals( $assertion, $creation );

		$interval	= new Math_CompactInterval( 1, 4 );
		$formula	= new Math_Formula( "2*x*y", array( "x", "y" ) );
		$sum		= new Math_FormulaSum( $formula, $interval );
		$creation	= $sum->calculate( 3 );
		$assertion	= 60;
		$this->assertEquals( $assertion, $creation );
	}
}
?>