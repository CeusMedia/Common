<?php
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
require_once 'Test/initLoaders.php5';
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Sender
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
class Test_Net_HTTP_Request_SenderTest extends Test_Case
{
	public function testSend()
	{
		$host		= "www.example.com";
		$url		= "/";
		$needle		= "@Example Domain@i";
		
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
