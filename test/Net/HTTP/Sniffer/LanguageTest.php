<?php
/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			16.02.2008
 *
 */
class Test_Net_HTTP_Sniffer_LanguageTest extends BaseCase
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
		$creation	= Net_HTTP_Sniffer_Language::getLanguageFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );

		$accept		= "da, en-gb;q=0.8, en;q=0.7";
		$assertion	= "en";
		$creation	= Net_HTTP_Sniffer_Language::getLanguageFromString( $accept, $this->allowed, $this->default );
		$this->assertEquals( $assertion, $creation );
	}
}
