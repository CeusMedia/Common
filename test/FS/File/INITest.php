<?php
/**
 *	TestUnit of FS_File_INI.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.12.2010
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_INI.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.12.2010
 *	@version		0.1
 */
class Test_FS_File_INITest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ ).'/';
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= FS_File_INI::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet1()
	{
		$fileName	= $this->path.'plain.ini';
		$file		= new FS_File_INI( $fileName );

		$assertion	= 'value1';
		$creation	= $file->get( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value11';
		$creation	= $file->get( 'key1.key11' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet2()
	{
		$fileName	= $this->path.'sections.ini';
		$file		= new FS_File_INI( $fileName, TRUE );

		$assertion	= 'value1';
		$creation	= $file->get( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value2';
		$creation	= $file->get( 'key2', 'section2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value11';
		$creation	= $file->get( 'key1.key11', 'section1' );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas1()
	{
		$fileName	= $this->path.'plain.ini';
		$file		= new FS_File_INI( $fileName );

		$assertion	= TRUE;
		$creation	= $file->has( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->has( 'key1.key11' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $file->has( 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas2()
	{
		$fileName	= $this->path.'sections.ini';
		$file		= new FS_File_INI( $fileName, TRUE );

		$assertion	= TRUE;
		$creation	= $file->has( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->has( 'key1.key11', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $file->has( 'not_existing', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $file->has( 'not_existing', 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove1()
	{
		$fileName	= $this->path.'plain.ini';
		copy( $fileName, $fileName.'.copy' );
		$file		= new FS_File_INI( $fileName.'.copy' );

		$assertion	= TRUE;
		$data		= parse_ini_file( $fileName.'.copy' );
		$creation	= isset( $data['key1'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->remove( 'key1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$data		= parse_ini_file( $fileName.'.copy' );
		$creation	= isset( $data['key1'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $file->remove( 'key1' );
		$this->assertEquals( $assertion, $creation );

		unlink( $fileName.'.copy' );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove2()
	{
		$fileName	= $this->path.'sections.ini';
		copy( $fileName, $fileName.'.copy' );
		$file		= new FS_File_INI( $fileName.'.copy', TRUE );

		$assertion	= TRUE;
		$data		= parse_ini_file( $fileName.'.copy', TRUE );
		$creation	= isset( $data['section1']['key1'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->remove( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$data		= parse_ini_file( $fileName.'.copy', TRUE );
		$creation	= isset( $data['section1']['key1'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $file->remove( 'key1', 'section1' );
		$this->assertEquals( $assertion, $creation );

		unlink( $fileName.'.copy' );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet1()
	{
		$fileName	= $this->path.'plain.ini';
		copy( $fileName, $fileName.'.copy' );
		$file		= new FS_File_INI( $fileName.'.copy' );

		$assertion	= FALSE;
		$data		= parse_ini_file( $fileName.'.copy' );
		$creation	= isset( $data['key3'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->set( 'key3', 'value3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value3';
		$data		= parse_ini_file( $fileName.'.copy' );
		$creation	= $data['key3'];
		$this->assertEquals( $assertion, $creation );

		unlink( $fileName.'.copy' );
	}
	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet2()
	{
		$fileName	= $this->path.'sections.ini';
		copy( $fileName, $fileName.'.copy' );
		$file		= new FS_File_INI( $fileName.'.copy', TRUE );

		$assertion	= FALSE;
		$data		= parse_ini_file( $fileName.'.copy', TRUE );
		$creation	= isset( $data['section1']['key3'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $file->set( 'key3', 'value3', 'section1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'value3';
		$data		= parse_ini_file( $fileName.'.copy', TRUE );
		$creation	= $data['section1']['key3'];
		$this->assertEquals( $assertion, $creation );

		unlink( $fileName.'.copy' );
	}
}
