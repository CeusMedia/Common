<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\Arc;

use CeusMedia\Common\FS\File\Arc\Zip;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;
use RuntimeException;
use ZipArchive;

/**
 *	TestUnit of FS\File\Arc\Zip.
 *	@package		Tests.FS.File.Arc
 */
class ZipTest extends BaseCase
{
	use TempFolderTrait;

	public function setUp(): void
	{
		if( !Zip::hasSupport() )
			$this->markTestSkipped( 'Missing zip support' );
		$this->createTempFolder();
		file_put_contents( $this->tempPath.'a.txt', 'A' );
		file_put_contents( $this->tempPath.'b.txt', 'B' );
	}

	public function tearDown(): void
	{
		$this->removeTempFolder();
	}

	public function testAddSaveAndContinue(): void
	{
		$zip	= new Zip( $this->tempPath.'test.zip' );
		self::assertTrue( $zip->addFile( $this->tempPath.'a.txt', 'a.txt' ) );
		self::assertTrue( $zip->save() );
		self::assertSame( ['a.txt'], $this->listEntries( $this->tempPath.'test.zip' ) );

		//  archive stays usable after saving
		self::assertTrue( $zip->addFile( $this->tempPath.'b.txt', 'b.txt' ) );
		self::assertTrue( $zip->save() );
		self::assertSame( ['a.txt', 'b.txt'], $this->listEntries( $this->tempPath.'test.zip' ) );
		self::assertCount( 2, $zip->index() );
	}

	public function testSaveAs(): void
	{
		$zip	= new Zip( $this->tempPath.'test.zip' );
		$zip->addFile( $this->tempPath.'a.txt', 'a.txt' );
		$zip->save();
		$zip->addFile( $this->tempPath.'b.txt', 'b.txt' );
		self::assertTrue( $zip->save( $this->tempPath.'copy.zip' ) );
		self::assertSame( ['a.txt', 'b.txt'], $this->listEntries( $this->tempPath.'copy.zip' ) );
		self::assertSame( ['a.txt'], $this->listEntries( $this->tempPath.'test.zip' ) );
	}

	public function testOpenInvalidException(): void
	{
		file_put_contents( $this->tempPath.'invalid.zip', 'no zip' );
		$this->expectException( RuntimeException::class );
		new Zip( $this->tempPath.'invalid.zip' );
	}

	protected function listEntries( string $fileName ): array
	{
		$archive	= new ZipArchive();
		$archive->open( $fileName );
		$list	= [];
		for( $i = 0; $i < $archive->numFiles; $i++ )
			$list[]	= $archive->getNameIndex( $i );
		$archive->close();
		sort( $list );
		return $list;
	}
}
