<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of FS\File\CSS\Theme\Minimizer.
 *	@package		Tests.FS.File.CSS.Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\CSS\Theme;

use CeusMedia\Common\FS\File\CSS\Theme\Minimizer;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of FS\File\CSS\Theme\Minimizer.
 *	@package		Tests.FS.File.CSS.Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class MinimizerTest extends BaseCase
{
	protected string $path;

	protected string $cssPath;

	public function setUp(): void
	{
		$this->path		= sys_get_temp_dir().'/CeusMediaCommonTestMinimizer'.getmypid().'/';
		$this->cssPath	= $this->path.'default/css/screen/';
		@mkdir( $this->cssPath, 0777, TRUE );
		file_put_contents( $this->cssPath.'style.css', "@import \"layout.css\";\n@import url( \"colors.css\" );\n" );
		file_put_contents( $this->cssPath.'layout.css', "body {\n\tmargin: 0px;\n\tpadding: 0px;\n}\n" );
		file_put_contents( $this->cssPath.'colors.css', "/* colors */\na {\n\tcolor: red;\n}\n" );
	}

	public function tearDown(): void
	{
		foreach( glob( $this->cssPath.'*' ) ?: [] as $file )
			unlink( $file );
		@rmdir( $this->cssPath );
		@rmdir( $this->path.'default/css' );
		@rmdir( $this->path.'default' );
		@rmdir( $this->path );
	}

	public function testMinimize(): void
	{
		$minimizer	= $this->getMinimizer();
		self::assertTrue( $minimizer->minimize( 'style.css' ) );

		$combined	= $this->cssPath.'combined.style.all.css';
		self::assertFileExists( $combined );
		$content	= (string) file_get_contents( $combined );
		self::assertStringContainsString( 'margin: 0px', $content );
		self::assertStringContainsString( 'color: red', $content );
		self::assertStringNotContainsString( '@import', $content );

		$statistics	= $minimizer->getStatistics();
		self::assertSame( realpath( $this->cssPath.'style.css' ), $statistics['fileSource'] );
		self::assertSame( realpath( $combined ), $statistics['fileCombined'] );
		self::assertArrayNotHasKey( 'fileCompressed', $statistics );
		self::assertFileDoesNotExist( $this->cssPath.'combined.style.all.min.css' );
	}

	public function testMinimizeWithCompression(): void
	{
		$minimizer	= $this->getMinimizer();
		self::assertTrue( $minimizer->minimize( 'style.css', TRUE ) );

		$compressed	= $this->cssPath.'combined.style.all.min.css';
		self::assertFileExists( $compressed );
		$content	= (string) file_get_contents( $compressed );
		self::assertStringNotContainsString( '/* colors */', $content );
		self::assertStringContainsString( 'color:red', str_replace( ' ', '', $content ) );

		$statistics	= $minimizer->getStatistics();
		self::assertSame( realpath( $compressed ), $statistics['fileCompressed'] );
		self::assertSame( strlen( $content ), $statistics['sizeCompressed'] );
	}

	public function testMinimizeMissingFileException(): void
	{
		$this->expectException( Exception::class );
		$this->getMinimizer()->minimize( 'missing.css' );
	}

	protected function getMinimizer(): Minimizer
	{
		return ( new Minimizer( $this->path ) )
			->setTheme( 'default/' )
			->setCssFolder( 'css/' )
			->setMedium( 'screen/' )
			->setCombinerPrefix( 'combined.' )
			->setCombinerSuffix( '.all' )
			->setCompressorSuffix( '.min' );
	}
}
