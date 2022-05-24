<?php
/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
class Test_Net_HTTP_Sniffer_CharsetTest extends Test_Case
{
	private $session;
	private $allowed	= array(
		"iso-8859-1",
		"iso-8859-5",
		"unicode-1-1",
		"utf-8",
	);
	private $default	= "unicode-1-1";

	public function testGetCharsetFromString()
	{
		$accept		= "iso-8859-5, unicode-1-1;q=0.8";
		$assertion	= "iso-8859-5";
		$creation	= Net_HTTP_Sniffer_Charset::getCharsetFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );

		$accept		= "ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$assertion	= "iso-8859-1";
		$creation	= Net_HTTP_Sniffer_Charset::getCharsetFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );
	}
}
