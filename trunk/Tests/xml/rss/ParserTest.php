<?php
/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.xml.rss.Parser' );
/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_RSS_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Tests_XML_RSS_ParserTest extends PHPUnit_Framework_TestCase
{
	protected $file		= "Tests/xml/rss/parser.xml";
	protected $serial	= "Tests/xml/rss/parser.serial";

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= XML_RSS_Parser::parse( $xml );
#		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>