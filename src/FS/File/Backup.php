<?php
/**
 *	Handles backup and restore of single files.
 *
 *	Copyright (c) 2015-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Handles backup and restore of single files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 *	@version		$Id$
 */
class FS_File_Backup{

	protected $filePath;
	protected $preserveTimestamp;
	protected $keepOnlyOne;

	public function __construct( $filePath, $preserveTimestamp = TRUE, $keepOnlyOne = FALSE ){
		if( !file_exists( $filePath ) )
			throw new RuntimeException( 'File "'.$filePath.'" is not existing' );				//  @todo: better an IO exception
		$this->filePath	= $filePath;
		$this->preserveTimestamp	= $preserveTimestamp;
		$this->keepOnlyOne			= $keepOnlyOne;
	}

	public function getContent( $version ){
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		return file_get_contents( $filePath );
	}

	public function getVersion(){
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

	protected function getVersionFilename( $version ){
		if( (int) $version <= 0 ){
			return $this->filePath.'~';
		}
		return $this->filePath.'.~'.( $version ).'~';
	}

	public function getVersions(){
		$list		= array();
		$version	= $this->getVersion();
		while( is_int( $version ) && $version >= 0 ){
			$list[$version]	= filemtime( $this->getVersionFilename( $version ) );
			$version		-= 1;
		}
		return array_reverse( $list );
	}

	public function move( $targetPath ){
		$files		= array();
		$version	= $this->getVersion();
		for( $i=0; $i<=$version; $i++ ){
			$files[]	= $this->getVersionFilename( $i );
		}
		if( !@rename( $this->filePath, $targetPath ) ){
			throw new RuntimeException( 'Moving original file failed.' );
		}
		$this->filePath	= $targetPath;
		for( $i=0; $i<=$version; $i++ ){
			if( !@rename( $files[$i], $this->getVersionFilename( $i ) ) ){
				throw new RuntimeException( 'Moving '.( $i + 1 ).'. backup file failed.' );
			}
		}
	}

	public function remove( $version = 0 ){
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		if( !file_exists( $filePath ) ){
			throw new OutOfRangeException( 'No backup version '.$version.' found for file "'.$this->filePath.'"' );
		}
		if( !@unlink( $filePath ) ){
			throw new RuntimeException( 'Removal of backup file '.$filePath.' failed' );
		}

		$nextFile	= $this->getVersionFilename( $version += 1 );
		while( file_exists( $nextFile ) ){
			if( !rename( $nextFile, $filePath ) ){
				throw new RuntimeException( 'Compression of backup versions failed at version '.$version );
			}
			$filePath	= $nextFile;
			$nextFile	= $this->getVersionFilename( $version += 1 );
		}
	}

	public function restore( $version = -1, $removeBackup = FALSE ){
		$version	= $this->sanitizeVersion( $version );
		$filePath	= $this->getVersionFilename( $version );
		if( !file_exists( $filePath) ){
			throw new RuntimeException( 'No backup version '.$version.' found for file "'.$this->filePath.'"' );
		}
		if( !@copy( $filePath, $this->filePath ) ){
			throw new RuntimeException( 'Restoring backup to file '.$this->filePath.' failed' );
		}
		if( $this->preserveTimestamp ){
			clearstatcache();
			touch( $this->filePath, filemtime( $filePath ) );
		}
		if( $removeBackup ){
			!$this->remove( $version );
		}
	}

	protected function sanitizeVersion( $version ){
		if( !is_int( $version ) ){
			throw new InvalidArgumentException( 'Version must be integer' );
		}
		else if( $version < -1 ){
			throw new OutOfBoundsException( 'Version must be a positive zero-based index or -1 for last' );
		}
		else if( $version === -1 ){
			$version	= $this->getVersion();
		}
		else if( $version > $this->getVersion() ){
			throw new OutOfRangeException( 'Version '.$version.' not existing' );
		}
		return $version;
	}

	public function setContent( $version, $content ){
		$version	= $this->sanitizeVersion( $version );
		return FS_File_Writer::save( $this->getVersionFilename( $version ), $content );
	}

	public function store( $removeOriginal = FALSE){
		$version	= $this->getVersion( $this->filePath );											//  get current backup version
		$version	= !$this->keepOnlyOne ? ( is_int( $version ) ? $version + 1 : 0 ) : 0;			//  increase version if any backups exist
		$filePath	= $this->getVersionFilename( $version );
		if( !@copy( $this->filePath, $filePath ) ){
			throw new RuntimeException( 'Storing backup into file '.$filePath.' failed' );
		}
		if( $this->preserveTimestamp ){
			clearstatcache();
			touch( realpath( $filePath ), filemtime( $this->filePath ) );
		}
		if( $removeOriginal ){
			if( !@unlink( $this->filePath ) ){
				throw new RuntimeException( 'Removal of source file '.$this->filePath.' failed' );
			}
		}
	}
}
?>
