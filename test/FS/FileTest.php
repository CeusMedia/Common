<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\File.
 *	@package		Tests.FS
 */
class FileTest extends BaseCase
{
	use TempFolderTrait;

	public function setUp(): void
	{
		$this->createTempFolder();
	}

	public function tearDown(): void
	{
		$this->removeTempFolder();
	}

	public function testCreateAndContent(): void
	{
		$file	= new File( $this->tempPath.'test.txt', TRUE );
		self::assertTrue( $file->exists() );
		self::assertSame( '', $file->getContent() );
		self::assertSame( 1, $file->setContent( '0' ) );
		self::assertSame( '0', $file->getContent() );
		self::assertSame( 'test', $file->getName( FALSE ) );
		self::assertSame( 'txt', $file->getExtension() );
		self::assertSame( 1, $file->getSize() );
		self::assertTrue( $file->isOwner() );
	}

	public function testCreateExistingException(): void
	{
		$file	= new File( $this->tempPath.'test.txt', TRUE );
		self::assertFalse( $file->create( 0777, FALSE ) );
		$this->expectException( IoException::class );
		$file->create();
	}

	public function testIsOwnerOfRootFile(): void
	{
		if( !function_exists( 'posix_getuid' ) || 0 === posix_getuid() || 0 !== @fileowner( '/etc/passwd' ) )
			$this->markTestSkipped( 'Needs a root owned file and a non-root user' );
		self::assertFalse( ( new File( '/etc/passwd' ) )->isOwner() );
	}
}
