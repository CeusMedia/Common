<?php
/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
require_once dirname( dirname( dirname( __DIR__ ) ) ).'/initLoaders.php';
/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *	@version		0.6
 */
class Test_Net_HTTP_Header_FieldTest extends Test_Case
{
	public function testConstruct()
	{
		$header	= new Net_HTTP_Header_Field( "key", "value" );
		$assertion	= true;
		$creation	= (bool) strlen( $header->toString() );
		$this->assertEquals( $assertion, $creation );
	}

	public function testToString()
	{
		$header	= new Net_HTTP_Header_Field( "key", "value" );
		$assertion	= "Key: value";
		$creation	= $header->toString();
		$this->assertEquals( $assertion, $creation );

		$header	= new Net_HTTP_Header_Field( "key-with-more-words", "value" );
		$assertion	= "Key-With-More-Words: value";
		$creation	= $header->toString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
