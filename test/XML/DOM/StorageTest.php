<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Storage.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\Storage;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML DOM Storage.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class StorageTest extends BaseCase
{
	protected $fileName;

	protected $storage;

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/assets/storage.xml";
		$this->storage	= new Storage( $this->fileName );
		$this->storage->set( "tests.test1.key1", "value11" );
		$this->storage->write();
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$assertion	= "value11";
		$creation	= $this->storage->get( "tests.test1.key1" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		//  remove Value
		$creation	= $this->storage->remove( "tests.test1.key1" );
		self::assertTrue( $creation );

		//  check Value
		$creation	= $this->storage->get( "tests.test1.key1" );
		self::assertNull( $creation );

		//  try to remove Value again
		$creation	= $this->storage->remove( "tests.test1.key1" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveAndWrite()
	{
		//  remove Value and write
		$creation	= $this->storage->remove( "tests.test1.key1", true );
		self::assertTrue( $creation );

		//  remove Value and write
		$creation	= substr_count( file_get_contents( $this->fileName ), "value11" );
		self::assertEquals( 0, $creation );

		//  try to remove Value again
		$creation	= $this->storage->remove( "tests.test1.key1", true );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		//  set Value
		$creation	= $this->storage->set( "tests.test2.key1", "value21" );
		self::assertTrue( $creation );

		//  check Value
		$assertion	= "value21";
		$creation	= $this->storage->get( "tests.test2.key1" );
		self::assertEquals( $assertion, $creation );

		//  try to set Value again
		$creation	= $this->storage->set( "tests.test2.key1", "value21" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAndWrite()
	{
		//  set Value and write
		$creation	= $this->storage->set( "tests.test2.key2", "value22", true );
		self::assertTrue( $creation );

		//  check Value in File
		$assertion	= 1;
		$creation	= substr_count( file_get_contents( $this->fileName ), "value22" );
		self::assertEquals( $assertion, $creation );

		//  try to set Value again
		$creation	= $this->storage->set( "tests.test2.key2", "value22", true );
		self::assertFalse( $creation );
	}
}
