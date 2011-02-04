<?php
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Sender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
class Test_Net_HTTP_Request_SenderTest extends PHPUnit_Framework_TestCase
{
	public function testSend()
	{
		$host		= "www.example.com";
		$url		= "/";
		$needle		= "@RFC\s+2606@i";
		
/*		$host		= "ceus-media.de";
		$url		= "/";
		$needle		= "@ceus media@i";
*/
		$sender		= new Net_HTTP_Request_Sender( $host, $url );
		$response	= $sender->send( array(), "test" );

		$creation	= is_object( $response );
		$this->assertTrue( $creation );

		$creation	= (bool) preg_match( $needle, $response->getBody() );
		$this->assertTrue( $creation );
	}
}
?>