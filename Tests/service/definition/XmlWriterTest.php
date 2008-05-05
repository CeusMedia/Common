<?php
/**
 *	TestUnit of Service_Definition_XmlWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Definition_XmlWriter
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.service.definition/XmlWriter' );
import( 'de.ceus-media.file.yaml.Reader' );
/**
 *	TestUnit of Service_Definition_XmlWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Definition_XmlWriter
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Service_Definition_XmlWriterTest extends PHPUnit_Framework_TestCase
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
		$this->data		= File_YAML_Reader::load( $this->path."services.yaml" );
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
		$creation	= Service_Definition_XmlWriter::build( $this->data );
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
		$creation	= is_int( Service_Definition_XmlWriter::save( $this->fileName, $this->data ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= file_get_contents( $this->path."services.xml" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>