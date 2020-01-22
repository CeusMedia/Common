<?php
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

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
	public function oldTestSend()
	{
		$host		= "www.example.com";
		$url		= "/";
		$needle		= "@Example Domain@i";

//		$host		= "ceusmedia.de";
//		$url		= "https://ceusmedia.de/";
//		$needle		= "@ceus media@i";

		$sender		= new Net_HTTP_Request_Sender( $host, $url );
//		$sender->setPort( 443 );
		$response	= $sender->send();

		$creation	= is_object( $response );
		$this->assertTrue( $creation );

		$creation	= (bool) preg_match( $needle, $response->getBody() );
		$this->assertTrue( $creation );
	}
}
?>
