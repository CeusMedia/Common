<?php
/**
 *	TestUnit of Test_ADT_List_Queue.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Queue
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Test_ADT_List_Queue.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_List_Queue
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.06.2008
 *	@version		0.1
 */
class Test_ADT_List_QueueTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->array	= array( 1, 2, 3 );
		$this->queue	= new ADT_List_Queue( $this->array );
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
		$array		= $this->array;
		$queue		= new ADT_List_Queue( $array );
		$assertion	= $array;
		$creation	= $queue->toArray();
		$this->assertEquals( $assertion, $creation );

		$queue		= new ADT_List_Queue( 1 );
		$assertion	= array();
		$creation	= $queue->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'bottom'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBottom()
	{
		$assertion	= 3;
		$creation	= $this->queue->bottom();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'bottom'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBottomException()
	{
		$creation	= $this->queue->bottom();
		$creation	= $this->queue->bottom();
		$creation	= $this->queue->bottom();
		$this->setExpectedException( "RuntimeException" );
		$creation	= $this->queue->bottom();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 3;
		$creation	= $this->queue->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= count( $this->queue );
		$this->assertEquals( $assertion, $creation );

		$queue		= new ADT_List_Queue();
		$assertion	= 0;
		$creation	= count( $queue );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'pop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPop()
	{
		$assertion	= 1;
		$creation	= $this->queue->pop();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'pop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPopException()
	{
		$creation	= $this->queue->pop();
		$creation	= $this->queue->pop();
		$creation	= $this->queue->pop();
		$this->setExpectedException( "RuntimeException" );
		$creation	= $this->queue->pop();
	}

	/**
	 *	Tests Method 'push'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPush()
	{
		$assertion	= 4;
		$creation	= $this->queue->push( 4 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $this->queue->bottom();
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
		$creation	= $this->queue->has( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->queue->has( 4 );
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
		$creation	= $this->queue->isEmpty();
		$this->assertEquals( $assertion, $creation );

		$queue		= new ADT_List_Queue();
		$assertion	= TRUE;
		$creation	= $queue->isEmpty();
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
		$creation	= $this->queue->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'top'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTop()
	{
		$assertion	= 1;
		$creation	= $this->queue->top();
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
		$creation	= (string) $this->queue;
		$this->assertEquals( $assertion, $creation );
	}
}
?>