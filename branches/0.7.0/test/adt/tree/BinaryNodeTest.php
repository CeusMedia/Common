<?php
/**
 *	Unit Test of Binary Node.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_Tree_BinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	Unit Test of Binary Node.
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Test_ADT_Tree_BinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
class Test_ADT_Tree_BinaryNodeTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of BinaryTree */
	private $tree;

	/**
	 *	Sets up binary Tree.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->tree	= new ADT_Tree_BinaryNode();
		$this->tree->add( 3 );
		$this->tree->add( 2 );
		$this->tree->add( 1 );
		$this->tree->add( 4 );
		$this->tree->add( 5 );
		$this->path	= dirname( __FILE__ ).'/';
	}

	/**
	 *	Tests method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$this->tree->add( 9 );
		$assertion	= 9;
		$creation	= $this->tree->getRight()->getRight()->getRight()->getValue();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'countNodes'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCountNodes()
	{
		$assertion	= 5;
		$creation	= $this->tree->countNodes();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'getHeight'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHeight()
	{
		$assertion	= 3;
		$creation	= $this->tree->getHeight();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'getLeft'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLeft()
	{
		$assertion	= 2;
		$creation	= $this->tree->getLeft()->getValue();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->tree->getLeft()->getLeft()->getValue();
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->tree->getLeft()->getLeft()->getLeft();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}

		try
		{
			$creation	= $this->tree->getRight()->getLeft();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}
	}

	/**
	 *	Tests method 'getRight'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRight()
	{
		$assertion	= 4;
		$creation	= $this->tree->getRight()->getValue();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 5;
		$creation	= $this->tree->getRight()->getRight()->getValue();
		$this->assertEquals( $assertion, $creation );

		try
		{
			$creation	= $this->tree->getRight()->getRight()->getRight();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}

		try
		{
			$creation	= $this->tree->getLeft()->getRight();
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e ) {}
	}

	/**
	 *	Tests method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValue()
	{
		$assertion	= 3;
		$creation	= $this->tree->getValue();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'search'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearch()
	{
		$assertion	= $this->tree->getRight()->getRight();
		$creation	= $this->tree->search( 5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $this->tree->getLeft()->getLeft();
		$creation	= $this->tree->search( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->tree->search( -1 );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests method 'toList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToList()
	{
		$assertion	=array( 1, 2, 3, 4, 5 );
		$creation	= $this->tree->toList();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->tree->toList( "lwr" );
		$this->assertEquals( $assertion, $creation );

		$assertion	=array( 5, 4, 3, 2, 1 );
		$creation	= $this->tree->toList( "rwl" );
		$this->assertEquals( $assertion, $creation );

		$assertion	=array( 3, 2, 1, 4, 5 );
		$creation	= $this->tree->toList( "wlr" );
		$this->assertEquals( $assertion, $creation );

		$assertion	=array( 3, 4, 5, 2, 1 );
		$creation	= $this->tree->toList( "wrl" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests method 'toTable'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToTable()
	{
		$this->tree->add( 5 );
		$this->tree->add( -1 );
	
		$assertion	= file_get_contents( $this->path.'binary.html' );
		$creation	= $this->tree->toTable( true );
#		file_put_contents( $this->path.'binary.html', $creation );
		$this->assertEquals( $assertion, $creation );
	}
}
?>	