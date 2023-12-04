<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare( strict_types = 1 );

/**
 *	TestUnit of File Writer.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Writer;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;
use RuntimeException;

/**
 *	TestUnit of File Writer.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class WriterTest extends BaseCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private string $fileName;
	/**	@var	string		$fileContent	Content of Test File */
	private string $fileContent	= "line1\nline2\n";
	/**	@var	string		$path			Path to Test Files */
	private string $path;
	/** @var	Writer		$writer			Instance of writer */
	private Writer $writer;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.test";
		$this->writer	= new Writer( $this->fileName, 0 );
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
		@unlink( $this->path."writer_create.test" );
		@unlink( $this->path."newFile" );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate()
	{
		$writer	= new Writer( $this->path."writer_create.test", 0 );
		$writer->create();

		$this->assertFileExists( $this->path."writer_create.test" );
	}

	/**
	 *	Tests Exception of Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateException()
	{
		$this->expectException( IoException::class );
		$writer	= new Writer( $this->path."not_existing_folder/file", 0 );
		$writer->create();
	}

	/**
	 *	Tests Method 'isWritable'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsWritable()
	{
		$this->writer->create();
		$this->assertTrue( $this->writer->isWritable() );

		$writer		= new Writer( $this->path."newFile" );
		$this->assertTrue( $writer->isWritable() );
	}

	/**
	 *	Tests Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetGroup()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->assertTrue( $this->writer->setGroup() );
	}

	/**
	 *	Tests Exception of Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetGroupException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->expectException( InvalidArgumentException::class );
//		$this->writer->setGroup();
	}

	/**
	 *	Tests Method 'setOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOwner()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->assertTrue( $this->writer->setOwner() );
	}

	/**
	 *	Tests Exception of Method 'setOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOwnerException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->expectException( InvalidArgumentException::class );
//		$this->writer->setOwner();
	}

	/**
	 *	Tests Method 'setPermissions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPermissions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->assertTrue( $this->writer->setPermissions() );
	}

	/**
	 *	Tests Exception of Method 'setPermissions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPermissionsException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
//		$this->expectException( InvalidArgumentException::class );
//		$this->writer->setPermissions();
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$removeFile	= $this->path."writer_remove.test";
		file_put_contents( $removeFile, "test" );

		$this->assertFileExists( $removeFile );

		$writer		= new Writer( $removeFile );
		$this->assertTrue( $writer->remove() );

		$this->assertFileDoesNotExist( $removeFile );

		$writer		= new Writer( $this->path."newFile" );
		$this->assertTrue( $writer->remove() );
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSave()
	{
		@unlink( $this->fileName );

		$assertion	= 12;
		$creation	= Writer::save( $this->fileName, $this->fileContent );
		$this->assertEquals( $assertion, $creation );

		$this->assertFileExists( $this->fileName );
	}

	/**
	 *	Tests Exception of Method 'save'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSaveException()
	{
		$this->expectException( IoException::class );
		Writer::save( $this->path."not_existing_folder/file", $this->fileContent );
	}

	/**
	 *	Tests Method 'saveArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSaveArray()
	{
		@unlink( $this->fileName );

		$array		= explode( "\n", $this->fileContent );
		$assertion	= 12;
		$creation	= Writer::saveArray( $this->fileName, $array );
		$this->assertEquals( $assertion, $creation );

		$this->assertFileExists( $this->fileName );
	}

	/**
	 *	Tests Exception of Method 'saveArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSaveArrayException()
	{
		$this->expectException( IoException::class );
		Writer::saveArray( $this->path."not_existing_folder/file", [] );
	}

	/**
	 *	Tests Method 'writeString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteString()
	{
		@unlink( $this->fileName );

		$assertion	= 12;
		$creation	= $this->writer->writeString( $this->fileContent );
		$this->assertEquals( $assertion, $creation );

		$this->assertFileExists( $this->fileName );
	}

	/**
	 *	Tests Exception of Method 'writeString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteStringException()
	{
		$this->expectException( IoException::class );
		$writer	= new Writer( $this->path."not_existing_folder/file" );
		$writer->writeString( "" );
	}

	/**
	 *	Tests Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArray()
	{
		$array		= explode( "\n", $this->fileContent );
		$assertion	= 12;
		$creation	= $this->writer->writeArray( $array );
		$this->assertEquals( $assertion, $creation );

		$this->assertFileExists( $this->fileName );
	}

	/**
	 *	Tests Exception of Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArrayException()
	{
		$this->expectException( IoException::class );
		$writer	= new Writer( $this->path."not_existing_folder/file" );
		$writer->writeArray( [] );
	}
}
