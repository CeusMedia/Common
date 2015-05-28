<?php
/**
 *	TestUnit of Net Service Definition XmlWriter.
 *	@package		Tests.net.service.definition
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Net Service Definition XmlWriter.
 *	@package		Tests.net.service.definition
 *	@extends		Test_Case
 *	@uses			Net_Service_Definition_XmlWriter
 *	@uses			FS_File_YAML_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_Definition_XmlWriterTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."writer.xml";
		$this->data		= FS_File_YAML_Reader::load( $this->path."services.yaml" );
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
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$assertion	= file_get_contents( $this->path."services.xml" );
		$creation	= Net_Service_Definition_XmlWriter::build( $this->data );
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
		$creation	= is_int( Net_Service_Definition_XmlWriter::save( $this->fileName, $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $this->path."services.xml" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
