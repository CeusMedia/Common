<?php
/**
 *	TestUnit of FS_File_YAML_Writer.
 *	@package		Test.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_YAML_Writer.
 *	@package		Test.File.YAML
 *	@extends		Test_Case
 *	@uses			FS_File_YAML_Writer
 *	@uses			FS_File_YAML_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_FS_File_YAML_WriterTest extends Test_Case
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
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
	public function tearDown()
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
		$writer		= new FS_File_YAML_Writer( $this->fileName );
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
		$writer	= new FS_File_YAML_Writer( $this->fileName );

		$assertion	= TRUE;
		$creation	= is_int( $writer->write( $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= FS_File_YAML_Reader::load( $this->fileName );
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
		$creation	= is_int( FS_File_YAML_Writer::save( $this->fileName, $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= FS_File_YAML_Reader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
