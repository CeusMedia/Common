<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handles backup and restore of single files.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use OutOfBoundsException;
use OutOfRangeException;
use RuntimeException;

/**
 *	Handles backup and restore of single files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Backup
{
	protected $filePath;
	protected $preserveTimestamp;
	protected $keepOnlyOne;

	/**
	 *	Constructor.
	 *	@param		string		$filePath
	 *	@param		boolean		$preserveTimestamp
	 *	@param		boolean		$keepOnlyOne
	 *	@throws		RuntimeException
	 */
	public function __construct( string $filePath, bool $preserveTimestamp = TRUE, bool $keepOnlyOne = FALSE )
	{
		if( !file_exists( $filePath ) )
			//  @todo: better an IO exception
			throw new RuntimeException( 'File "'.$filePath.'" is not existing' );
		$this->filePath	= $filePath;
		$this->preserveTimestamp	= $preserveTimestamp;
		$this->keepOnlyOne			= $keepOnlyOne;
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@return		string|FALSE
	 */
	public function getContent( int $version )
	{
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		return file_get_contents( $filePath );
	}

	/**
	 *	...
	 *	@return		integer|NULL
	 */
	public function getVersion(): ?int
	{
		$i	= 1;
		$v	= NULL;
		while( file_exists( $this->filePath.'.~'.$i.'~' ) ){
			$v	= $i++;
		}
		if( !$v && file_exists( $this->filePath.'~' ) ){
			return 0;
		}
		return $v;
	}

	/**
	 *	...
	 *	@return		int[]
	 */
	public function getVersions(): array
	{
		$list		= [];
		$version	= $this->getVersion();
		while( is_int( $version ) && $version >= 0 ){
			$list[$version]	= filemtime( $this->getVersionFilename( $version ) );
			$version		-= 1;
		}
		return array_reverse( $list );
	}

	/**
	 *	...
	 *	@param		string		$targetPath
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	public function move( string $targetPath ): void
	{
		$files		= [];
		$version	= $this->getVersion();
		for( $i=0; $i<=$version; $i++ )
			$files[]	= $this->getVersionFilename( $i );

		if( !@rename( $this->filePath, $targetPath ) )
			throw new RuntimeException( 'Moving original file failed.' );

		$this->filePath	= $targetPath;
		for( $i=0; $i<=$version; $i++ ){
			if( !@rename( $files[$i], $this->getVersionFilename( $i ) ) ){
				throw new RuntimeException( 'Moving '.( $i + 1 ).'. backup file failed.' );
			}
		}
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@return		void
	 *	@throws		OutOfRangeException
	 *	@throws		RuntimeException
	 */
	public function remove( int $version = 0 ): void
	{
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		if( !file_exists( $filePath ) )
			throw new OutOfRangeException( 'No backup version '.$version.' found for file "'.$this->filePath.'"' );

		if( !@unlink( $filePath ) )
			throw new RuntimeException( 'Removal of backup file '.$filePath.' failed' );

		$nextFile	= $this->getVersionFilename( $version += 1 );
		while( file_exists( $nextFile ) ){
			if( !rename( $nextFile, $filePath ) )
				throw new RuntimeException( 'Compression of backup versions failed at version '.$version );

			$filePath	= $nextFile;
			$nextFile	= $this->getVersionFilename( $version += 1 );
		}
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@param		boolean		$removeBackup
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	public function restore( int $version = -1, bool $removeBackup = FALSE ): void
	{
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		if( !file_exists( $filePath) )
			throw new RuntimeException( 'No backup version '.$version.' found for file "'.$this->filePath.'"' );

		if( !@copy( $filePath, $this->filePath ) )
			throw new RuntimeException( 'Restoring backup to file '.$this->filePath.' failed' );

		if( $this->preserveTimestamp ){
			clearstatcache();
			touch( $this->filePath, filemtime( $filePath ) );
		}
		if( $removeBackup )
			$this->remove( $version );
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@param		string		$content
	 *	@return		integer
	 */
	public function setContent( int $version, string $content ): int
	{
		$version	= $this->sanitizeVersion( $version );
		return Writer::save( $this->getVersionFilename( $version ), $content );
	}

	/**
	 *	...
	 *	@param		bool		$removeOriginal
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	public function store( bool $removeOriginal = FALSE )
	{
		//  get current backup version
		$version	= $this->getVersion();
		//  increase version if any backups exist
		$version	= !$this->keepOnlyOne ? ( $version + 1 ) : 0;
		$filePath	= $this->getVersionFilename( $version );
		if( !@copy( $this->filePath, $filePath ) )
			throw new RuntimeException( 'Storing backup into file '.$filePath.' failed' );

		if( $this->preserveTimestamp ){
			clearstatcache();
			touch( realpath( $filePath ), filemtime( $this->filePath ) );
		}
		if( $removeOriginal )
			if( !@unlink( $this->filePath ) )
				throw new RuntimeException( 'Removal of source file '.$this->filePath.' failed' );
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@return		string
	 */
	protected function getVersionFilename( int $version ): string
	{
		if( $version <= 0 )
			return $this->filePath.'~';
		return $this->filePath.'.~'.( $version ).'~';
	}

	/**
	 *	...
	 *	@param		integer		$version
	 *	@return		integer
	 *	@throws		OutOfRangeException
	 *	@throws		OutOfBoundsException
	 */
	protected function sanitizeVersion( int $version ): int
	{
		if( $version < -1 )
			throw new OutOfBoundsException( 'Version must be a positive zero-based index or -1 for last' );
		if( $version > $this->getVersion() )
			throw new OutOfRangeException( 'Version '.$version.' not existing' );
		if( $version === -1 )
			$version	= $this->getVersion();
		return $version;
	}
}
