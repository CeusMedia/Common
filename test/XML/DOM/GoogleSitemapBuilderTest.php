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

use CeusMedia\Common\XML\DOM\GoogleSitemapBuilder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class GoogleSitemapBuilderTest extends BaseCase
{
	protected $xmlFile;

	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->xmlFile	= dirname( __FILE__ ).'/assets/sitemap.xml';
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$builder	= new GoogleSitemapBuilder();
		$builder->addLink( "test1.html" );
		$builder->addLink( "test2.html" );

		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= $builder->build( "http://www.example.com/" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'buildSitemap'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuildSitemap()
	{
		$links	= array(
			"test1.html",
			"test2.html",
		);

		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= GoogleSitemapBuilder::buildSitemap( $links, "http://www.example.com/" );
		self::assertEquals( $assertion, $creation );
	}
}
