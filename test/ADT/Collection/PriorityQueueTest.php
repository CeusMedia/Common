<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Collection\Stack.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Collection;

use CeusMedia\Common\ADT\Collection\PriorityQueue;
use CeusMedia\CommonTest\BaseCase;

/**
*	TestUnit of ADT\Collection\PriorityQueue.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PriorityQueueTest extends BaseCase
{
	protected $array;
	protected PriorityQueue $queue;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->array	= [];//array( 1, 2, 3 );
		$this->queue	= new PriorityQueue( $this->array );
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
		$items	= ['Test 1', 'Test 2', 'Test 3'];

		$prio	= 5;
		$queue	= new PriorityQueue( $items, $prio );
		self::assertEquals( 3, $queue->count() );
		self::assertEquals( [$prio => $items], $queue->toArray() );

		$prio	= 3;
		$queue	= new PriorityQueue( $items, $prio );
		self::assertEquals( 3, $queue->count() );
		self::assertEquals( [$prio => $items], $queue->toArray() );
	}

	/**
	 *	Tests Method 'dequeue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDequeue()
	{
		$this->queue->enqueue( 'Test 1' );
		$this->queue->enqueue( 'Test 2' );
		$this->queue->enqueue( 'Test 3' );

		self::assertEquals( 3, $this->queue->count() );
		self::assertEquals( 'Test 1', $this->queue->dequeue() );
		self::assertEquals( 2, $this->queue->count() );
		self::assertEquals( 'Test 2', $this->queue->dequeue() );
		self::assertEquals( 1, $this->queue->count() );
		self::assertEquals( 'Test 3', $this->queue->dequeue() );
		self::assertEquals( 0, $this->queue->count() );
		self::assertEquals( [], $this->queue->toList() );

		$this->queue->clear();
		$this->queue->enqueue( 'Test 1.1', 3 );
		$this->queue->enqueue( 'Test 1.2', 3 );
		$this->queue->enqueue( 'Test 2', 2 );
		$this->queue->enqueue( 'Test 3.1', 1 );
		$this->queue->enqueue( 'Test 3.2', 1 );
		$this->queue->enqueue( 'Test 3.3', 1 );
		self::assertEquals( 6, $this->queue->count() );

		self::assertEquals( 'Test 3.1', $this->queue->dequeue() );
		self::assertEquals( 'Test 3.2', $this->queue->dequeue() );
		self::assertEquals( 'Test 3.3', $this->queue->dequeue() );
		self::assertEquals( 'Test 2', $this->queue->dequeue() );
		self::assertEquals( 'Test 1.1', $this->queue->dequeue() );
		self::assertEquals( 'Test 1.2', $this->queue->dequeue() );
		self::assertEquals( [], $this->queue->toList() );
	}

	/**
	 *	Tests Method 'dequeue'.
	 *	Awaits OutOfBoundsException when dequeue-ing on empty queue.
	 *	@return		void
	 */
	public function testDequeue_OutOfBoundsException(): void
	{
		$this->expectException( \OutOfBoundsException::class );
		$this->queue->dequeue();
	}

	/**
	 *	Tests Method 'enqueue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEnqueue()
	{
		$this->queue->enqueue( 'Test 1' );

		$expected	= [5 => ['Test 1']];
		self::assertEquals( $expected, $this->queue->toArray() );

		$this->queue->enqueue( 'Test 2', 7 );
		$expected	= [5 => ['Test 1'], 7 => ['Test 2']];
		self::assertEquals( $expected, $this->queue->toArray() );

		$this->queue->enqueue( 'Test 0', 3 );
		$expected	= [3 => ['Test 0'], 5 => ['Test 1'], 7 => ['Test 2']];
		self::assertEquals( $expected, $this->queue->toArray() );

		$this->queue->clear();
		$this->queue->enqueue( 'Test 1' );
		$this->queue->enqueue( 'Test 2' );
		$expected	= [5 => ['Test 1', 'Test 2']];
		self::assertEquals( $expected, $this->queue->toArray() );

		$this->queue->clear();
		$this->queue->enqueue( 'Test 1', 9 );
		$this->queue->enqueue( 'Test 2', 1 );
		$this->queue->enqueue( 'Test 3', 7 );
		$this->queue->enqueue( 'Test 4', 3 );
		$this->queue->enqueue( 'Test 5', 5 );

		$expected	= [1, 3, 5, 7, 9];
		self::assertEquals( $expected, array_keys( $this->queue->toArray() ) );
		$expected	= ['Test 2', 'Test 4', 'Test 5', 'Test 3', 'Test 1'];
		self::assertEquals( $expected, $this->queue->toList() );
	}

	/**
	 *	Tests Method 'enqueue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		self::assertFalse( $this->queue->has() );
		$this->queue->enqueue( 'Test 1' );
		self::assertTrue( $this->queue->has() );
		self::assertTrue( $this->queue->has( 5 ) );
		self::assertFalse( $this->queue->has( 6 ) );

		$this->queue->enqueue( 'Test 2', 6 );
		self::assertTrue( $this->queue->has() );
		self::assertTrue( $this->queue->has( 5 ) );
		self::assertTrue( $this->queue->has( 6 ) );
	}
}
