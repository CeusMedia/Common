<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\CSS;

use CeusMedia\Common\FS\File\CSS\Combiner;
use CeusMedia\Common\FS\File\CSS\Compressor;
use CeusMedia\Common\FS\File\CSS\Converter;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;

/**
 *	TestUnit of FS\File\CSS\Parser, Converter, Compressor and Combiner.
 *	@package		Tests.FS.File.CSS
 */
class ParserTest extends BaseCase
{
	use TempFolderTrait;

	public function testParseString(): void
	{
		$css	= "/**/ h1,\n h2 { color : red; border: 1px\tsolid; background: url(http://x.org/y.png) }\ndiv\n.x { margin:0 } /* c */ a{b:c}";
		$expected	= [
			'h1'		=> ['color' => 'red', 'border' => '1px solid', 'background' => 'url(http://x.org/y.png)'],
			'h2'		=> ['color' => 'red', 'border' => '1px solid', 'background' => 'url(http://x.org/y.png)'],
			'div .x'	=> ['margin' => '0'],
			'a'			=> ['b' => 'c'],
		];
		self::assertSame( $expected, Converter::convertStringToArray( $css ) );
	}

	public function testCompressString(): void
	{
		$css	= "/**/ div\n.x , a > b {\n  margin: 0  auto;\n  border: 1px\tsolid red; }\n/* c */ p{color: red}";
		self::assertSame( 'div .x,a>b{margin:0 auto;border:1px solid red;}p{color:red}', Compressor::compressString( $css ) );
	}

	public function testCombineRelocatesUrlsOfImportedFiles(): void
	{
		$path	= $this->createTempFolder();
		mkdir( $path.'sub' );
		file_put_contents( $path.'sub/part.css', "a { background: url(img/a.png) }\nb { background: url('../top.png') }\nc { background: url(/abs.png) }" );
		$combined	= ( new Combiner() )->combineString( $path, "/**/ @import \"sub/part.css\";\nbody { margin: 0 }" );
		$this->removeTempFolder();
		self::assertStringContainsString( 'url(sub/img/a.png)', $combined );
		self::assertStringContainsString( "url('sub/../top.png')", $combined );
		self::assertStringContainsString( 'url(/abs.png)', $combined );
		self::assertStringContainsString( 'body { margin: 0 }', $combined );
	}
}
