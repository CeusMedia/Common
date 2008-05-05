<?php
/**
 *	TestUnit of Database_StatementCollection.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_StatementCollection
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database/StatementCollection' );
import( 'de.ceus-media.database/StatementBuilder' );
/**
 *	TestUnit of Database_StatementCollection.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_StatementCollection
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Database_StatementCollectionTest extends PHPUnit_Framework_TestCase
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
		$this->builder		= new Tests_Database_StatementBuilderInStatementCollectionInstance( "prefix_" );
		$this->collection	= new Tests_Database_StatementCollectionInstance( $this->builder );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$collection	= new Tests_Database_StatementCollectionInstance( $this->builder );
		$assertion	= $this->builder;
		$creation	= $this->collection->getProtectedVar( 'builder' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addComponent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddComponent()
	{
		$this->collection->addComponent( 'Order', array( 'column1', "DESC" ) );
		$assertion	= array( 'column1'	=> "DESC" );
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addComponent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddComponentException()
	{
		$this->setExpectedException( 'BadMethodCallException' );
		$this->collection->addComponent( "not_existing" );
	}

	/**
	 *	Tests Method 'addOrder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddOrder()
	{
		$this->collection->addOrder( "key1", "ASC" );
		$assertion	= array( 'key1' => "ASC" );
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrefix'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrefix()
	{
		$assertion	= "prefix_";
		$creation	= $this->collection->getPrefix();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Limit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLimit()
	{
		$this->collection->Limit( array( 10, 20 ) );
		$assertion	= array(
			'offset'	=> 10,
			'rows'		=> 20,
		);
		$creation	= $this->builder->getProtectedVar( 'limits' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'Limit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLimitException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->collection->Limit( "not_an_array" );
	}

	/**
	 *	Tests Method 'Order'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOrder()
	{
		$this->collection->Order( array( "key1", "ASC" ) );
		$assertion	= array( 'key1' => "ASC" );
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'Order'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOrderException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->collection->Order( "not_an_array" );
	}

	/**
	 *	Tests Method 'setLimit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLimit()
	{
		$this->collection->setLimit( 10, 20 );
		$assertion	= array(
			'offset'	=> 10,
			'rows'		=> 20,
		);
		$creation	= $this->builder->getProtectedVar( 'limits' );
		$this->assertEquals( $assertion, $creation );
	}
}

class Tests_Database_StatementCollectionInstance extends Database_StatementCollection
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}

class Tests_Database_StatementBuilderInStatementCollectionInstance extends Database_StatementBuilder
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}

	public function executeProtectedMethod( $method, $content, $comment = NULL )
	{
		if( !method_exists( $this, $method ) )
			throw new Exception( 'Method "'.$method.'" is not callable.' );
		return $this->$method( $content, $comment );
	}
}
?>