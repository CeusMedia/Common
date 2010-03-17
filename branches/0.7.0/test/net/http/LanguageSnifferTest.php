<?php
/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_HTTP_LanguageSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
class Test_Net_HTTP_LanguageSnifferTest extends PHPUnit_Framework_TestCase
{
	private $session;
	private $allowed	= array(
		"de",
		"en",
		"fr",
	);
	private $default	= "en";
	
	public function testGetLanguageFromString()
	{
		$accept		= "de-de,de-at;q=0.8,de;q=0.6,en-us;q=0.4,en;q=0.2";
		$assertion	= "de";
		$creation	= Net_HTTP_LanguageSniffer::getLanguageFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );

		$accept		= "da, en-gb;q=0.8, en;q=0.7";
		$assertion	= "en";
		$creation	= Net_HTTP_LanguageSniffer::getLanguageFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );
	}
}
?>