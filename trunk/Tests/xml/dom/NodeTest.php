<?php
/**
 *	TestUnit of XML DOM Node.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.xml.dom.Node' );
/**
 *	TestUnit of XML DOM Node.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Leaf
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class Tests_XML_DOM_NodeTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Sets up Node.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->node	= new XML_DOM_Node( "testNode", "testContent" );
		$this->node->setAttribute( "testKey", "testValue" );
		$this->leaf	= new XML_DOM_Node( "testLeaf1", "testContent1" );
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
		$node		= new XML_DOM_Node( "tag1", "content1", $attributes );

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
		$leaf		= new XML_DOM_Node( "testLeaf2", "testContent2" );
		$assertion	= $leaf;
		$creation	= $this->node->addChild( $leaf );
		$this->assertEquals( $assertion, $creation );

		//  get added Leaf
		$assertion	= $leaf;
		$creation	= $this->node->getChild( "testLeaf2" );
		$this->assertEquals( $assertion, $creation );

		//  count Children
		$assertion	= 2;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );

		//  add Node
		$node		= new XML_DOM_Node( "testNode3", "testContent3" );
		$assertion	= $node;
		$creation	= $this->node->addChild( $node );
		$this->assertEquals( $assertion, $creation );

		//  get added Node
		$assertion	= $node;
		$creation	= $this->node->getChild( "testNode3" );
		$this->assertEquals( $assertion, $creation );

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
		$assertion	= NULL;
		$creation	= $this->node->getAttribute( "testKey1" );
		$this->assertEquals( $assertion, $creation );

		//  get invalid Attribute
		$assertion	= NULL;
		$creation	= $this->node->getAttribute( "TESTKEY" );
		$this->assertEquals( $assertion, $creation );
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
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$assertion	= true;
		$creation	= $this->node->hasAttributes();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasAttribute()
	{
		//  test valid Attribute
		$assertion	= true;
		$creation	= $this->node->hasAttribute( "testKey" );
		$this->assertEquals( $assertion, $creation );

		//  test invalid Attribute
		$assertion	= false;
		$creation	= $this->node->hasAttribute( "testKey1" );
		$this->assertEquals( $assertion, $creation );

		//  test invalid Attribute
		$assertion	= false;
		$creation	= $this->node->hasAttribute( "TESTKEY" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChild()
	{
		//  test Children
		$assertion	= true;
		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );

		//  remove Children
		$this->node->removeChild( $this->leaf->getNodeName() );
		$assertion	= false;
		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChildren()
	{
		//  test Children
		$assertion	= true;
		$creation	= $this->node->hasChildren();
		$this->assertEquals( $assertion, $creation );

		//  remove Children
		$this->node->removeChild( $this->leaf->getNodeName() );
		$assertion	= false;
		$creation	= $this->node->hasChildren();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasContent()
	{
		$assertion	= true;
		$creation	= $this->node->hasContent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveAttribute()
	{
		//  remove Attribute
		$assertion	= true;
		$creation	= $this->node->removeAttribute( "testKey" );
		$this->assertEquals( $assertion, $creation );
		
		//  check Attribute
		$assertion	= false;
		$creation	= $this->node->hasAttribute( "testKey" );
		$this->assertEquals( $assertion, $creation );
		
		//  check Attributes
		$assertion	= false;
		$creation	= $this->node->hasAttributes();
		$this->assertEquals( $assertion, $creation );

		//  try to delete Attribute again
		$assertion	= false;
		$creation	= $this->node->removeAttribute( "testKey" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveChild()
	{
		//  remove Children
		$assertion	= true;
		$creation	= $this->node->removeChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );

		//  test Children
		$assertion	= FALSE;
		$creation	= $this->node->hasChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );

		//  try to remove Children again
		$assertion	= false;
		$creation	= $this->node->removeChild( $this->leaf->getNodeName() );
		$this->assertEquals( $assertion, $creation );


		//  add 2 Children with same Node Name
		$this->node->addChild( new XML_DOM_Node( "leaf" ) );
		$this->node->addChild( new XML_DOM_Node( "leaf" ) );

		//  test Children
		$assertion	= 2;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );

		//  remove first Child
		$assertion	= true;
		$creation	= $this->node->removeChild( "leaf" );
		$this->assertEquals( $assertion, $creation );

		//  test Children
		$assertion	= 1;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );

		//  remove second Child
		$assertion	= true;
		$creation	= $this->node->removeChild( "leaf" );
		$this->assertEquals( $assertion, $creation );

		//  test Children
		$assertion	= 0;
		$creation	= count( $this->node->getChildren() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeContent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveContent()
	{
		//  remove Content
		$assertion	= true;
		$creation	= $this->node->removeContent();
		$this->assertEquals( $assertion, $creation );

		//  check Content
		$assertion	= false;
		$creation	= $this->node->hasContent();
		$this->assertEquals( $assertion, $creation );

		//  try to delete Content again
		$assertion	= false;
		$creation	= $this->node->removeContent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setAttribute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAttribute()
	{
		//  set Attribute
		$assertion	= true;
		$creation	= $this->node->setAttribute( "testKey2", "testValue2" );
		$this->assertEquals( $assertion, $creation );

		//  check Attribute
		$assertion	= "testValue2";
		$creation	= $this->node->getAttribute( "testKey2" );
		$this->assertEquals( $assertion, $creation );

		//  try to set Attribute again
		$assertion	= false;
		$creation	= $this->node->setAttribute( "testKey2", "testValue2" );
		$this->assertEquals( $assertion, $creation );

		//  try to overwrite an Attribute
		$assertion	= true;
		$creation	= $this->node->setAttribute( "testKey2", "testValue3" );
		$this->assertEquals( $assertion, $creation );

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
		$assertion	= true;
		$creation	= $this->node->setContent( "testContent2" );
		$this->assertEquals( $assertion, $creation );

		//  check Content
		$assertion	= "testContent2";
		$creation	= $this->node->getContent();
		$this->assertEquals( $assertion, $creation );

		//  try to set Content again
		$assertion	= false;
		$creation	= $this->node->setContent( "testContent2" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setNodeName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetNodeName()
	{
		//  set Node Name
		$assertion	= true;
		$creation	= $this->node->setNodeName( "testNode2" );
		$this->assertEquals( $assertion, $creation );

		//  check NodeName
		$assertion	= "testNode2";
		$creation	= $this->node->getNodeName();
		$this->assertEquals( $assertion, $creation );

		//  try to set Node Name again
		$assertion	= false;
		$creation	= $this->node->setNodeName( "testNode2" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>