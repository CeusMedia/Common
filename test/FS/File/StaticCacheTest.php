<?php
/**
 *	TestUnit of FS_File_StaticCache.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of FS_File_StaticCache.
 *	@package		Tests.file
 *	@extends		Test_Case
 *	@uses			FS_File_StaticCache
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
class Test_FS_File_StaticCacheTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		Test_MockAntiProtection::createMockClass( 'FS_File_StaticCache' );
		$this->path			= dirname( __FILE__ )."/";
		$this->pathCache	= $this->path."__cacheTestPath/";
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		if( !file_exists( $this->pathCache ) )
			@mkdir( $this->pathCache );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		$dir	= dir( $this->pathCache );														//  index Folder
		while( $entry = $dir->read() )															//  iterate Objects
		{
			if( preg_match( "@^(\.){1,2}$@", $entry ) )											//  if is Dot Object
				continue;																		//  continue
			if( is_file( $this->pathCache."/".$entry ) )										//  is nested File
				@unlink( $this->pathCache."/".$entry );											//  remove File
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
		FS_File_StaticCache::init( $this->pathCache, 1 );
		$fileName	= $this->pathCache."test.serial";
		file_put_contents( $fileName, "test" );
		touch( $fileName, time() - 10 );

		$assertion	= 1;
		$creation	= FS_File_StaticCache::cleanUp();
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
		$this->setExpectedException( 'InvalidArgumentException' );
		FS_File_StaticCache::init( $this->pathCache );
		FS_File_StaticCache::cleanUp();
	}

	/**
	 *	Tests Exception of Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUpException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		FS_File_StaticCache::init( $this->pathCache, 0 );
		FS_File_StaticCache::cleanUp();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'test1', 'value1' );
		FS_File_StaticCache::set( 'test2', 'value2' );
		FS_File_StaticCache::set( 'test3', 'value3' );
		file_put_contents( $this->pathCache."notCacheFile.txt", "test" );

		$assertion	= 3;
		$creation	= FS_File_StaticCache::count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'flush'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFlush()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'test1', 'value1' );
		FS_File_StaticCache::set( 'test2', 'value2' );
		FS_File_StaticCache::set( 'test3', 'value3' );
		$fileName	= $this->pathCache."notCacheFile.txt";
		file_put_contents( $fileName, "test" );

		$assertion	= 3;
		$creation	= FS_File_StaticCache::count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= FS_File_StaticCache::flush();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= FS_File_StaticCache::count();
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
		FS_File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= NULL;
		$creation	= FS_File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet2()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= "testValue";
		$creation	= FS_File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		FS_File_StaticCache::set( 'testKey', "testValue2" );

		$assertion	= "testValue2";
		$creation	= FS_File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet3()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		FS_File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= "testValue";
		$creation	= FS_File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet4()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= NULL;
		$creation	= FS_File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas1()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= FALSE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas2()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		FS_File_StaticCache::set( 'testKey', FALSE );

		$assertion	= TRUE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas3()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		FS_File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= TRUE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas4()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= FALSE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'init'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInit()
	{
		Test_FS_File_StaticCache_MockAntiProtection::init( $this->pathCache );
		$assertion	= 'FS_File_Cache';
		$creation	= get_class( Test_FS_File_StaticCache_MockAntiProtection::getProtectedStaticVar( 'store' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		FS_File_StaticCache::init( $this->pathCache, 1 );
		FS_File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= FS_File_StaticCache::remove( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= FS_File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		Test_FS_File_StaticCache_MockAntiProtection::init( $this->pathCache );
		Test_FS_File_StaticCache_MockAntiProtection::set( 'testKey', "testValue" );

		$store		= Test_FS_File_StaticCache_MockAntiProtection::getProtectedStaticVar( 'store' );

		$assertion	= "testValue";
		$creation	= $store->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>