<?php
/**
 *	TestUnit of File_StaticCache.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_StaticCache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.file.StaticCache' );
/**
 *	TestUnit of File_StaticCache.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_StaticCache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
class File_StaticCacheTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		MockAntiProtection::createMockClass( 'File_StaticCache' );
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
		File_StaticCache::init( $this->pathCache, 1 );
		$fileName	= $this->pathCache."test.serial";
		file_put_contents( $fileName, "test" );
		touch( $fileName, time() - 10 );

		$assertion	= 1;
		$creation	= File_StaticCache::cleanUp();
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
		File_StaticCache::init( $this->pathCache );
		File_StaticCache::cleanUp();
	}

	/**
	 *	Tests Exception of Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUpException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		File_StaticCache::init( $this->pathCache, 0 );
		File_StaticCache::cleanUp();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'test1', 'value1' );
		File_StaticCache::set( 'test2', 'value2' );
		File_StaticCache::set( 'test3', 'value3' );
		file_put_contents( $this->pathCache."notCacheFile.txt", "test" );

		$assertion	= 3;
		$creation	= File_StaticCache::count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'flush'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFlush()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'test1', 'value1' );
		File_StaticCache::set( 'test2', 'value2' );
		File_StaticCache::set( 'test3', 'value3' );
		$fileName	= $this->pathCache."notCacheFile.txt";
		file_put_contents( $fileName, "test" );

		$assertion	= 3;
		$creation	= File_StaticCache::count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= File_StaticCache::flush();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= File_StaticCache::count();
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
		File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= NULL;
		$creation	= File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet2()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= "testValue";
		$creation	= File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		File_StaticCache::set( 'testKey', "testValue2" );

		$assertion	= "testValue2";
		$creation	= File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet3()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= "testValue";
		$creation	= File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet4()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= NULL;
		$creation	= File_StaticCache::get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas1()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= FALSE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas2()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		File_StaticCache::set( 'testKey', FALSE );

		$assertion	= TRUE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas3()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		File_StaticCache::init( $this->pathCache, 1 );
		$assertion	= TRUE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas4()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= FALSE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'init'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInit()
	{
		File_StaticCache_MockAntiProtection::init( $this->pathCache );
		$assertion	= 'File_Cache';
		$creation	= get_class( File_StaticCache_MockAntiProtection::getProtectedStaticVar( 'store' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		File_StaticCache::init( $this->pathCache, 1 );
		File_StaticCache::set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= File_StaticCache::remove( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= File_StaticCache::has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		File_StaticCache_MockAntiProtection::init( $this->pathCache );
		File_StaticCache_MockAntiProtection::set( 'testKey', "testValue" );

		$store		= File_StaticCache_MockAntiProtection::getProtectedStaticVar( 'store' );

		$assertion	= "testValue";
		$creation	= $store->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>