<?php
/**
 *	TestUnit of UI_HTML_WikiParser.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of UI_HTML_WikiParser.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.05.2008
 *	@version		0.1
 */
class Test_UI_HTML_WikiParserTest extends PHPUnit_Framework_TestCase
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