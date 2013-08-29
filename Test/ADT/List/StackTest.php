<?php
/**
 *	TestUnit of Test_ADT_List_Stack.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Stack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Test_ADT_List_Stack.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Stack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.06.2008
 *	@version		0.1
 */
class Test_ADT_List_StackTest extends PHPUnit_Framework_TestCase
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
		$this->array	= array( 1, 2, 3 );
		$this->stack	= new ADT_List_Stack( $this->array );
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
		$array		= $this->array;
		$stack		= new ADT_List_Stack( $array );
		$assertion	= $array;
		$creation	= $stack->toArray();
		$this->assertEquals( $assertion, $creation );

		$stack		= new ADT_List_Stack( 1 );
		$assertion	= array();
		$creation	= $stack->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'bottom'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBottom()
	{
		$assertion	= 1;
		$creation	= $this->stack->bottom();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'bottom'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBottomException()
	{
		$creation	= $this->stack->bottom();
		$creation	= $this->stack->bottom();
		$creation	= $this->stack->bottom();
		$this->setExpectedException( "RuntimeException" );
		$creation	= $this->stack->bottom();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 3;
		$creation	= $this->stack->count();
		$this->assertEquals( $assertion, $creation );

		$stack		= new ADT_List_Stack();
		$assertion	= 0;
		$creation	= $stack->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$assertion	= TRUE;
		$creation	= $this->stack->has( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->stack->has( 5 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isEmpty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEmpty()
	{
		$assertion	= FALSE;
		$creation	= $this->stack->isEmpty();
		$this->assertEquals( $assertion, $creation );

		$stack		= new ADT_List_Stack();
		$assertion	= TRUE;
		$creation	= $stack->isEmpty();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'pop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPop()
	{
		$assertion	= 3;
		$creation	= $this->stack->pop();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->stack->pop();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->stack->pop();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'pop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPopException()
	{
		$this->stack->pop();
		$this->stack->pop();
		$this->stack->pop();
		$this->setExpectedException( "RuntimeException" );
		$this->stack->pop();
	}


	/**
	 *	Tests Method 'push'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPush()
	{
		$assertion	= 4;
		$creation	= $this->stack->push( 4 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $this->stack->pop();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToArray()
	{
		$assertion	= $this->array;
		$creation	= $this->stack->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'top'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTop()
	{
		$assertion	= 3;
		$creation	= $this->stack->top();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "(1|2|3)";
		$creation	= (string) $this->stack;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
