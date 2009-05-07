<?php
/**
 *	TestUnit of File_Cache.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Cache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
require_once 'MockAntiProtection.php';
/**
 *	TestUnit of File_Cache.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Cache
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.04.2009
 *	@version		0.1
 */
final class File_CacheTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		MockAntiProtection::createMockClass( 'File_Cache' );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct1()
	{
		$mock		= new File_Cache_MockAntiProtection( $this->pathCache );
		$assertion	= $this->pathCache;
		$creation	= $mock->getProtectedVar( 'path' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct2()
	{
		$path		= substr( $this->pathCache, 0, -1 ); 
		$mock		= new File_Cache_MockAntiProtection( $path );
		$assertion	= $this->pathCache;
		$creation	= $mock->getProtectedVar( 'path' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__constructException()
	{
		$this->setExpectedException( 'RuntimeException' );
		new File_Cache( "not_existing" );
	}

	/**
	 *	Tests Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUp()
	{
		$cache		= new File_Cache( $this->pathCache, 1 );
		$fileName	= $this->pathCache."test.serial";
		file_put_contents( $fileName, "test" );
		touch( $fileName, time() - 10 );

		$assertion	= 1;
		$creation	= $cache->cleanUp();
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
		$cache	= new File_Cache( $this->pathCache );
		$this->setExpectedException( 'InvalidArgumentException' );
		$cache->cleanUp();
	}

	/**
	 *	Tests Exception of Method 'cleanUp'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCleanUpException2()
	{
		$cache	= new File_Cache( $this->pathCache, 0 );
		$this->setExpectedException( 'InvalidArgumentException' );
		$cache->cleanUp();
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'test1', 'value1' );
		$cache->set( 'test2', 'value2' );
		$cache->set( 'test3', 'value3' );
		file_put_contents( $this->pathCache."notCacheFile.txt", "test" );

		$assertion	= 3;
		$creation	= $cache->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'flush'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFlush()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'test1', 'value1' );
		$cache->set( 'test2', 'value2' );
		$cache->set( 'test3', 'value3' );
		$fileName	= $this->pathCache."notCacheFile.txt";
		file_put_contents( $fileName, "test" );

		$assertion	= 3;
		$creation	= $cache->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $cache->flush();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $cache->count();
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
		$cache	= new File_Cache( $this->pathCache, 1 );
		$assertion	= NULL;
		$creation	= $cache->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet2()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		$assertion	= "testValue";
		$creation	= $cache->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$cache->set( 'testKey', "testValue2" );

		$assertion	= "testValue2";
		$creation	= $cache->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet3()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		$cache	= new File_Cache( $this->pathCache, 1 );
		$assertion	= "testValue";
		$creation	= $cache->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet4()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= NULL;
		$creation	= $cache->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet5()
	{
		$mock	= new File_Cache_MockAntiProtection( $this->pathCache );
		$mock->setProtectedVar( 'data', array( 'testKey' => 'testValue' ) );

		$assertion	= NULL;
		$creation	= $mock->get( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas1()
	{
		$cache		= new File_Cache( $this->pathCache, 1 );
		$assertion	= FALSE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas2()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$cache->set( 'testKey', FALSE );

		$assertion	= TRUE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas3()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		$cache	= new File_Cache( $this->pathCache, 1 );
		$assertion	= TRUE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas4()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		sleep( 1 );
		$assertion	= FALSE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas5()
	{
		$mock	= new File_Cache_MockAntiProtection( $this->pathCache );
		$mock->setProtectedVar( 'data', array( 'testKey' => 'testValue' ) );

		$assertion	= FALSE;
		$creation	= $mock->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$cache	= new File_Cache( $this->pathCache, 1 );
		$cache->set( 'testKey', "testValue" );

		$assertion	= TRUE;
		$creation	= $cache->remove( 'testKey' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $cache->has( 'testKey' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		$mock	= new File_Cache_MockAntiProtection( $this->pathCache );
		$mock->set( 'testKey', "testValue" );

		$assertion	= array( 'testKey' => "testValue" );
		$creation	= $mock->getProtectedVar( 'data' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>