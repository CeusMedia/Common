<?php
/**
 *	TestUnit of XML Node Reader.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_NodeReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.NodeReader' );
/**
 *	TestUnit of XML Node Reader.
 *	@package		Tests.xml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_NodeReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_NodeReaderTest extends PHPUnit_Framework_TestCase
{
	
	protected $url		= "http://cyber.law.harvard.edu/rss/examples/rss2sample.xml";
	protected $file		= "Tests/xml/node_reader.xml";

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$node		= XML_NodeReader::readUrl( $this->url );
		
		$assertion	= "Liftoff News";
		$creation	= (string) $node->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://liftoff.msfc.nasa.gov/";
		$creation	= (string )$node->channel->link;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFile()
	{
		$node		= XML_NodeReader::readFile( $this->file );
		
		$assertion	= "Liftoff News";
		$creation	= (string) $node->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://liftoff.msfc.nasa.gov/";
		$creation	= (string )$node->channel->link;
		$this->assertEquals( $assertion, $creation );
	}
}
?>