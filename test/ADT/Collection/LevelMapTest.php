<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Level Map.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Collection;

use CeusMedia\Common\ADT\Collection\LevelMap;
use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\CLI;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Level Map.
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class LevelMapTest extends BaseCase
{
	/** @var LevelMap $map */
	protected $map;

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$assertion	= "value_11";
		$creation	= $this->map->get( 'level1.key1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "value_121";
		$creation	= $this->map->get( 'level1.level2.key1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "value_1231";
		$creation	= $this->map->get( 'level1.level2.level3.key1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'					=> "value_11",
			'key2'					=> "value_12",
			'level2.key1'			=> "value_121",
			'level2.key2'			=> "value_122",
			'level2.level3.key1'	=> "value_1231",
			'level2.level3.key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'			=> "value_121",
			'key2'			=> "value_122",
			'level3.key1'	=> "value_1231",
			'level3.key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1.level2' );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'key1'	=> "value_1231",
			'key2'	=> "value_1232",
		);
		$creation	= $this->map->get( 'level1.level2.level3' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->map->get( 'not_existing' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "";
		$creation	= $this->map->get( 'level1.not_existing' );
		self::assertEquals( $assertion, $creation );
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
		$creation	= $this->map->has( 'level1.key2' );
		self::assertTrue( $creation );

		$creation	= $this->map->has( 'level1.level2.key2' );
		self::assertTrue( $creation );

		$creation	= $this->map->has( 'level1.level2.level3.key2' );
		self::assertTrue( $creation );

		$creation	= $this->map->has( "not_existing" );
		self::assertFalse( $creation );

		$creation	= $this->map->has( "level1.not_existing" );
		self::assertFalse( $creation );

		$creation	= $this->map->has( 'level1' );
		self::assertTrue( $creation );
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
		$creation	= $this->map->has( 'level1.key2' );
		self::assertFalse( $creation );

		$this->map->remove( 'level1.level2.level3' );

		$creation	= $this->map->has( 'level1.level2.level3' );
		self::assertFalse( $creation );

		$creation	= $this->map->has( 'level1.level2.level3.key1' );
		self::assertFalse( $creation );

		$assertion	= array(
			'level1.key1'			=> "value_11",
			'level1.level2.key1'	=> "value_121",
			'level1.level2.key2'	=> "value_122",
		);
		$creation	= $this->map->getAll();
		self::assertEquals( $assertion, $creation );
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

		$creation	= $this->map->has( 'level1.key3' );
		self::assertTrue( $creation );

		$assertion	= "value_13";
		$creation	= $this->map->get( 'level1.key3' );
		self::assertEquals( $assertion, $creation );

		$this->map->set( 'level1.level2.level3.level4.key1', "value_12341" );

		$creation	= $this->map->has( 'level1.level2.level3.level4.key1' );
		self::assertTrue( $creation );

		$assertion	= "value_12341";
		$creation	= $this->map->get( 'level1.level2.level3.level4.key1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetSortArray()
	{
		$data	= array(
			'key2' => "value2",
			'key1' => "value1",
		);

		$this->map->set( 'array', $data, FALSE );
		$creation	= $this->map->get( 'array' );
		self::assertSame( $data, $creation );

		$this->map->set( 'array', $data );
		$assertion	= array(
			'key1' => "value1",
			'key2' => "value2",
		);
		$creation	= $this->map->get( 'array' );
		self::assertSame( $assertion, $creation );

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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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

/*	//  this disabled snippet exists to measure performance of ::get
	public function testGet_performance(): void
	{
		$clock	= new Clock();
		for($i=0; $i<1000000; $i++)
			$this->map->get( 'level1' );

		$time	= $clock->stop(3, 0);
		CLI::out( 'LevelMap::get@Performance: '.$time.'ms' );
		$this->markTestIncomplete( 'LevelMap::get@Performance: '.$time.'ms' );
	}*/

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->map	= new LevelMap();

		$this->map['level1.key1']	= "value_11";
		$this->map['level1.key2']	= "value_12";

		$this->map['level1.level2.key1']	= "value_121";
		$this->map['level1.level2.key2']	= "value_122";

		$this->map['level1.level2.level3.key1']	= "value_1231";
		$this->map['level1.level2.level3.key2']	= "value_1232";
	}
}
