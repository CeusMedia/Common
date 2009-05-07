<?php
/**
 *	TestUnit of File Writer.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
/**
 *	TestUnit of File Writer.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_WriterTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;
	/**	@var	string		$fileContent	Content of Test File */
	private $fileContent	= "line1\nline2\n";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.test";
		$this->writer	= new File_Writer( $this->fileName );
	}
	
	public function tearDown()
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
		$writer	= new File_Writer( $this->path."writer_create.test" );
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
		$this->setExpectedException( 'RuntimeException' );
		$writer	= new File_Writer( "not_existing_folder/file" );
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

		$writer		= new File_Writer( "not_existing" );
		$assertion	= false;
		$creation	= $writer->isWritable();
		$this->assertEquals( $assertion, $creation );
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

		$writer		= new File_Writer( $removeFile );
		$assertion	= true;
		$creation	= $writer->remove();
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= file_exists( $removeFile );
		$this->assertEquals( $assertion, $creation );

		$writer		= new File_Writer( "no_existing" );
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
		$creation	= File_Writer::save( $this->fileName, $this->fileContent );
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
		$this->setExpectedException( 'RuntimeException' );
		File_Writer::save( "not_existing_folder/file", $this->fileContent );
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
		$creation	= File_Writer::saveArray( $this->fileName, $array );
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
		$this->setExpectedException( 'RuntimeException' );
		File_Writer::saveArray( "not_existing_folder/file", array() );
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
		$this->setExpectedException( 'RuntimeException' );
		$writer	= new File_Writer( "not_existing_folder/file" );
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
		$this->setExpectedException( 'RuntimeException' );
		$writer	= new File_Writer( "not_existing_folder/file" );
		$writer->writeArray( array() );
	}
}
?>