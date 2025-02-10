<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML;

use CeusMedia\Common\XML\ElementReader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML Element Reader.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ElementReaderTest extends BaseCase
{
	protected string $url		= 'https://www.rssboard.org/files/sample-rss-2.xml';

	protected string $file;

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

		$assertion	= 'NASA Space Station News';
		$creation	= (string) $element->channel->title;
		self::assertEquals( $assertion, $creation );

		$assertion	= 'http://www.nasa.gov/';
		$creation	= (string) $element->channel->link;
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= 'http://liftoff.msfc.nasa.gov/';
		$creation	= (string) $element->channel->link;
		self::assertEquals( $assertion, $creation );
	}
}
