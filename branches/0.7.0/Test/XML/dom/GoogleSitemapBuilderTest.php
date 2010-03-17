<?php
/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
class Test_XML_DOM_GoogleSitemapBuilderTest extends PHPUnit_Framework_TestCase
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
?>