<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\BackupCleaner;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;

/**
 *	TestUnit of FS\File\BackupCleaner.
 *	@package		Tests.FS.File
 */
class BackupCleanerTest extends BaseCase
{
	use TempFolderTrait;

	public function setUp(): void
	{
		$this->createTempFolder();
		foreach( ['2024-01-05', '2024-01-20', '2024-01-31', '2024-02-01', '2024-02-10'] as $date )
			touch( $this->tempPath.'db.'.$date.'.sql.gz' );
		touch( $this->tempPath.'db.latest.sql.gz' );
		touch( $this->tempPath.'dbX2024-01-01.sql.gz' );
	}

	public function tearDown(): void
	{
		$this->removeTempFolder();
	}

	public function testIndex(): void
	{
		$dates	= ( new BackupCleaner( $this->tempPath, 'db.', 'sql.gz' ) )->index();
		sort( $dates );
		self::assertSame( ['2024-01-05', '2024-01-20', '2024-01-31', '2024-02-01', '2024-02-10'], $dates );
	}

	public function testKeepLastOfMonth(): void
	{
		$cleaner	= new BackupCleaner( $this->tempPath, 'db.', '.sql.gz' );
		$cleaner->keepLastOfMonth( [], FALSE, TRUE );
		self::assertCount( 7, glob( $this->tempPath.'*' ) ?: [] );

		$cleaner->keepLastOfMonth();
		$files	= array_map( 'basename', glob( $this->tempPath.'*' ) ?: [] );
		sort( $files );
		self::assertSame( ['db.2024-01-31.sql.gz', 'db.2024-02-10.sql.gz', 'db.latest.sql.gz', 'dbX2024-01-01.sql.gz'], $files );
	}
}
