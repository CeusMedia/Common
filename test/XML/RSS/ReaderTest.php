<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

use CeusMedia\Common\XML\RSS\Reader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	protected string $file;
	protected string $serial;
	protected Reader $reader;
	protected string $url		= 'https://www.rssboard.org/files/sample-rss-2.xml';

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );
		$rss		= $this->reader->readUrl( $this->url );

		$assertion	= "http://www.nasa.gov/";
		$creation	= $rss['channelData']['link'];
		self::assertEquals( $assertion, $creation );
		self::assertCount( 5, $rss['itemList'] );

		$oldest		= end($rss['itemList']);

		$expected	= "NASA Plans Coverage of Roscosmos Spacewalk Outside Space Station";
		self::assertEquals( $expected, $oldest['title'] );

		self::assertTrue( strlen( trim( $oldest['description'] ) ) > 0 );

		$expected	= "http://liftoff.msfc.nasa.gov/news/2003/news-laundry.asp";
		self::assertEquals( $expected, $oldest['link'] );
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
#		file_put_contents( $this->serial, serialize( $creation ) );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	protected function setUp(): void
	{
		$this->file		= dirname( __FILE__ )."/assets/reader.xml";
		$this->serial	= dirname( __FILE__ )."/assets/reader.serial";
		$this->reader	= new Reader();
	}
}
