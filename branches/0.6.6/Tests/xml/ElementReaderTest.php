<?php
/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.ElementReader' );
/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_ElementReaderTest extends PHPUnit_Framework_TestCase
{
	
	protected $url	= "http://www.rssboard.org/files/sample-rss-2.xml";
	protected $file	= "Tests/xml/element_reader.xml";

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$element	= XML_ElementReader::readUrl( $this->url );
		
		$assertion	= "Liftoff News";
		$creation	= (string) $element->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://liftoff.msfc.nasa.gov/";
		$creation	= (string )$element->channel->link;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFile()
	{
		$element	= XML_ElementReader::readFile( $this->file );
		
		$assertion	= "Liftoff News";
		$creation	= (string) $element->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://liftoff.msfc.nasa.gov/";
		$creation	= (string )$element->channel->link;
		$this->assertEquals( $assertion, $creation );
	}
}
?>