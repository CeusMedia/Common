<?php
declare( strict_types = 1 );

/**
 *	TestUnit of Level Map.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\ADT\Collection;

use CeusMedia\Common\ADT\Collection\LevelMap;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Level Map.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class LevelMapTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->map	= new LevelMap();

		$this->map['level1.key1']	= "value_11";
		$this->map['level1.key2']	= "value_12";

		$this->map['level1.level2.key1']	= "value_121";
		$this->map['level1.level2.key2']	= "value_122";

		$this->map['level1.level2.level3.key1']	= "value_1231";
		$this->map['level1.level2.level3.key2']	= "value_1232";
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$assertion	= "value_11";
		$creation	= $this->map->get( 'level1.key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "value_121";
		$creation	= $this->map->get( 'level1.level2.key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "value_1231";
		$creation	= $this->map->get( 'level1.level2.level3.key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'					=> "value_11",
			'key2'					=> "value_12",
			'level2.key1'			=> "value_121",
			'level2.key2'			=> "value_122",
			'level2.level3.key1'	=> "value_1231",
			'level2.level3.key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'			=> "value_121",
			'key2'			=> "value_122",
			'level3.key1'	=> "value_1231",
			'level3.key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1.level2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'	=> "value_1231",
			'key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1.level2.level3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->map->get( 'not_existing' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->map->get( 'level1.not_existing' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->map->get( "" );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1.key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1.level2.key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1.level2.level3.key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->map->has( "not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->map->has( "level1.not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->map->has( "" );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$this->map->remove( 'level1.key2' );
		$assertion	= FALSE;
		$creation	= $this->map->has( 'level1.key2' );
		$this->assertEquals( $assertion, $creation );

		$this->map->remove( 'level1.level2.level3' );
		$assertion	= FALSE;
		$creation	= $this->map->has( 'level1.level2.level3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->map->has( 'level1.level2.level3.key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'level1.key1'			=> "value_11",
			'level1.level2.key1'	=> "value_121",
			'level1.level2.key2'	=> "value_122",
		);
		$creation	= $this->map->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->map->remove( "" );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		$this->map->set( 'level1.key3', "value_13" );

		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1.key3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "value_13";
		$creation	= $this->map->get( 'level1.key3' );
		$this->assertEquals( $assertion, $creation );

		$this->map->set( 'level1.level2.level3.level4.key1', "value_12341" );

		$assertion	= TRUE;
		$creation	= $this->map->has( 'level1.level2.level3.level4.key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "value_12341";
		$creation	= $this->map->get( 'level1.level2.level3.level4.key1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetSortArray()
	{
		$map	= new LevelMap();
		$data	= array(
			'key1' => "value1",
			'key1' => "value2",
		);

		$this->map->set( 'array', $data );
		$assertion	= $data;
		$creation	= $this->map->get( 'array' );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetSort()
	{
		$data	= array(
			'add_b'	=> "value_b",
			'add_a'	=> "value_a",
		);
		$map	= new LevelMap( $data );

		ksort( $data );
		$assertion	= $data;
		$creation	= $map->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetNoSort()
	{
		$data	= array(
			'add_b'	=> "value_b",
			'add_a'	=> "value_a",
		);
		$map	= new LevelMap( $data );

		$assertion	= $data;
		$creation	= $map->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetException()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->map->set( "", "" );
	}
}
