<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare( strict_types = 1 );
/**
 *	TestUnit of File Reader.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Reader;
use CeusMedia\CommonTest\BaseCase;
use RuntimeException;

/**
 *	TestUnit of File Reader.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;
	/**	@var	string		$fileContent	Content of Test File */
	private $fileContent	= "line1\nline2\n";

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName	= dirname( __FILE__ )."/reader.test";
		$this->reader	= new Reader( $this->fileName );
	}

/*	public function testGetMimeType(){
		die( $this->reader->getMimeType() );
	}
*/
	/**
	 *	Tests Method 'equals'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEquals()
	{
		$assertion	= true;
		$creation	= $this->reader->equals( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= $this->reader->equals( __FILE__ );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'exists'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExists()
	{
		$assertion	= true;
		$creation	= $this->reader->exists();
		$this->assertEquals( $assertion, $creation );

		$reader		= new Reader( "no_existing" );
		$assertion	= false;
		$creation	= $reader->exists();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBasename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBasename()
	{
		$assertion	= basename( $this->fileName );
		$creation	= $this->reader->getBasename();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFilename'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFilename()
	{
		$assertion	= $this->fileName;
		$creation	= $this->reader->getFilename();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getExtension'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetExtension()
	{
		$assertion	= "test";
		$creation	= $this->reader->getExtension();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetGroup()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->reader->getGroup();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetOwner()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->reader->getOwner();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= str_replace( "\\", "/", dirname( realpath( $this->fileName ) ) )."/";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$reader		= new Reader( "test" );
		$assertion	= str_replace( "\\", "/", dirname( __FILE__ ) )."/";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetPermissions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->reader->getPermissions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getSize'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetSize()
	{
		$reader	= new Reader( __FILE__ );
		$size	= filesize( __FILE__ );

		$assertion	= $size;
		$creation	= $reader->getSize();
		$this->assertEquals( $assertion, $creation );

		$assertion	= round( $size / 1024, 3 )." KB";
		$creation	= $reader->getSize( 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= round( $size / 1024, 1 )." KB";
		$creation	= $reader->getSize( 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDate()
	{
		$assertion	= filemtime( $this->fileName );
		$creation	= $this->reader->getDate();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isReadable'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsReadable()
	{
		$assertion	= true;
		$creation	= $this->reader->isReadable();
		$this->assertEquals( $assertion, $creation );

		$reader		= new Reader( "not_existing" );
		$assertion	= false;
		$creation	= $reader->isReadable();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$assertion	= $this->fileContent;
		$creation	= Reader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadException()
	{
		$this->expectException( 'RuntimeException' );
		Reader::load( "not_existing" );
	}

	/**
	 *	Tests Method 'loadArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadArray()
	{
		$assertion	= explode( "\n", $this->fileContent );
		$creation	= Reader::loadArray( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'loadArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadArrayException()
	{
		$this->expectException( 'RuntimeException' );
		Reader::loadArray( "not_existing" );
	}

	/**
	 *	Tests Method 'readString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadString()
	{
		$assertion	= $this->fileContent;
		$creation	= $this->reader->readString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'readString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadStringException()
	{
		$this->expectException( RuntimeException::class );
		$reader	= new Reader( "not_existing______", TRUE );
		$reader->readString();
	}

	/**
	 *	Tests Method 'readArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadArray()
	{
		$assertion	= explode( "\n", $this->fileContent );
		$creation	= $this->reader->readArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'readArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadArrayException()
	{
		$this->expectException( 'RuntimeException' );
		$reader	= new Reader( "not_existing" );
		$reader->readArray();
	}
}
