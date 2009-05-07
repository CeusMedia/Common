<?php
/**
 *	TestUnit of Database_StatementBuilder.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.database.StatementBuilder' );
/**
 *	TestUnit of Database_StatementBuilder.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Database_StatementBuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->builder	= new Database_StatementBuilderInstance( "prefix_" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$prefix		= "pre_";
		$keys		= array( "key1", "key2" );
		$tables		= array( "table1 as t1", "table2 as t2" );
		$conditions	= array( "t1.key1=t2.key2", "field1>1" );
		$groupings	= array( "key1", "key3" );
		$builder	= new Database_StatementBuilderInstance( $prefix, $keys, $tables, $conditions, $groupings );

		$assertion	= $prefix;
		$creation	= $builder->getProtectedVar( 'prefix' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $keys;
		$creation	= $builder->getProtectedVar( 'keys' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( "pre_table1 as t1", "pre_table2 as t2" );
		$creation	= $builder->getProtectedVar( 'tables' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $conditions;
		$creation	= $builder->getProtectedVar( 'conditions' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= $groupings;
		$creation	= $builder->getProtectedVar( 'groupings' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addCondition'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddCondition1()
	{
		$condition	= 'key1="alpha"';
		$this->builder->addCondition( $condition );

		$assertion	= array( $condition );
		$creation	= $this->builder->getProtectedVar( 'conditions' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addCondition'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddCondition2()
	{
		$condition	= "key1='alpha'";
		$this->builder->addCondition( $condition );

		$assertion	= array( $condition );
		$creation	= $this->builder->getProtectedVar( 'conditions' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddConditions()
	{
		$conditions	= array( "key1='alpha'", "key2>2" );
		$this->builder->addConditions( $conditions );

		$assertion	= $conditions;
		$creation	= $this->builder->getProtectedVar( 'conditions' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddConditionsException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->builder->addConditions( "not_an_array" );
	}

	/**
	 *	Tests Method 'addGrouping'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddGrouping()
	{
		$this->builder->addGrouping( "group_key" );
		$assertion	= array( "group_key" );
		$creation	= $this->builder->getProtectedVar( 'groupings' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addGroupings'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddGroupings()
	{
		$groupings	= array( "group_key1", "group_key2" );
		$this->builder->addGroupings( $groupings );
		$assertion	= $groupings;
		$creation	= $this->builder->getProtectedVar( 'groupings' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addGroupings'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddGroupingsException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->builder->addGroupings( "not_an_array" );
	}

	/**
	 *	Tests Method 'addKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddKey()
	{
		$this->builder->addKey( 'key1' );
		$assertion	= array( 'key1' );
		$creation	= $this->builder->getProtectedVar( 'keys' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddKeys()
	{
		$keys		= array( "key1", "key2" );
		$this->builder->addKeys( $keys );
		$assertion	= $keys;
		$creation	= $this->builder->getProtectedVar( 'keys' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddKeysException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->builder->addKeys( "not_an_array" );
	}

	/**
	 *	Tests Method 'addOrder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddOrder()
	{
		$this->builder->addOrder( "key1", "ASC" );
		$assertion	= array( "key1" => "ASC" );
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addOrders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddOrders()
	{
		$orders		= array(
			array( "key1" => "ASC" ),
			array( "key2" => "DESC" ),
		);
		$creation	= $this->builder->addOrders( $orders );
		$assertion	= $orders;
		$creation	= $this->builder->getProtectedVar( 'orders' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addOrders'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddOrdersException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->builder->addOrders( "not_an_array" );
	}

	/**
	 *	Tests Method 'addTable'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddTable()
	{
		$this->builder->addTable( "table_alpha as a" );
		$assertion	= array( "prefix_table_alpha as a" );
		$creation	= $this->builder->getProtectedVar( 'tables' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addTables'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddTables()
	{
		$this->builder->addTables( array( "table_alpha as a", "table_beta" ) );
		$assertion	= array( "prefix_table_alpha as a", "prefix_table_beta" );;
		$creation	= $this->builder->getProtectedVar( 'tables' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'addTables'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddTablesException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->builder->addTables( "not_an_array" );
	}

	/**
	 *	Tests Method 'buildCountStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildCountStatement()
	{
		$this->builder->addKeys( array( 'key1', 'key2' ) );
		$this->builder->addTables( array( 'alpha as a', 'beta as b' ) );
		$this->builder->addConditions( array( 'a.key1=b.key2', 'field>10' ) );
		$this->builder->addGroupings( array( 'key1', 'key2' ) );
		$this->builder->addOrders( array( 'key1' => "ASC", 'key2' => "DESC" ) );
		$this->builder->setLimit( 10 );
		$this->builder->setOffset( 20 );

		$assertion	= "SELECT COUNT(key1) as rowcount
FROM
	prefix_alpha as a,
	prefix_beta as b
WHERE
	a.key1=b.key2 AND
	field>10
GROUP BY
	key1,
	key2";
		$creation	= $this->builder->buildCountStatement();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'buildCountStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildCountStatementException1()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->buildCountStatement();
	}
	
	/**
	 *	Tests Exception of Method 'buildCountStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildCountStatementException2()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->addKeys( array( 'key1', 'key2' ) );
		$this->builder->buildCountStatement();
	}

	/**
	 *	Tests Method 'buildSelectStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildSelectStatement()
	{
		$this->builder->addKeys( array( 'key1', 'key2' ) );
		$this->builder->addTables( array( 'alpha as a', 'beta as b' ) );
		$this->builder->addConditions( array( 'a.key1=b.key2', 'field>10' ) );
		$this->builder->addGroupings( array( 'key1', 'key2' ) );
		$this->builder->addOrders( array( 'key1' => "ASC", 'key2' => "DESC" ) );
		$this->builder->setLimit( 10 );
		$this->builder->setOffset( 20 );

		$assertion	= "SELECT
	key1,
	key2
FROM
	prefix_alpha as a,
	prefix_beta as b
WHERE
	a.key1=b.key2 AND
	field>10
GROUP BY
	key1,
	key2
ORDER BY
	key1 ASC,
	key2 DESC
LIMIT 10
OFFSET 20";
		$creation	= $this->builder->buildSelectStatement();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'buildSelectStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildSelectStatementException1()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->buildSelectStatement();
	}
	
	/**
	 *	Tests Exception of Method 'buildSelectStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildSelectStatementException2()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->addKeys( array( 'key1', 'key2' ) );
		$this->builder->buildSelectStatement();
	}

	/**
	 *	Tests Method 'buildStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildStatement()
	{
		$this->builder->addKey( 'key1' );
		$this->builder->addTable( 'alpha as a' );
		$assertion	= "SELECT
	key1
FROM
	prefix_alpha as a";
		$creation	= $this->builder->buildStatement();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'buildStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildStatementException1()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->buildStatement();
	}
	
	/**
	 *	Tests Exception of Method 'buildStatement'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildStatementException2()
	{
		$this->setExpectedException( 'Exception' );
		$this->builder->addKeys( array( 'key1', 'key2' ) );
		$this->builder->buildStatement();
	}

	/**
	 *	Tests Method 'getPrefix'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrefix()
	{
		$prefix		= "new_prefix_";
		$builder	= new Database_StatementBuilder( $prefix );
		$assertion	= $prefix;
		$creation	= $builder->getPrefix();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setLimit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLimit()
	{
		$this->builder->setLimit( 10 );
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
		$this->builder->setOffset( 10 );
		$assertion	= 10;
		$creation	= $this->builder->getProtectedVar( 'offset' );
		$this->assertEquals( $assertion, $creation );
	}
}

class Database_StatementBuilderInstance extends Database_StatementBuilder
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