<?php
/**
 *	TestUnit of Math_Formula.
 *	@package		Tests.math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Math_Formula.
 *	@package		Tests.math
 *	@extends		Test_Case
 *	@uses			Math_Formula
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Test_Math_FormulaTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->formula	= new Math_Formula( "x * y", array( "x", "y" ) );
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
		$formula	= new Math_Formula( "x*y", array( "x", "y" ) );
	
		$assertion	= "x*y";
		$creation	= $formula->getExpression();
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= array( "x", "y" );
		$creation	= $formula->getVariables();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$creation	= new Math_Formula( "x", array( "x", "x" ) );
	}

	/**
	 *	Tests Method 'getExpression'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetExpression()
	{
		$assertion	= "x * y";
		$creation	= $this->formula->getExpression();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValue()
	{
		$assertion	= 20;
		$creation	= $this->formula->getValue( 4, 5 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getVariables'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetVariables()
	{
		$assertion	= array( "x", "y" );
		$creation	= $this->formula->getVariables();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "f(x, y) = x * y";
		$creation	= (string) $this->formula;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
