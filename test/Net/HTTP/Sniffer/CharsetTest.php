<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP\Sniffer;

use CeusMedia\Common\Net\HTTP\Sniffer\Charset;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Charset Sniffer.
 *	@package		Tests.net.http
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CharsetTest extends BaseCase
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
		$creation	= Charset::getCharsetFromString( $accept, $this->allowed, $this->default );
		self::assertEquals( $assertion, $creation );

		$accept		= "ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$assertion	= "iso-8859-1";
		$creation	= Charset::getCharsetFromString( $accept, $this->allowed, $this->default );
		self::assertEquals( $assertion, $creation );
	}
}
