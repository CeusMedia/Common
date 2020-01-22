<?php
/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@extends		Test_Case
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Test_XML_ElementReaderTest extends Test_Case
{

	protected $url		= 'http://www.rssboard.org/files/sample-rss-2.xml';
	protected $file;

	public function setUp()
	{
		$this->file		= dirname( __FILE__ ).'/element_reader.xml';
	}

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );

		$element	= XML_ElementReader::readUrl( $this->url );

		$assertion	= 'Liftoff News';
		$creation	= (string) $element->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'http://liftoff.msfc.nasa.gov/';
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

		$assertion	= 'Liftoff News';
		$creation	= (string) $element->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'http://liftoff.msfc.nasa.gov/';
		$creation	= (string )$element->channel->link;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
