<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_StaticCache.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File;

use CeusMedia\Common\FS\File\Cache;
use CeusMedia\Common\FS\File\StaticCache;
use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\Test\MockAntiProtection;

/**
 *	TestUnit of FS_File_StaticCache.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class StaticCacheTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		MockAntiProtection::createMockClass( StaticCache::class );
		$this->path			= dirname( __FILE__ )."/";
		$this->pathCache	= $this->path."__cacheTestPath/";
		if( !file_exists( $this->pathCache ) )
			@mkdir( $this->pathCache );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		//  index Folder
		$dir	= dir( $this->pathCache );
		//  iterate Objects
		while( $entry = $dir->read() )
		{
			//  if is Dot Object
			if( preg_match( "@^(\.){1,2}$@", $entry ) )
				//  continue
				continue;
			//  is nested File
			if( is_file( $this->pathCache.$entry ) )
				//  remove File
				@unlink( $this->pathCache.$entry );
		}
		$dir->close();
		rmdir( substr( $this->pathCache, 0, -1 ) );
	}

	/**
	 *	Tests Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUp()
	{
		StaticCache::init( $this->pathCache, 1 );
		$fileName	= $this->pathCache."test.serial";
		file_put_contents( $fileName, "test" );
		touch( $fileName, time() - 10 );

		$assertion	= 1;
		$creation	= StaticCache::cleanUp();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUpException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		StaticCache::init( $this->pathCache );
		StaticCache::cleanUp();
	}

	/**
	 *	Tests Exception of Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUpException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		StaticCache::init( $this->pathCache, 0 );
		StaticCache::cleanUp();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'test1', 'value1' );
		StaticCache::set( 'test2', 'value2' );
		StaticCache::set( 'test3', 'value3' );
		file_put_contents( $this->pathCache."notCacheFile.txt", "test" );

		$assertion	= 3;
		$creation	= StaticCache::count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'flush'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFlush()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'test1', 'value1' );
		StaticCache::set( 'test2', 'value2' );
		StaticCache::set( 'test3', 'value3' );
		$fileName	= $this->pathCache."notCacheFile.txt";
		file_put_contents( $fileName, "test" );

		$assertion	= 3;
		$creation	= StaticCache::count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= StaticCache::flush();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= StaticCache::count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet1()
	{
		StaticCache::init( $this->pathCache, 1 );
		$assertion	= NULL;
		$creation	= StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet2()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		$assertion	= "testValue";
		$creation	= StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		StaticCache::set( 'testKey', "testValue2" );

		$assertion	= "testValue2";
		$creation	= StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet3()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		StaticCache::init( $this->pathCache, 1 );
		$assertion	= "testValue";
		$creation	= StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet4()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= NULL;
		$creation	= StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas1()
	{
		StaticCache::init( $this->pathCache, 1 );
		$assertion	= FALSE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas2()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		StaticCache::set( 'testKey', FALSE );

		$assertion	= TRUE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas3()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		StaticCache::init( $this->pathCache, 1 );
		$assertion	= TRUE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas4()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= FALSE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'init'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInit()
	{
		StaticCacheMockAntiProtection::init( $this->pathCache );
		$assertion	= Cache::class;
		$creation	= get_class( StaticCacheMockAntiProtection::getProtectedStaticVar( 'store' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		StaticCache::init( $this->pathCache, 1 );
		StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= StaticCache::remove( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		StaticCacheMockAntiProtection::init( $this->pathCache );
		StaticCacheMockAntiProtection::set( 'testKey', "testValue" );

		$store		= StaticCacheMockAntiProtection::getProtectedStaticVar( 'store' );

		$assertion	= "testValue";
		$creation	= $store->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}
}
