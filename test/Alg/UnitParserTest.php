<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg;

use CeusMedia\Common\Alg\UnitParser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class UnitParserTest extends BaseCase
{
	/**
	 * @dataProvider provideCases
	 */
	public function testParse( string $input, int $expected ): void
	{
		self::assertEquals( $expected, UnitParser::parse( $input ) );
	}

	public function testParse_exception_emptyString(): void
	{
		$this->expectException( \InvalidArgumentException::class );
		UnitParser::parse( ' ' );
	}

	public static function provideCases(): array
	{
		return [
			'byte'			=> ["256B", 256],
			'kilobyte'		=> ["256 kB", 256 * 1000],
			'kebibyte'		=> ["256 KB", 256 * 1024],
			'terabyte'		=> ["2TB", 2 * 10 ** 12],
			'petabyte'		=> ["3PB", 3 * 10 ** 15],
			'exabyte'		=> ["4EB", 4 * 10 ** 18],
		];
	}
}
