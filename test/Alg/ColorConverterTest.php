<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Alg;

use CeusMedia\Common\Alg\ColorConverter;
use CeusMedia\Common\Alg\HtmlMetaTagReader;
use CeusMedia\Common\Alg\Text\EncodingConverter;
use CeusMedia\Common\Alg\UnusedVariableFinder;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of Alg\ColorConverter and some other Alg classes.
 *	@package		Tests.Alg
 */
class ColorConverterTest extends BaseCase
{
	public function testColorConversions(): void
	{
		self::assertSame( [0, 0, 0, 1], ColorConverter::rgb2cmyk( [0, 0, 0] ) );
		self::assertSame( [255, 128, 0], ColorConverter::html2rgb( '#FF8000' ) );
		self::assertSame( [255, 136, 0], ColorConverter::html2rgb( 'f80' ) );
		self::assertSame( 'FF8000', ColorConverter::rgb2html( [255, 128, 0] ) );
		self::assertSame( 'FF0010', ColorConverter::rgb2html( [300, -5, 15.6] ) );
		self::assertEquals( [255, 0, 0], ColorConverter::hsv2rgb( [360, 100, 100] ) );
		self::assertEquals( [255, 0, 0], ColorConverter::hsv2rgb( [0, 100, 100] ) );
		self::assertEquals( [30, 100, 100], ColorConverter::html2hsv( '#ff8000' ) );
		self::assertEquals( [10, 200, 30], ColorConverter::xyz2rgb( ColorConverter::rgb2xyz( [10, 200, 30] ) ) );
		self::assertSame( [NULL, NULL, NULL], ColorConverter::html2rgb( 'red' ) );
	}

	public function testMetaTagsOnOneLine(): void
	{
		$html	= '<head><META name="author" content="me"><meta name="robots" content="noindex"/><meta charset="utf-8"></head>';
		self::assertSame( ['author' => 'me', 'robots' => 'noindex'], HtmlMetaTagReader::getMetaTags( $html ) );
	}

	public function testEncodingConverterFailure(): void
	{
		self::assertSame( 'Gr'.chr( 246 ).chr( 223 ).'e', EncodingConverter::convert( 'Größe', 'UTF-8', 'ISO-8859-1' ) );
		$this->expectException( InvalidArgumentException::class );
		EncodingConverter::convert( "\xff\xfe invalid", 'UTF-8', 'ISO-8859-1' );
	}

	public function testUnusedVariableFinder(): void
	{
		$code	= "class A\n{\n\tpublic function test( \$a, \$b )\n\t{\n\t\t\$unused = 1;\n\t\t\$used = 2;\n\t\t\$used = \$used + 1;\n\t\treturn \$a + \$used;\n\t}\n}";
		$finder	= ( new UnusedVariableFinder() )->readCode( $code );
		self::assertSame( ['test' => ['b', 'unused']], $finder->getUnusedVars() );
	}
}
