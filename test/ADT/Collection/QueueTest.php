<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Collection\Queue.
 *	@package		Tests.adt.list
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 */

namespace CeusMedia\CommonTest\ADT\Collection;

use CeusMedia\Common\ADT\Collection\Queue;
use CeusMedia\CommonTest\BaseCase;

/**
*	TestUnit of ADT\Collection\Queue.
 *	@package		Tests.adt.list
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 */
class QueueTest extends BaseCase
{
	protected $array;
	protected $queue;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->array	= array( 1, 2, 3 );
		$this->queue	= new Queue( $this->array );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
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
		$queue		= new Queue( $array );
		$assertion	= $array;
		$creation	= $queue->toArray();
		self::assertEquals( $assertion, $creation );

		$queue		= new Queue( array( 1 ) );
		$assertion	= array( 1 );
		$creation	= $queue->toArray();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'bottom'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBottomException()
	{
		$this->expectException( "RuntimeException" );
		$this->queue->bottom();
		$this->queue->bottom();
		$this->queue->bottom();
		$this->queue->bottom();
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= count( $this->queue );
		self::assertEquals( $assertion, $creation );

		$queue		= new Queue();
		$assertion	= 0;
		$creation	= count( $queue );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'pop'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPopException()
	{
		$this->expectException( "RuntimeException" );
		$this->queue->pop();
		$this->queue->pop();
		$this->queue->pop();
		$this->queue->pop();
	}

	/**
	 *	Tests Method 'push'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPush()
	{
		$assertion	= 4;
		$this->queue->push( 4 );
		$creation	= $this->queue->count();
		self::assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $this->queue->bottom();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$creation	= $this->queue->has( 1 );
		self::assertTrue( $creation );

		$creation	= $this->queue->has( 4 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'isEmpty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEmpty()
	{
		$creation	= $this->queue->isEmpty();
		self::assertFalse( $creation );

		$queue		= new Queue();
		$creation	= $queue->isEmpty();
		self::assertTrue( $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}
}
