<?php
/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@extends		Test_Case
 *	@uses			Net_FTP_Connection
 *	@uses			Net_FTP_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Test_Net_FTP_WriterTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->config	= self::$_config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in Common.ini' );

		$this->connection	= new Net_FTP_Connection( $this->host, $this->port );
		$this->connection->login( $this->username, $this->password );

		@mkDir( $this->local );
		@mkDir( $this->local."folder" );
		@file_put_contents( $this->local."source.txt", "source file" );
		@file_put_contents( $this->local."folder/source.txt", "source file" );

		if( $this->path )
			$this->connection->setPath( $this->path );

		$this->reader	= new Net_FTP_Reader( $this->connection );
		$this->writer	= new Net_FTP_Writer( $this->connection );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in Common.ini' );
		@unlink( $this->local."source.txt" );
		@unlink( $this->local."target.txt" );
		@unlink( $this->local."renamed.txt" );
		@unlink( $this->local."folder/source.txt" );
		@unlink( $this->local."folder/target.txt" );
		@unlink( $this->local."copy/source.txt" );
		@unlink( $this->local."moved/source.txt" );
		@unlink( $this->local."rightsTest" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local."copy" );
		@rmDir( $this->local."created" );
		@rmDir( $this->local."moved" );
		@rmDir( $this->local );
	}

	/**
	 *	Tests Method 'changeRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeRights()
	{
		file_put_contents( $this->local."rightsTest", "this file will be removed" );
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) != "WIN" )
		{
			$assertion	= 0777;
			$creation	= $this->writer->changeRights( "rightsTest", 0777 );
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
		$creation	= file_exists( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->copyFile( "folder/source.txt", "folder/target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."folder/target.txt" );
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
		$creation	= file_exists( $this->local."folder/target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
		$this->expectException( 'RuntimeException' );
		$this->writer->copyFile( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException2()
	{
		$this->expectException( 'RuntimeException' );
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
		$creation	= file_exists( $this->local."copy" );
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
		$creation	= file_exists( $this->local."created" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= preg_replace( '/^(.+)\/$/', '\\1', "/".$this->path );
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$this->writer->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
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
		$creation	= file_exists( $this->local."source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."target.txt" );
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
		$creation	= file_exists( $this->local."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
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
		$creation	= $this->writer->putFile( $this->local."source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "source file";
		$creation	= file_get_contents( $this->local."target.txt" );
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
		$creation	= file_exists( $this->local."folder/source.txt" );
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
		$creation	= file_exists( $this->local."folder" );
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
		$creation	= file_exists( $this->local."renamed.txt" );
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

		$assertion	= "/".$this->path."folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->writer->setPath( "/".$this->path."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );
	}
}
