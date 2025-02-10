<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Lock.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Lock;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Lock.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class LockTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ ).'/';
		@unlink( $this->path.'test.lock' );
		$this->lock	= new Lock( $this->path.'test.lock' );
		$this->lock->setTimeout( 0.5 );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->path.'test.lock' );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$lock	= new Lock( 'test.lock' );
		self::assertEquals( 0, $lock->getExpiration() );
		self::assertEquals( 2, $lock->getTimeout() );
		self::assertEquals( 0.1, $lock->getSleep() );

		$lock	= new Lock( 'test.lock', 200, 100, 1 );
		self::assertEquals( 200, $lock->getExpiration() );
		self::assertEquals( 100, $lock->getTimeout() );
		self::assertEquals( 1, $lock->getSleep() );
	}

	/**
	 *	Tests Method 'lock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLock()
	{
		self::assertTrue( $this->lock->lock( FALSE ) );
		self::assertFalse( $this->lock->lock( FALSE ) );
 	}

	/**
	 *	Tests Exception of Method 'lock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLockException()
	{
		$this->expectException( 'RuntimeException' );
		self::assertTrue( $this->lock->lock() );
		$creation	= $this->lock->lock();
	}

	/**
	 *	Tests Method 'unlock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUnlock()
	{
		self::assertTrue( $this->lock->lock() );
		self::assertTrue( $this->lock->isLocked() );
		self::assertTrue( $this->lock->unlock() );
		self::assertFalse( $this->lock->isLocked() );
		self::assertFalse( $this->lock->unlock() );
	}

	/**
	 *	Tests Method 'isLocked'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLocked()
	{
		self::assertFalse( $this->lock->isLocked() );
		self::assertTrue( $this->lock->lock() );
		self::assertTrue( $this->lock->isLocked() );
		self::assertTrue( $this->lock->unlock() );
		self::assertFalse( $this->lock->isLocked() );
	}

	/**
	 *	Tests Method 'setExpiration'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetExpiration()
	{
		$this->lock->setExpiration( 100 );
		self::assertEquals( 100, $this->lock->getExpiration() );
	}

	/**
	 *	Tests Method 'setSleep'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetSleep()
	{
		$this->lock->setSleep( 100 );
		self::assertEquals( 100, $this->lock->getSleep() );
	}

	/**
	 *	Tests Method 'setTimeout'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTimeout()
	{
		$this->lock->setTimeout( 100 );
		self::assertEquals( 100, $this->lock->getTimeout() );
	}
}
