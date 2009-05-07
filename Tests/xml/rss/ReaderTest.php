<?php
/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.rss.Reader' );
/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_RSS_ReaderTest extends PHPUnit_Framework_TestCase
{
	
	protected $url		= "http://www.rssboard.org/files/sample-rss-2.xml";
	protected $file		= "Tests/xml/rss/reader.xml";
	protected $serial	= "Tests/xml/rss/reader.serial";

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->reader	= new XML_RSS_Reader();
	}

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$rss		= $this->reader->readUrl( $this->url );

		$assertion	= "http://liftoff.msfc.nasa.gov/";
		$creation	= $rss['channelData']['link'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $rss['itemList'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Star City";
		$creation	= $rss['itemList'][0]['title'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= strlen( trim( $rss['itemList'][0]['description'] ) ) > 0;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp";
		$creation	= $rss['itemList'][0]['link'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFile()
	{
		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= $this->reader->readFile( $this->file );
		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'readXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadXml()
	{
		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= $this->reader->readXml( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
?>