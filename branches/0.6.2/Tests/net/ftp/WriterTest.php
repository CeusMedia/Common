<?php
/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Connection
 *	@uses			Net_FTP_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.ftp.Connection' );
import( 'de.ceus-media.net.ftp.Writer' );
/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Connection
 *	@uses			Net_FTP_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Tests_Net_FTP_WriterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$host		= "localhost";
		$port		= 21;
		$username	= "ftp_user";
		$password	= "ftp_pass";
		$this->connection	= new Net_FTP_Connection( $host, $port );
		$this->connection->login( $username, $password );
		$this->ftpPath	= dirname( __FILE__ )."/upload/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		@mkDir( $this->ftpPath );
		@mkDir( $this->ftpPath."folder" );
		@file_put_contents( $this->ftpPath."source.txt", "source file" );
		@file_put_contents( $this->ftpPath."folder/source.txt", "source file" );

		$this->reader	= new Net_FTP_Reader( $this->connection );
		$this->writer	= new Net_FTP_Writer( $this->connection );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->ftpPath."source.txt" );
		@unlink( $this->ftpPath."target.txt" );
		@unlink( $this->ftpPath."renamed.txt" );
		@unlink( $this->ftpPath."folder/source.txt" );
		@unlink( $this->ftpPath."folder/target.txt" );
		@unlink( $this->ftpPath."copy/source.txt" );
		@unlink( $this->ftpPath."moved/source.txt" );
		@unlink( $this->ftpPath."rightsTest" );
		@rmDir( $this->ftpPath."folder" );
		@rmDir( $this->ftpPath."copy" );
		@rmDir( $this->ftpPath."created" );
		@rmDir( $this->ftpPath."moved" );
		@rmDir( $this->ftpPath );
	}

	/**
	 *	Tests Method 'changeRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeRights()
	{
		file_put_contents( $this->ftpPath."rightsTest", "this file will be removed" );
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) != "WIN" )
		{
			$assertion	= TRUE;
			$creation	= $this->writer->changeRights( "rightsTest", 777 );
			$this->assertEquals( $assertion, $creation );

			$file		= $this->reader->getFile( "rightsTest" );
			$assertion	= 777;
			$creation	= $file['octal'];
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFile()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->copyFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->copyFile( "folder/source.txt", "folder/target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."folder/target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileInPath()
	{
		$this->writer->setPath( "folder" );

		$assertion	= TRUE;
		$creation	= $this->writer->copyFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."folder/target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->writer->copyFile( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException2()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->writer->copyFile( "source.txt", "not_existing/not_relevant.txt" );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->copyFolder( "folder", "copy" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."copy" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $this->reader->getFileList( "copy" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'createFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->createFolder( "created" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."created" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= "/";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$this->writer->setPath( "folder" );

		$assertion	= "/folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFile()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->moveFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->moveFolder( "folder", "moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->writer->moveFolder( "moved", "moved" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'putFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPutFile()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->putFile( $this->ftpPath."source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "source file";
		$creation	= file_get_contents( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFile()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->removeFile( "folder/source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder/source.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->removeFolder( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'renameFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFile()
	{
		$assertion	= TRUE;
		$creation	= $this->writer->renameFile( "source.txt", "renamed.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."renamed.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$assertion	= FALSE;
		$creation	= $this->writer->setPath( "not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->setPath( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->setPath( "/folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->writer->setPath( "not_existing" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>