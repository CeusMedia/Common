<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\XML;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\ElementReader;

/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ElementReaderTest extends BaseCase
{

	protected $url		= 'http://www.rssboard.org/files/sample-rss-2.xml';
	protected $file;

	public function setUp(): void
	{
		$this->file		= dirname( __FILE__ ).'/assets/element_reader.xml';
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

		$element	= ElementReader::readUrl( $this->url );

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
		$element	= ElementReader::readFile( $this->file );

		$assertion	= 'Liftoff News';
		$creation	= (string) $element->channel->title;
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'http://liftoff.msfc.nasa.gov/';
		$creation	= (string )$element->channel->link;
		$this->assertEquals( $assertion, $creation );
	}
}
