<?php
/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML RSS 2 Parser.
 *	@package		Tests.xml.rss
 *	@extends		Test_Case
 *	@uses			XML_RSS_Parser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			20.02.2008
 *	@version		0.1
 */
class Test_XML_RSS_ParserTest extends Test_Case
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
