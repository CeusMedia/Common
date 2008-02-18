<?php
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Sender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.http.request.Sender' );
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Sender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
class Tests_Net_HTTP_Request_SenderTest extends PHPUnit_Framework_TestCase
{
	public function testSend()
	{
		$sender		= new Net_HTTP_Request_Sender( "www.example.com", "/" );
		$response	= $sender->send( array(), "test" );

		$assertion	= true;
		$creation	= (bool) preg_match( "@RFC\s+2606@i", $response );
		$this->assertEquals( $assertion, $creation );
	}
}
?>