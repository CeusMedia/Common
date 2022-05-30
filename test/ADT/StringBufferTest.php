<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Test_ADT_StringBuffer.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test;

use CeusMedia\Common\ADT\StringBuffer;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Test_ADT_StringBuffer.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class StringBufferTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->buffer	= new StringBuffer( "test" );
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
		$buffer		= new StringBuffer( "construct" );
		$assertion	= "construct";
		$creation	= $buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 4;
		$creation	= $this->buffer->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteCharAt()
	{
		$assertion	= "tet";
		$this->buffer->deleteCharAt( 2 );
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetCharAt()
	{
		$assertion	= "t";
		$creation	= $this->buffer->getCharAt( 3 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'key'.
	 *	@access		public
	 *	@return		void
	 */
	public function testKey()
	{
		$assertion	= 0;
		$creation	= $this->buffer->key();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->next();

		$assertion	= 1;
		$creation	= $this->buffer->key();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'current'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCurrent()
	{
		$assertion	= "t";
		$creation	= $this->buffer->current();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->next();

		$assertion	= "e";
		$creation	= $this->buffer->current();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insert'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsert()
	{
		$assertion	= "te123st";
		$this->buffer->insert( 2, "123" );
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'reset'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReset()
	{
		$this->buffer->reset();

		$assertion	= "";
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rewind'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRewind()
	{
		$this->buffer->next();

		$assertion	= "e";
		$creation	= $this->buffer->current();
		$this->assertEquals( $assertion, $creation );

		$this->buffer->rewind();

		$assertion	= "t";
		$creation	= $this->buffer->current();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setCharAt'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetCharAt()
	{
		$assertion	= "text";
		$this->buffer->setCharAt( 2, "x" );
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "test";
		$creation	= $this->buffer->toString();
		$this->assertEquals( $assertion, $creation );
	}
}
