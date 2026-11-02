<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS;

/**
 *	Creates a temporary folder per test and removes it afterwards.
 *	@package		Tests.FS
 */
trait TempFolderTrait
{
	protected string $tempPath	= '';

	protected function createTempFolder(): string
	{
		$this->tempPath	= sys_get_temp_dir().'/CeusMediaCommonTest'.getmypid().'_'.bin2hex( random_bytes( 4 ) ).'/';
		mkdir( $this->tempPath, 0777, TRUE );
		return $this->tempPath;
	}

	protected function removeTempFolder( ?string $path = NULL ): void
	{
		$path	??= $this->tempPath;
		if( '' === $path || !is_dir( $path ) )
			return;
		foreach( array_diff( scandir( $path ) ?: [], ['.', '..'] ) as $entry ){
			$entryPath	= rtrim( $path, '/' ).'/'.$entry;
			is_dir( $entryPath ) && !is_link( $entryPath ) ? $this->removeTempFolder( $entryPath ) : @unlink( $entryPath );
		}
		@rmdir( $path );
	}
}
