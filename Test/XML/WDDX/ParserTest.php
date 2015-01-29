<?php
/**
 *	TestUnit of XML_WDDX_Parser.
 *	@package		Tests.xml.wddx
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
if( !class_exists( 'PHPUnit_Framework_TestCase' ) )
	require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML_WDDX_Parser.
 *	@package		Tests.xml.wddx
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_WDDX_Parser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_XML_WDDX_ParserTest extends PHPUnit_Framework_TestCase
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
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
		if( !function_exists( 'wddx_packet_start' ) )
			return;
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
?>