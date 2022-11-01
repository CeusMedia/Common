<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_YAML_Writer.
 *	@package		Test.FS.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */

namespace CeusMedia\CommonTest\FS\File\YAML;

use CeusMedia\Common\FS\File\JSON\Reader;
use CeusMedia\Common\FS\File\JSON\Writer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_YAML_Writer.
 *	@package		Test.FS.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class WriterTest extends BaseCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName	= dirname( __FILE__ )."/writer.yaml";
		$this->data		= array(
			'test1',
			'test2'
		);
		@unlink( $this->fileName );
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
	public function testConstruct()
	{
		$writer		= new Writer( $this->fileName );
		$writer->write( $this->data );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'write'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		$writer	= new Writer( $this->fileName );

		$assertion	= TRUE;
		$creation	= is_int( $writer->write( $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= Reader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'save'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSave()
	{
		$assertion	= TRUE;
		$creation	= is_int( Writer::save( $this->fileName, $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= Reader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
