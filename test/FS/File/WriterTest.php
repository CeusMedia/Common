<?php
/**
 *	TestUnit of File Writer.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of File Writer.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class Test_FS_File_WriterTest extends Test_Case
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
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.test";
		$this->writer	= new FS_File_Writer( $this->fileName );
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreate()
	{
		$writer	= new FS_File_Writer( $this->path."writer_create.test" );
		$writer->create();

		$assertion	= TRUE;
		$creation	= file_exists( $this->path."writer_create.test" );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->path."writer_create.test" );
	}

	/**
	 *	Tests Exception of Method 'create'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateException()
	{
		$this->expectException( 'RuntimeException' );
		$writer	= new FS_File_Writer( "not_existing_folder/file" );
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
		$assertion	= TRUE;
		$creation	= $this->writer->isWritable();
		$this->assertEquals( $assertion, $creation );

		$writer		= new FS_File_Writer( "not_existing" );
		$assertion	= false;
		$creation	= $writer->isWritable();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetGroup()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->writer->setGroup();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetGroupException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		$this->writer->setGroup();
	}

	/**
	 *	Tests Method 'setOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOwner()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->writer->setOwner();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setOwner'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOwnerException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		$this->writer->setOwner();
	}

	/**
	 *	Tests Method 'setPermissions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPermissions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= $this->writer->setPermissions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setPermissions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPermissionsException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		$this->writer->setPermissions();
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

		$assertion	= true;
		$creation	= file_exists( $removeFile );
		$this->assertEquals( $assertion, $creation );

		$writer		= new FS_File_Writer( $removeFile );
		$assertion	= true;
		$creation	= $writer->remove();
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= file_exists( $removeFile );
		$this->assertEquals( $assertion, $creation );

		$writer		= new FS_File_Writer( "no_existing" );
		$assertion	= false;
		$creation	= $writer->remove();
		$this->assertEquals( $assertion, $creation );
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
		$creation	= FS_File_Writer::save( $this->fileName, $this->fileContent );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'save'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSaveException()
	{
		$this->expectException( 'RuntimeException' );
		FS_File_Writer::save( "not_existing_folder/file", $this->fileContent );
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
		$creation	= FS_File_Writer::saveArray( $this->fileName, $array );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'saveArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSaveArrayException()
	{
		$this->expectException( 'RuntimeException' );
		FS_File_Writer::saveArray( "not_existing_folder/file", array() );
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

		$assertion	= true;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'writeString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteStringException()
	{
		$this->expectException( 'RuntimeException' );
		$writer	= new FS_File_Writer( "not_existing_folder/file" );
		$writer->writeString( "" );
	}

	/**
	 *	Tests Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArray()
	{
		@unlink( $this->fileName );

		$array		= explode( "\n", $this->fileContent );
		$assertion	= 12;
		$creation	= $this->writer->writeArray( $array );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'writeArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteArrayException()
	{
		$this->expectException( 'RuntimeException' );
		$writer	= new FS_File_Writer( "not_existing_folder/file" );
		$writer->writeArray( array() );
	}
}
