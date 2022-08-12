<?php
declare( strict_types = 1 );

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.02.2008
 *
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\Test\BaseCase;
use CeusMedia\Common\XML\DOM\GoogleSitemapBuilder;

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.02.2008
 *
 */
class GoogleSitemapBuilderTest extends BaseCase
{
	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->xmlFile	= dirname( __FILE__ ).'/sitemap.xml';
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
	}
}
