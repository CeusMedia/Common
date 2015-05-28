<?php
/**
 *	TestUnit of DB_StatementCollection.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of DB_StatementCollection.
 *	@package		Tests.{classPackage}
 *	@extends		Test_Case
 *	@uses			DB_StatementCollection
 *	@uses			DB_StatementBuilder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_DB_StatementCollectionTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->builder		= new Test_DB_StatementBuilderInStatementCollectionInstance( "prefix_" );
		$this->collection	= new Test_DB_StatementCollectionInstance( $this->builder );
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
		$collection	= new Test_DB_StatementCollectionInstance( $this->builder );
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
		$this->collection->orderBy( 'column1', "DESC" );
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
	 *	Tests Method 'Order'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOrderBy()
	{
		$this->collection->orderBy( "key1", "ASC" );
		$assertion	= array( 'key1' => "ASC" );
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setLimit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLimit()
	{
		$this->collection->setLimit( 10 );
		$assertion	= 10;
		$creation	= $this->builder->getProtectedVar( 'limit' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOffset'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOffset()
	{
		$this->collection->setOffset( 20 );
		$assertion	= 20;
		$creation	= $this->builder->getProtectedVar( 'offset' );
		$this->assertEquals( $assertion, $creation );
	}
}

class Test_DB_StatementCollectionInstance extends DB_StatementCollection
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}

class Test_DB_StatementBuilderInStatementCollectionInstance extends DB_StatementBuilder
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
