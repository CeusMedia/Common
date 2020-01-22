<?php
/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@extends		Test_Case
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
class Test_XML_DOM_GoogleSitemapBuilderTest extends Test_Case
{
	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
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
		$builder	= new XML_DOM_GoogleSitemapBuilder();
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
		$creation	= XML_DOM_GoogleSitemapBuilder::buildSitemap( $links, "http://www.example.com/" );
		$this->assertEquals( $assertion, $creation );
	}
}
