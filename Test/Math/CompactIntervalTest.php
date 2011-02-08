<?php
/**
 *	TestUnit of Math_CompactInterval.
 *	@package		Tests.math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Math_CompactInterval.
 *	@package		Tests.math
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Test_Math_CompactIntervalTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->interval	= new Math_CompactInterval( 3, 9 );
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
		$interval	= new Math_CompactInterval( 1, 2 );
		$assertion	= "I[1;2]";
		$creation	= (string) $interval;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDiameter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDiameter()
	{
		$assertion	= 6;
		$creation	= $this->interval->getDiameter();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStart'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStart()
	{
		$assertion	= 3;
		$creation	= $this->interval->getStart();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getEnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetEnd()
	{
		$assertion	= 9;
		$creation	= $this->interval->getEnd();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setStart'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetStart()
	{
		$this->interval->setStart( 2 );
		
		$assertion	= 2;
		$creation	= $this->interval->getStart();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setStart'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetStartException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->interval->setStart( 12 );
	}

	/**
	 *	Tests Method 'setEnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetEnd()
	{
		$this->interval->setEnd( 10 );
		$assertion	= 10;
		$creation	= $this->interval->getEnd();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setEnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetEndException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->interval->setEnd( 1 );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "I[3;9]";
		$creation	= (string) $this->interval;
		$this->assertEquals( $assertion, $creation );
	}
}
?>