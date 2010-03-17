<?php
/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_HTTP_CharsetSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_Net_HTTP_CharsetSnifferTest extends PHPUnit_Framework_TestCase
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
		$creation	= Net_HTTP_CharsetSniffer::getCharsetFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );

		$accept		= "ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$assertion	= "iso-8859-1";
		$creation	= Net_HTTP_CharsetSniffer::getCharsetFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );
	}
}
?>