<?php
/**
 *	TestUnit of FS_File_Log_Writer.
 *	@package		Tests.file.log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_Log_Writer.
 *	@package		Tests.file.log
 *	@extends		Test_Case
 *	@uses			FS_File_Log_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class Test_FS_File_Log_WriterTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.log";
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$writer	= new FS_File_Log_Writer( $this->fileName );
		$writer->note( 1 );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'note'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNote()
	{
		$writer	= new FS_File_Log_Writer( $this->fileName );

		$assertion	= TRUE;
		$creation	= $writer->note( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s";
		$assertion	= TRUE;
		$creation	= preg_match( "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s", file_get_contents( $this->fileName ) );
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $writer->note( 2 );
		$this->assertEquals( $assertion, $creation );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n[0-9]+ \[([0-9]|[.: -])+\] 2\\n@s";
		$assertion	= TRUE;
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertEquals( $assertion, $creation );
	}
}
