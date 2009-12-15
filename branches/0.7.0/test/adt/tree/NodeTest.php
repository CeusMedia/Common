<?php
/**
 *	TestUnit of Test_ADT_Tree_Node.
 *	@package		Tests.adt.tree
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_Tree_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Test_ADT_Tree_Node.
 *	@package		Tests.adt.tree
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_Tree_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.07.2008
 *	@version		0.1
 */
class Test_ADT_Tree_NodeTest extends PHPUnit_Framework_TestCase
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
		$this->node	= new ADT_Tree_Node();
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
	 *	Tests Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddChild()
	{
		$this->node->addChild( "string", "testString" );
		$assertion	= "testString";
		$creation	= $this->node->getChild( "string" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddChildException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->node->getChild( 'int' );
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

		$assertion	= TRUE;
		$creation	= $this->node->hasChild( "string" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->node->hasChild( "not_existing" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasChildren'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasChildren()
	{
		$assertion	= FALSE;
		$creation	= $this->node->hasChildren();
		$this->assertEquals( $assertion, $creation );

		$this->node->addChild( 'string', "testString" );

		$assertion	= TRUE;
		$creation	= $this->node->hasChildren( "not_existing" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeChild'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveChild()
	{
		$this->node->addChild( 'string', "testString" );

		$assertion	= TRUE;
		$creation	= $this->node->hasChild( "string" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->node->removeChild( 'string' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->node->hasChild( "string" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->node->removeChild( 'string' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>