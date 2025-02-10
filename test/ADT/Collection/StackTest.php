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

use CeusMedia\Common\ADT\Collection\Stack;
use CeusMedia\CommonTest\BaseCase;

/**
*	TestUnit of ADT\Collection\Stack.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class StackTest extends BaseCase
{
	protected $array;
	protected $stack;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->array	= array( 1, 2, 3 );
		$this->stack	= new Stack( $this->array );
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
		$stack		= new Stack( $array );
		$assertion	= $array;
		$creation	= $stack->toArray();
		self::assertEquals( $assertion, $creation );

		$stack		= new Stack( array( 1 ) );
		$assertion	= array( 1 );
		$creation	= $stack->toArray();
		self::assertEquals( $assertion, $creation );
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
		$this->stack->bottom();
		$this->stack->bottom();
		$this->stack->bottom();
		$this->stack->bottom();
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
		self::assertEquals( $assertion, $creation );

		$stack		= new Stack();
		$assertion	= 0;
		$creation	= $stack->count();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$creation	= $this->stack->has( 1 );
		self::assertTrue( $creation );

		$creation	= $this->stack->has( 5 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'isEmpty'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEmpty()
	{
		$creation	= $this->stack->isEmpty();
		self::assertFalse( $creation );

		$stack		= new Stack();
		$creation	= $stack->isEmpty();
		self::assertTrue( $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->stack->pop();
		self::assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->stack->pop();
		self::assertEquals( $assertion, $creation );
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
		$this->expectException( "RuntimeException" );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $this->stack->pop();
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		$creation	= (string) $this->stack;
		self::assertEquals( $assertion, $creation );
	}
}
