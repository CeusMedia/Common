<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP\Sniffer;

use CeusMedia\Common\Net\HTTP\Sniffer\Language;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Language Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class LanguageTest extends BaseCase
{
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
		$creation	= Language::getLanguageFromString( $accept, $this->allowed, $this->default );
		self::assertEquals( $assertion, $creation );

		$accept		= "da, en-gb;q=0.8, en;q=0.7";
		$assertion	= "en";
		$creation	= Language::getLanguageFromString( $accept, $this->allowed, $this->default );
		self::assertEquals( $assertion, $creation );
	}
}
