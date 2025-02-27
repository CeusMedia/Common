<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Test_ADT_Tree_Node.
 *	@package		Tests.ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Tree;

use CeusMedia\Common\ADT\Tree\Node;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Test_ADT_Tree_Node.
 *	@package		Tests.ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class NodeTest extends BaseCase
{
	protected $node;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->node	= new Node();
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
	 *	Tests Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddChild()
	{
		$this->node->addChild( "string", "testString" );
		$assertion	= "testString";
		$creation	= $this->node->getChild( "string" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddChildException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->node->addChild( "string", "testString" );
		$this->node->addChild( "string", "testString" );
	}

	/**
	 *	Tests Method 'clearChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClearChildren()
	{
		$this->node->addChild( "string", "testString" );
		$this->node->clearChildren();

		$assertion	= 0;
		$creation	= count( $this->node->getChildren() );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChildren()
	{
		$this->node->addChild( 'string', "testString" );
		$this->node->addChild( 'int', 1 );

		$assertion	= array(
			'string'	=> "testString",
			'int'		=> 1,
		);
		$creation	= $this->node->getChildren();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChild()
	{
		$this->node->addChild( 'string', "testString" );
		$this->node->addChild( 'int', 1 );

		$assertion	= "testString";
		$creation	= $this->node->getChild( 'string' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->node->getChild( 'int' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChildException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->node->getChild( 'not_existing' );
	}

	/**
	 *	Tests Method 'hasChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChild()
	{
		$this->node->addChild( 'string', "testString" );

		$creation	= $this->node->hasChild( "string" );
		self::assertTrue( $creation );

		$creation	= $this->node->hasChild( "not_existing" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChildren()
	{
		$creation	= $this->node->hasChildren();
		self::assertFalse( $creation );

		$this->node->addChild( 'string', "testString" );

		$creation	= $this->node->hasChildren( "not_existing" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests Method 'removeChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveChild()
	{
		$this->node->addChild( 'string', "testString" );

		$creation	= $this->node->hasChild( "string" );
		self::assertTrue( $creation );

		$creation	= $this->node->removeChild( 'string' );
		self::assertTrue( $creation );

		$creation	= $this->node->hasChild( "string" );
		self::assertFalse( $creation );

		$creation	= $this->node->removeChild( 'string' );
		self::assertFalse( $creation );
	}
}
