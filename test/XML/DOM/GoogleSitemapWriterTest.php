<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\GoogleSitemapWriter;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class GoogleSitemapWriterTest extends BaseCase
{
	protected $testFile;
	protected $xmlFile;

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->xmlFile	= dirname( __FILE__ ).'/assets/sitemap.xml';
		$this->testFile	= dirname( __FILE__ ).'/assets/test.xml';
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		$builder	= new GoogleSitemapWriter();
		$builder->addLink( "test1.html" );
		$builder->addLink( "test2.html" );

		$builder->write( $this->testFile, "http://www.example.com/" );

		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= file_get_contents( $this->testFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->testFile );
	}

	/**
	 *	Tests Method 'buildSitemap'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteSitemap()
	{
		$links	= array(
			"test1.html",
			"test2.html",
		);

		GoogleSitemapWriter::writeSitemap( $links, $this->testFile, "http://www.example.com/" );

		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= file_get_contents( $this->testFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->testFile );
	}
}
