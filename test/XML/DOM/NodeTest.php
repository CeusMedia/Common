<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Node.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML DOM Node.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class NodeTest extends BaseCase
{
	/** @var Node  */
	protected $node;

	/** @var Node */
	protected $leaf;

	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->node	= new Node( "testNode", "testContent" );
		$this->node->setAttribute( "testKey", "testValue" );
		$this->leaf	= new Node( "testLeaf1", "testContent1" );
		$this->node->addChild( $this->leaf );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$attributes	= array( 'key1' => "value1", 'key2' => "value2" );
		$node		= new Node( "tag1", "content1", $attributes );

		$assertion	= "tag1";
		$creation	= $node->getNodeName();
		$this->assertEquals( $assertion, $creation );

		$assertion	= "content1";
		$creation	= $node->getContent();
		$this->assertEquals( $assertion, $creation );

		$assertion	= $attributes;
		$creation	= $node->getAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddChild()
	{
		//  add Leaf
		$leaf		= new Node( "testLeaf2", "testContent2" );
		$creation	= $this->node->addChild( $leaf );
		$this->assertEquals( $leaf, $creation );

		//  get added Leaf
		$creation	= $this->node->getChild( "testLeaf2" );
		$this->assertEquals( $leaf, $creation );

		//  count Children
		$this->assertCount( 2, $this->node->getChildren() );

		//  add Node
		$node		= new Node( "testNode3", "testContent3" );
		$creation	= $this->node->addChild( $node );
		$this->assertEquals( $node, $creation );

		//  get added Node
		$creation	= $this->node->getChild( "testNode3" );
		$this->assertEquals( $node, $creation );

		//  count Children
		$assertion	= 3;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAttribute()
	{
		//  get Attribute
		$assertion	= "testValue";
		$creation	= $this->node->getAttribute( "testKey" );
		$this->assertEquals( $assertion, $creation );

		//  get invalid Attribute
		$creation	= $this->node->getAttribute( "testKey1" );
		$this->assertNull( $creation );

		//  get invalid Attribute
		$creation	= $this->node->getAttribute( "TESTKEY" );
		$this->assertNull( $creation );
	}

	/**
	 *	Tests Method 'getAttributes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAttributes()
	{
		$assertion	= array( "testKey" => "testValue" );
		$creation	= $this->node->getAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChild()
	{
		//  get Leaf Child
		$assertion	= $this->leaf;
		$creation	= $this->node->getChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChildException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->node->getChild( "not_existing" );
	}

	/**
	 *	Tests Method 'getChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChildrenWithNodeName()
	{
		$this->node->addChild( $this->leaf );

		//  get Leaf Child
		$assertion	= array( $this->leaf, $this->leaf );
		$creation	= $this->node->getChildren( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetChildren()
	{
		//  get Children
		$assertion	= array( $this->leaf );
		$creation	= $this->node->getChildren();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetContent()
	{
		$assertion	= "testContent";
		$creation	= $this->node->getContent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getNodeName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNodeName()
	{
		$assertion	= "testNode";
		$creation	= $this->node->getNodeName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasAttributes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasAttributes()
	{
		$creation	= $this->node->hasAttributes();
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'hasAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasAttribute()
	{
		//  test valid Attribute
		$creation	= $this->node->hasAttribute( "testKey" );
		$this->assertTrue( $creation );

		//  test invalid Attribute
		$creation	= $this->node->hasAttribute( "testKey1" );
		$this->assertFalse( $creation );

		//  test invalid Attribute
		$creation	= $this->node->hasAttribute( "TESTKEY" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChild()
	{
		//  test Children
		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertTrue( $creation );

		//  remove Children
		$this->node->removeChild( $this->leaf->getNodeName() );

		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChildren()
	{
		//  test Children
		$creation	= $this->node->hasChildren();
		$this->assertTrue( $creation );

		//  remove Children
		$this->node->removeChild( $this->leaf->getNodeName() );
		$creation	= $this->node->hasChildren();
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'hasContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasContent()
	{
		$creation	= $this->node->hasContent();
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'removeAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveAttribute()
	{
		//  remove Attribute
		$creation	= $this->node->removeAttribute( "testKey" );
		$this->assertEquals( $this->node, $creation );

		//  check Attribute
		$creation	= $this->node->hasAttribute( "testKey" );
		$this->assertFalse( $creation );

		//  check Attributes
		$creation	= $this->node->hasAttributes();
		$this->assertFalse( $creation );

		//  try to delete Attribute again
		$creation	= $this->node->removeAttribute( "testKey" );
		$this->assertEquals( $this->node, $creation );
	}

	/**
	 *	Tests Method 'removeChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveChild()
	{
		//  remove Children
		$creation	= $this->node->removeChild( $this->leaf->getNodeName() );
		$this->assertEquals( $this->node, $creation );

		//  test Children
		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertFalse( $creation );

		//  try to remove Children again
		$creation	= $this->node->removeChild( $this->leaf->getNodeName() );
		$this->assertEquals( $this->node, $creation );

		//  add 2 Children with same Node Name
		$this->node->addChild( new Node( "leaf" ) );
		$this->node->addChild( new Node( "leaf" ) );

		//  test Children
		$assertion	= 2;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );

		//  remove first Child
		$creation	= $this->node->removeChild( "leaf" );
		$this->assertEquals( $this->node, $creation );

		//  test Children
		$assertion	= 1;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );

		//  remove second Child
		$creation	= $this->node->removeChild( "leaf" );
		$this->assertEquals( $this->node, $creation );

		//  test Children
		$this->assertCount( 0, $this->node->getChildren() );
	}

	/**
	 *	Tests Method 'removeContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveContent()
	{
		//  remove Content
		$creation	= $this->node->removeContent();
		$this->assertEquals( $this->node, $creation );

		//  check Content
		$creation	= $this->node->hasContent();
		$this->assertFalse( $creation );

		//  try to delete Content again
		$creation	= $this->node->removeContent();
		$this->assertEquals( $this->node, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute()
	{
		//  set Attribute
		$creation	= $this->node->setAttribute( "testKey2", "testValue2" );
		$this->assertEquals( $this->node, $creation );

		//  check Attribute
		$assertion	= "testValue2";
		$creation	= $this->node->getAttribute( "testKey2" );
		$this->assertEquals( $assertion, $creation );

		//  try to set Attribute again
		$creation	= $this->node->setAttribute( "testKey2", "testValue2" );
		$this->assertEquals( $this->node, $creation );

		//  try to overwrite an Attribute
		$creation	= $this->node->setAttribute( "testKey2", "testValue3" );
		$this->assertEquals( $this->node, $creation );

		//  check overwritten Attribute
		$assertion	= "testValue3";
		$creation	= $this->node->getAttribute( "testKey2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetContent()
	{
		//  set Content
		$creation	= $this->node->setContent( "testContent2" );
		$this->assertEquals( $this->node, $creation );

		//  check Content
		$assertion	= "testContent2";
		$creation	= $this->node->getContent();
		$this->assertEquals( $assertion, $creation );

		//  try to set Content again
		$creation	= $this->node->setContent( "testContent2" );
		$this->assertEquals( $this->node, $creation );
	}

	/**
	 *	Tests Method 'setNodeName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetNodeName()
	{
		//  set Node Name
		$creation	= $this->node->setNodeName( "testNode2" );
		$this->assertEquals( $this->node, $creation );

		//  check NodeName
		$assertion	= "testNode2";
		$creation	= $this->node->getNodeName();
		$this->assertEquals( $assertion, $creation );

		//  try to set Node Name again
		$creation	= $this->node->setNodeName( "testNode2" );
		$this->assertEquals( $this->node, $creation );
	}
}
