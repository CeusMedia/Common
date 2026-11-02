<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net\HTTP\Header\Field\Parser.
 *	@package		Tests.Net.HTTP.Header.Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP\Header\Field;

use CeusMedia\Common\Net\HTTP\Header\Field\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Header\Field\Parser.
 *	@package		Tests.Net.HTTP.Header.Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class ParserTest extends BaseCase
{
	public function testParse(): void
	{
		$field	= Parser::parse( 'Content-Type: text/html' );
		self::assertSame( 'content-type', $field->getName() );
		self::assertSame( 'text/html', $field->getValue() );
	}

	public function testParseWithQualifiedValues(): void
	{
		$field	= Parser::parse( 'Accept-Language: de;q=0.8, en', TRUE );
		self::assertSame( 'de;q=0.8, en', $field->getValue() );
		self::assertSame( ['en' => 1.0, 'de' => 0.8], $field->getValue( TRUE ) );
	}
}
