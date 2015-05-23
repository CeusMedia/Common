<?php
/**
 *	TestUnit of UI_HTML_WikiParser.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of UI_HTML_WikiParser.
 *	@package		Tests.{classPackage}
 *	@extends		Test_Case
 *	@uses			UI_HTML_WikiParser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_UI_HTML_WikiParserTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$parser		= new UI_HTML_WikiParser();
		$assertion	= file_get_contents( $this->path."wiki-parsed.html" );
		$creation	= $parser->parse( file_get_contents( $this->path."wiki-syntax.txt" ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>