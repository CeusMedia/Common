<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Log_Writer.
 *	@package		Tests.FS.File.Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File\JSON;

use CeusMedia\Common\FS\File\Log\Writer;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of FS_File_Log_Writer.
 *	@package		Tests.FS.File.Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WriterTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.log";
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
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
		$writer	= new Writer( $this->fileName );
		$writer->note( "1" );

		$this->assertFileExists( $this->fileName );
	}

	/**
	 *	Tests Method 'note'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNote()
	{
		$writer	= new Writer( $this->fileName );

		$creation	= $writer->note( "1" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->fileName );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s";
		$creation	= preg_match( "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s", file_get_contents( $this->fileName ) );
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertTrue( $creation );

		$creation	= $writer->note( "2" );
		$this->assertTrue( $creation );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n[0-9]+ \[([0-9]|[.: -])+\] 2\\n@s";
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertTrue( $creation );
	}
}
