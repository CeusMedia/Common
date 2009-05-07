<?php
/**
 *	TestUnit of Math_Algebra_LabelMatrix.
 *	@package		Tests.math.algebra
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Algebra_LabelMatrix
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.math.algebra.LabelMatrix' );
/**
 *	TestUnit of Math_Algebra_LabelMatrix.
 *	@package		Tests.math.algebra
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Algebra_LabelMatrix
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Math_Algebra_LabelMatrixTest extends PHPUnit_Framework_TestCase
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
		$rows		= array( "A1", "A2" );
		$columns	= array( "B1", "B2", "B3" );
		$this->matrix	= new Math_Algebra_LabelMatrix( $rows, $columns );
		$this->matrix->setValue( "A1", "B1", 1 );
		$this->matrix->setValue( "A1", "B2", 2 );
		$this->matrix->setValue( "A1", "B3", 3 );
		$this->matrix->setValue( "A2", "B1", 4 );
		$this->matrix->setValue( "A2", "B2", 5 );
		$this->matrix->setValue( "A2", "B3", 6 );
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
		$rows		= array( "A1" );
		$columns	= array( "B1", "B2", "B3" );
		$matrix		= new Math_Algebra_LabelMatrix( $rows, $columns, 10 );
		
		$assertion	= 1;
		$creation	= $matrix->getRowNumber();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 3;
		$creation	= $matrix->getColumnNumber();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 10;
		$creation	= $matrix->getValue( "A1", "B2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 10;
		$creation	= $matrix->getValue( 0, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Math_Algebra_LabelMatrix( array(), array( "a", "b", "c", "d", "e" ) );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Math_Algebra_LabelMatrix( array( "a", "b", "c", "d", "e" ), array() );
	}

	/**
	 *	Tests Method 'clear'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClear()
	{
		$this->matrix->clear( 10 );
		$assertion	= array(
			"A1"	=> array(
				"B1"	=> 10,
				"B2"	=> 10,
				"B3"	=> 10
			),
			"A2"	=> array(
				"B1"	=> 10,
				"B2"	=> 10,
				"B3"	=> 10
			)
		);
		$creation	= $this->matrix->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getRowNumber'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRowNumber()
	{
		$assertion	= 2;
		$creation	= $this->matrix->getRowNumber();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getColumnNumber'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnNumber()
	{
		$assertion	= 3;
		$creation	= $this->matrix->getColumnNumber();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getColumn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumn()
	{
		$assertion	= array( "A1" => 1, "A2" => 4 );
		$creation	= $this->matrix->getColumn( "B1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "A1" => 2, "A2" => 5 );
		$creation	= $this->matrix->getColumn( "B2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "A1" => 3, "A2" => 6 );
		$creation	= $this->matrix->getColumn( "B3" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getColumn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->getColumn( "B5" );
	}

	/**
	 *	Tests Method 'getRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRow()
	{
		$assertion	= array( "B1" => 1, "B2" => 2, "B3" => 3 );
		$creation	= $this->matrix->getRow( "A1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "B1" => 4, "B2" => 5, "B3" => 6 );
		$creation	= $this->matrix->getRow( "A2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRowException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->getRow( "A5" );
	}

	/**
	 *	Tests Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValue()
	{
		$assertion	= 1;
		$creation	= $this->matrix->getValue( "A1", "B1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->matrix->getValue( "A1", "B2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->matrix->getValue( "A1", "B3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->matrix->getValue( "A2", "B3" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValueException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->getValue( "A5", "B1" );
	}

	/**
	 *	Tests Exception of Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValueException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->getValue( "A1", "B5" );
	}

	/**
	 *	Tests Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValue()
	{
		$this->matrix->setValue( "A1", "B3", 10 );
		$assertion	= 10;
		$creation	= $this->matrix->getValue( "A1", "B3" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValueException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->setValue( "A5", "B1", 1 );
	}

	/**
	 *	Tests Exception of Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValueException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->setValue( "A1", "B5", 1 );
	}

	/**
	 *	Tests Method 'transpose'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTranspose()
	{
		$this->matrix->transpose();
		$assertion	= array( "B1" => 1, "B2" => 2, "B3" => 3 );
		$creation	= $this->matrix->getColumn( "A1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "B1" => 4, "B2" => 5, "B3" => 6 );
		$creation	= $this->matrix->getColumn( "A2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'swapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRows()
	{
		$this->matrix->swapRows( "A1", "A2" );
		
		$assertion	= array( "B1" => 1, "B2" => 2, "B3" => 3 );
		$creation	= $this->matrix->getRow( "A2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "B1" => 4, "B2" => 5, "B3" => 6 );
		$creation	= $this->matrix->getRow( "A1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'testSwapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRowsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->swapRows( "A5", "B1" );
	}

	/**
	 *	Tests Exception of Method 'testSwapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRowsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->swapRows( "A1", "B5" );
	}

	/**
	 *	Tests Method 'swapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapColumns()
	{
		$this->matrix->swapColumns( "B1", "B2" );

		$assertion	= array( "B1" => 2, "B2" => 1, "B3" => 3 );
		$creation	= $this->matrix->getRow( "A1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "B1" => 5, "B2" => 4, "B3" => 6 );
		$creation	= $this->matrix->getRow( "A2" );
		$this->assertEquals( $assertion, $creation );

		$this->matrix->swapColumns( "B1", "B3" );

		$assertion	= array( "B1" => 3, "B2" => 1, "B3" => 2 );
		$creation	= $this->matrix->getRow( "A1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "B1" => 6, "B2" => 4, "B3" => 5 );
		$creation	= $this->matrix->getRow( "A2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'testSwapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapColumnsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->swapColumns( "A5", "B1" );
	}

	/**
	 *	Tests Exception of Method 'testSwapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapColumnsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->matrix->swapColumns( "A1", "B5" );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= array(
			"A1"	=> array(
				"B1"	=> 1,
				"B2"	=> 2,
				"B3"	=> 3
			),
			"A2"	=> array(
				"B1"	=> 4,
				"B2"	=> 5,
				"B3"	=> 6
			)
		);
		$creation	= $this->matrix->toArray();
		$this->assertEquals( $assertion, $creation );
	}
}
?>