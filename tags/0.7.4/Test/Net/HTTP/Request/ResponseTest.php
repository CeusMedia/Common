<?php
/**
 *	TestUnit of Request Response.
 *	@package		Tests.net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Request Response.
 *	@package		Tests.net.http.request
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_HTTP_Request_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_Net_HTTP_Request_ResponseTest extends PHPUnit_Framework_TestCase
{
	/**	@var	array		$list		Instance of Request Response */
	private $receiver;
	
	public function setUp()
	{
		$this->response	= new Net_HTTP_Request_Response();
	}

	public function testWrite()
	{
		$assertion	= "content1content2";
		$this->response->write( "content1" );
		$this->response->write( "content2" );
		ob_start();
		@$this->response->send();
		$creation	= ob_get_clean();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSend()
	{
		$assertion	= "content3";
		$this->response->write( "content3" );
		ob_start();
		@$this->response->send();
		$creation	= ob_get_clean();
		$this->assertEquals( $assertion, $creation );
	}
}
?>