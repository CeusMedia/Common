<?php
/**
 *	UnitTest for Request Header.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.net.http.request.Header' );
/**
 *	UnitTest for Request Sender.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
class Net_HTTP_Request_HeaderTest extends PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$header	= new Net_HTTP_Request_Header( "key", "value" );
		$assertion	= true;
		$creation	= (bool) count( $header->toString() );
		$this->assertEquals( $assertion, $creation );
	}

	public function testToString()
	{
		$header	= new Net_HTTP_Request_Header( "key", "value" );
		$assertion	= "Key: value\r\n";
		$creation	= $header->toString();
		$this->assertEquals( $assertion, $creation );

		$header	= new Net_HTTP_Request_Header( "key-with-more-words", "value" );
		$assertion	= "Key-With-More-Words: value\r\n";
		$creation	= $header->toString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>