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

	public function testParse_exception_unknownUnit(): void
	{
		$this->expectException( \DomainException::class );
		UnitParser::parse( '10 µB' );
	}

	public static function provideCases(): array
	{
		return [
			'byte'			=> ["256B", 256],
			'kilobyte'		=> ["256 kB", 256 * 1000],
			'kebibyte'		=> ["256 KB", 256 * 1024],
			'gigabyte'			=> ["100GB", 100 * 10 ** 9],
			'gigabyte spaced'	=> ["100 GB", 100 * 10 ** 9],
			'terabyte'			=> ["2TB", 2 * 10 ** 12],
			'terabyte spaced'	=> ["2 TB", 2 * 10 ** 12],
			'tebibyte'			=> ["2TiB", 2 * 2 ** 40],
			'tebibyte spaced'	=> ["2 TiB", 2 * 2 ** 40],
			'petabyte'			=> ["3PB", 3 * 10 ** 15],
			'petabyte spaced'	=> ["3 PB", 3 * 10 ** 15],
			'pebibyte'			=> ["3PiB", 3 * 2 ** 50],
			'pebibyte spaced'	=> ["3 PiB", 3 * 2 ** 50],
			'exabyte'			=> ["4EB", 4 * 10 ** 18],
			'exabyte spaced'	=> ["4 EB", 4 * 10 ** 18],
			'exibyte'			=> ["4EiB", 4 * 2 ** 60],
			'exibyte spaced'	=> ["4 EiB", 4 * 2 ** 60],
		];
	}
}
