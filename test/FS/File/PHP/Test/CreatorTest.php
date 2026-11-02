<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of FS\File\PHP\Test\Creator.
 *	@package		Tests.FS.File.PHP.Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\PHP\Test;

use CeusMedia\Common\FS\File\PHP\Test\Creator;
use CeusMedia\CommonTest\BaseCase;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 *	TestUnit of FS\File\PHP\Test\Creator.
 *	@package		Tests.FS.File.PHP.Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CreatorTest extends BaseCase
{
	protected string $path;

	public function setUp(): void
	{
		$this->path	= sys_get_temp_dir().'/CeusMediaCommonTestCreator'.getmypid().'/';
		@mkdir( $this->path.'src/Foo', 0777, TRUE );
		file_put_contents( $this->path.'src/Foo/Bar.php', '<?php
namespace Demo\Lib\Foo;

use RuntimeException;

/**
 *	Example class.
 */
class Bar
{
	/**
	 *	@param		int		$a
	 *	@return		int
	 *	@throws		\InvalidArgumentException
	 */
	public function doIt( int $a ): int
	{
		if( $a < 0 )
			throw new \InvalidArgumentException( "negative" );
		if( $a > 10 )
			throw new RuntimeException( "too large" );
		return $a;
	}

	public static function create(): self
	{
		return new self();
	}

	protected function hidden(): void
	{
	}
}
' );
		file_put_contents( $this->path.'src/Foo/Contract.php', '<?php
namespace Demo\Lib\Foo;

interface Contract
{
	public function run(): void;
}
' );
	}

	public function tearDown(): void
	{
		if( !is_dir( $this->path ) )
			return;
		$iterator	= new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->path, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::CHILD_FIRST
		);
		foreach( $iterator as $entry )
			$entry->isDir() ? rmdir( $entry->getPathname() ) : unlink( $entry->getPathname() );
		rmdir( $this->path );
	}

	public function testCreateForFile(): void
	{
		$creator	= $this->getCreator();
		$creator->createForFile( 'Foo\Bar' );
		$targetFile	= $this->path.'test/Foo/BarTest.php';
		self::assertSame( $targetFile, $creator->getTargetFile() );
		self::assertFileExists( $targetFile );

		$content	= (string) file_get_contents( $targetFile );
		self::assertStringStartsWith( '<?php', $content );
		self::assertStringContainsString( 'namespace Demo\LibTest\Foo;', $content );
		self::assertStringContainsString( 'use Demo\Lib\Foo\Bar;', $content );
		self::assertStringContainsString( 'use PHPUnit\Framework\TestCase;', $content );
		self::assertStringContainsString( 'class BarTest extends TestCase', $content );
		self::assertStringContainsString( 'public function testDoIt(): void', $content );
		self::assertStringContainsString( 'public function testDoItException1(): void', $content );
		self::assertStringContainsString( "expectException( 'InvalidArgumentException' )", $content );
		self::assertStringContainsString( "expectException( 'RuntimeException' )", $content );
		self::assertStringContainsString( 'public function testCreate(): void', $content );
		self::assertStringNotContainsString( 'testHidden', $content );

		$output	= [];
		exec( escapeshellarg( PHP_BINARY ).' -l '.escapeshellarg( $targetFile ).' 2>&1', $output, $code );
		self::assertSame( 0, $code, implode( PHP_EOL, $output ) );
	}

	public function testCreateForFileWithAbsoluteAndUnderscoredName(): void
	{
		$this->getCreator()->createForFile( '\Demo\Lib\Foo\Bar' );
		self::assertFileExists( $this->path.'test/Foo/BarTest.php' );

		$this->getCreator()->createForFile( 'Foo_Bar', TRUE );
		self::assertFileExists( $this->path.'test/Foo/BarTest.php' );
	}

	public function testCreateForFileWithBaseClass(): void
	{
		$creator	= $this->getCreator()->setBaseClass( 'CeusMedia\CommonTest\BaseCase' );
		$creator->createForFile( 'Foo\Bar' );
		$content	= (string) file_get_contents( $this->path.'test/Foo/BarTest.php' );
		self::assertStringContainsString( 'use CeusMedia\CommonTest\BaseCase;', $content );
		self::assertStringContainsString( 'class BarTest extends BaseCase', $content );
	}

	public function testCreateForFileExistingException(): void
	{
		$this->getCreator()->createForFile( 'Foo\Bar' );
		$this->expectException( RuntimeException::class );
		$this->getCreator()->createForFile( 'Foo\Bar' );
	}

	public function testCreateForFileMissingException(): void
	{
		$this->expectException( RuntimeException::class );
		$this->getCreator()->createForFile( 'Foo\Missing' );
	}

	public function testCreateForFolder(): void
	{
		$creator	= $this->getCreator();
		self::assertSame( 1, $creator->createForFolder( 'Foo' ) );
		self::assertFileExists( $this->path.'test/Foo/BarTest.php' );
		self::assertFileDoesNotExist( $this->path.'test/Foo/ContractTest.php' );

		self::assertSame( 0, $creator->createForFolder( 'Foo' ) );
		self::assertSame( 1, $creator->createForFolder( 'Foo', TRUE ) );
		self::assertSame( 0, $creator->createForFolder( 'Missing' ) );
	}

	protected function getCreator(): Creator
	{
		return new Creator( 'Demo\Lib', 'Demo\LibTest', $this->path.'src', $this->path.'test' );
	}
}
