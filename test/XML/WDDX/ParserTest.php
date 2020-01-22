<?php
/**
 *	TestUnit of XML_WDDX_Parser.
 *	@package		Tests.xml.wddx
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_WDDX_Parser.
 *	@package		Tests.xml.wddx
 *	@extends		Test_Case
 *	@uses			XML_WDDX_Parser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_ParserTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		if( !extension_loaded( 'wddx' ) )
			$this->markTestSkipped( 'Missing WDDX support' );

		$this->path	= dirname( __FILE__ )."/";
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$content	= file_get_contents( $this->path."reader.wddx" );
		$data		= array(
			'data'	=> array(
				'test_string'	=> "data to be passed by WDDX",
				'test_bool'		=> TRUE,
				'test_int'		=> 12,
				'test_double'	=> 3.1415926,
			)
		);

		$assertion	= $data;
		$creation	= XML_WDDX_Parser::parse( $content );
		$this->assertEquals( $assertion, $creation );
	}
}
