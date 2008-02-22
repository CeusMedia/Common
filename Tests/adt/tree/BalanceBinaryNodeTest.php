<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_BalanceBinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.adt.tree.BalanceBinaryNode' );
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Tree_BalanceBinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_Tree_BalanceBinaryNodeTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of BinaryTree */
	private $tree;
	
	public function setUp()
	{
		$this->tree	= new ADT_Tree_BalanceBinaryNode( 2 );
		$this->tree->add( 1 );
		$this->tree->add( 2 );
		$this->tree->add( 3 );
		$this->tree->add( 4 );
	}

	public function testAdd()
	{
		$this->tree->add( 5 );
		$this->tree->add( -1 );
		$this->tree->add( -2 );
		$this->tree->add( -3 );
		$this->tree->add( -4 );
		$this->tree->add( -5 );
		$this->tree->add( -6 );

		$assertion	= 4;
		$creation	= $this->tree->getHeight();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->tree->getLeft()->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountNodes()
	{
		$assertion	= 4;
		$creation	= $this->tree->countNodes();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->tree->getRight()->countNodes();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetHeight()
	{
		$assertion	= 3;
		$creation	= $this->tree->getHeight();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->tree->getRight()->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetLeft()
	{
		$assertion	= new ADT_Tree_BalanceBinaryNode( 2, 1 );
		$creation	= $this->tree->getLeft();
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->tree->getLeft()->getLeft();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}
	}

	public function testGetRight()
	{
		$tree	= new ADT_Tree_BalanceBinaryNode( 2, 3 );
		$tree->add( 4 );
		$assertion	= 4;
		$creation	= $tree->getRight()->getValue();
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->tree->getRight()->getRight();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}
	}

	public function testGetValue()
	{
		$assertion	= 2;
		$creation	= $this->tree->getValue();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSearch()
	{
		$assertion	= $this->tree;
		$creation	= $this->tree->search( 2 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testToTable()
	{
		$tree	= new ADT_Tree_BalanceBinaryNode( 2 );
		$tree->add( 3 );
		$tree->add( 2 );
		$tree->add( 4 );
		$tree->add( 1 );
		$tree->add( 5 );

		$assertion	= file_get_contents( "Tests/adt/tree/balanceBinary.html" );
		$creation	= $tree->toTable( true );
#		file_put_contents( "Tests/adt/tree/balanceBinary.html", $creation );
		$this->assertEquals( $assertion, $creation );
	}
}
?>