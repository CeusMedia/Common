<?php
/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Test_XML_RSS_ParserTest extends PHPUnit_Framework_TestCase
{

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$this->file		= dirname( __FILE__ )."/parser.xml";
		$this->serial	= dirname( __FILE__ )."/parser.serial";

		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= XML_RSS_Parser::parse( $xml );

#		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>