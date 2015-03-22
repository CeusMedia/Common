<?php
/**
 *	TestUnit of File_YAML_Writer.
 *	@package		Test.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_YAML_Writer.
 *	@package		Test.File.YAML
 *	@extends		Test_Case
 *	@uses			File_YAML_Writer
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_File_YAML_WriterTest extends Test_Case
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->fileName	= dirname( __FILE__ )."/writer.yaml";
		$this->data		= array(
			'test1',
			'test2'
		);
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
		$writer		= new File_YAML_Writer( $this->fileName );
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
		$writer	= new File_YAML_Writer( $this->fileName );

		$assertion	= TRUE;
		$creation	= is_int( $writer->write( $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= File_YAML_Reader::load( $this->fileName );
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
		$creation	= is_int( File_YAML_Writer::save( $this->fileName, $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= $this->data;
		$creation	= File_YAML_Reader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
