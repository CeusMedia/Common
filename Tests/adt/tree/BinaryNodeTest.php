<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_BinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.adt.tree.BinaryNode' );
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_BinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_Tree_BinaryNodeTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of BinaryTree */
	private $tree;
	
	public function setUp()
	{
		$this->tree	= new ADT_Tree_BinaryNode();
		$this->tree->add( 10 );
		$this->tree->add( 12 );
		$this->tree->add( 11 );
	}

	public function testAdd()
	{
		$this->tree->add( 9 );
		$assertion	= new ADT_Tree_BinaryNode( 9 );
		$creation	= $this->tree->getLeft();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountNodes()
	{
		$assertion	= 3;
		$creation	= $this->tree->countNodes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetHeight()
	{
		$assertion	= 3;
		$creation	= $this->tree->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetLeft()
	{
		$assertion	= null;
		$creation	= $this->tree->getLeft();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetRight()
	{
		$tree	= new ADT_Tree_BinaryNode( 12 );
		$tree->add( 11 );
		$assertion	= $tree;
		$creation	= $this->tree->getRight();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetValue()
	{
		$assertion	= 10;
		$creation	= $this->tree->getValue();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSearch()
	{
		$assertion	= $this->tree;
		$creation	= $this->tree->search( 10 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>