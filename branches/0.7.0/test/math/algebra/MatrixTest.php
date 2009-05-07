<?php
/**
 *	TestUnit of Math_Algebra_Matrix.
 *	@package		Tests.math.algebra
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Algebra_Matrix
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.math.algebra.Matrix' );
/**
 *	TestUnit of Math_Algebra_Matrix.
 *	@package		Tests.math.algebra
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Math_Algebra_Matrix
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.06.2008
 *	@version		0.1
 */
class Math_Algebra_MatrixTest extends PHPUnit_Framework_TestCase
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
		$this->matrix	= new Math_Algebra_Matrix( 2, 3 );
		$this->matrix->setValue( 0, 0, 1 );
		$this->matrix->setValue( 0, 1, 2 );
		$this->matrix->setValue( 0, 2, 3 );
		$this->matrix->setValue( 1, 0, 4 );
		$this->matrix->setValue( 1, 1, 5 );
		$this->matrix->setValue( 1, 2, 6 );
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
		$matrix		= new Math_Algebra_Matrix( 1, 3, 10 );
		
		$assertion	= 1;
		$creation	= $matrix->getRowNumber();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 3;
		$creation	= $matrix->getColumnNumber();
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
		new Math_Algebra_Matrix( 0, 10 );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		new Math_Algebra_Matrix( 10, 0 );
	}

	/**
	 *	Tests Method 'clear'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClear()
	{
		$this->matrix->clear( 10 );
		$assertion	= array( array( 10, 10, 10 ), array( 10, 10, 10 ) );
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
		$assertion	= new Math_Algebra_Vector( 1, 4 );
		$creation	= $this->matrix->getColumn( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 2, 5 );
		$creation	= $this->matrix->getColumn( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 3, 6 );
		$creation	= $this->matrix->getColumn( 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getColumn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getColumn( -1 );
	}

	/**
	 *	Tests Exception of Method 'getColumn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getColumn( 10 );
	}

	/**
	 *	Tests Method 'getRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRow()
	{
		$assertion	= new Math_Algebra_Vector( 1, 2, 3 );
		$creation	= $this->matrix->getRow( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 4, 5, 6 );
		$creation	= $this->matrix->getRow( 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRowException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getRow( -1 );
	}

	/**
	 *	Tests Exception of Method 'getRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRowException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getRow( 10 );
	}

	/**
	 *	Tests Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValue()
	{
		$assertion	= 1;
		$creation	= $this->matrix->getValue( 0, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->matrix->getValue( 0, 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->matrix->getValue( 0, 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->matrix->getValue( 1, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValueException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getValue( 10, 1 );
	}

	/**
	 *	Tests Exception of Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValueException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->getValue( 1, 10 );
	}

	/**
	 *	Tests Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValue()
	{
		$this->matrix->setValue( 0, 2, 10 );
		$assertion	= 10;
		$creation	= $this->matrix->getValue( 0, 2 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValueException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->setValue( 10, 1, 1 );
	}

	/**
	 *	Tests Exception of Method 'setValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetValueException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->setValue( 1, 10, 1 );
	}

	/**
	 *	Tests Method 'transpose'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTranspose()
	{
		$this->matrix->transpose();
		$assertion	= new Math_Algebra_Vector( 1, 2, 3 );
		$creation	= $this->matrix->getColumn( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 4, 5, 6 );
		$creation	= $this->matrix->getColumn( 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'swapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRows()
	{
		$this->matrix->swapRows( 0, 1 );
		
		$assertion	= new Math_Algebra_Vector( 1, 2, 3 );
		$creation	= $this->matrix->getRow( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 4, 5, 6 ); ;
		$creation	= $this->matrix->getRow( 0 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'testSwapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRowsException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->swapRows( 10, 0 );
	}

	/**
	 *	Tests Exception of Method 'testSwapRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapRowsException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->swapRows( 0, 10 );
	}

	/**
	 *	Tests Method 'swapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapColumns()
	{
		$this->matrix->swapColumns( 0, 1 );

		$assertion	= new Math_Algebra_Vector( 2, 1, 3 );
		$creation	= $this->matrix->getRow( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 5, 4, 6 );
		$creation	= $this->matrix->getRow( 1 );
		$this->assertEquals( $assertion, $creation );

		$this->matrix->swapColumns( 0, 2 );

		$assertion	= new Math_Algebra_Vector( 3, 1, 2 );
		$creation	= $this->matrix->getRow( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= new Math_Algebra_Vector( 6, 4, 5 );
		$creation	= $this->matrix->getRow( 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'testSwapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSwapColumnsException1()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->swapColumns( 10, 0 );
	}

	/**
	 *	Tests Exception of Method 'testSwapColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function  testSwapColumnsException2()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->matrix->swapColumns( 0, 10 );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= array( array( 1, 2, 3 ), array( 4, 5, 6 ) );
		$creation	= $this->matrix->toArray();
		$this->assertEquals( $assertion, $creation );
	}
}
?>