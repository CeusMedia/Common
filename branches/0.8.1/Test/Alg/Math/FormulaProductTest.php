<?php
/**
 *	TestUnit of Alg_Math_FormulaProduct.
 *	@package		Tests.math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Alg_Math_FormulaProduct.
 *	@package		Tests.math
 *	@extends		Test_Case
 *	@uses			Alg_Math_FormulaProduct
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Test_Alg_Math_FormulaProductTest extends Test_Case
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
		new Alg_Math_FormulaProduct( "no_object", "no_object" );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Alg_Math_FormulaProduct( new Alg_Math_Formula( "x", "x" ), "no_object" );
	}

	/**
	 *	Tests Method 'calculate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculate()
	{
		$interval	= new Alg_Math_CompactInterval( 1, 4 );
		$formula	= new Alg_Math_Formula( "x", "x" );
		$product	= new Alg_Math_FormulaProduct( $formula, $interval );
		$creation	= $product->calculate();
		$assertion	= 24;
		$this->assertEquals( $assertion, $creation );

		$interval	= new Alg_Math_CompactInterval( 1, 4 );
		$formula	= new Alg_Math_Formula( "2*x*y", array( "x", "y" ) );
		$product	= new Alg_Math_FormulaProduct( $formula, $interval );
		$creation	= $product->calculate( 3 );
		$assertion	= 31104;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
