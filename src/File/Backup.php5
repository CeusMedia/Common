<?php
class File_Backup{

	protected $filePath;
	protected $preserveTimestamp;

	public function __construct( $filePath, $preserveTimestamp = TRUE ){
		if( !file_exists( $filePath ) )
			throw new RuntimeException( 'File "'.$filePath.'" is not existing' );				//  @todo: better an IO exception
		$this->filePath	= $filePath;
		$this->preserveTimestamp	= $preserveTimestamp;
	}

	public function getContent( $version ){
		if( !is_int( $version ) ){
			throw new InvalidArgumentException( 'Version must be integer' );
		}
		if( $version < -1 ){
			throw new OutOfBoundsException( 'Version must be a positive zero-based index or -1 for last' );
		}
		if( $version === -1 ){
			$version	= $this->getVersion();
		}
		$maxVersion	= $this->getVersion();
		if( $version > $maxVersion ){
			throw new OutOfBoundsException( 'Version '.$version.' not existing' );
		}
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

	public function getVersions(){
		$list		= array();
		$version	= $this->getVersion();
		while( is_int( $version ) && $version >= 0 ){
			$list[$version]	= filemtime( $this->getVersionFilename( $version ) );
			$version		-= 1;
		}
		return array_reverse( $list );
	}

/*	protected function getVersionFromFilename( $fileName ){
		if( preg_match( "/^.+\.~([0-9]+)~$/", $fileName ) ){
			return preg_replace( "/^.+\.~([0-9]+)~$/", "\\1", $fileName );
		}
		else if( preg_match( "/[a-z0-9]~$/", $fileName ) ){
			return 0;
		}
		return NULL;
	}
*/
	protected function getVersionFilename( $version ){
		if( (int) $version <= 0 ){
			return $this->filePath.'~';
		}
		return $this->filePath.'.~'.( $version ).'~';
	}

	public function remove( $version = 0 ){
		if( !is_int( $version ) ){
			throw new InvalidArgumentException( 'Version must be integer' );
		}
		if( $version < -1 ){
			throw new OutOfBoundsException( 'Version must be a positive zero-based index or -1 for last' );
		}
		if( $version === -1 ){
			$version	= $this->getVersion();
		}

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
		if( !is_int( $version ) ){
			throw new InvalidArgumentException( 'Version must be integer' );
		}
		if( $version < -1 ){
			throw new OutOfBoundsException( 'Version must be a positive zero-based index or -1 for last' );
		}
		if( $version === -1 ){
			$version	= $this->getVersion();
		}

		$filePath	= $this->getVersionFilename( $version );
		if( !file_exists( $filePath) ){
			throw new RuntimeException( 'No backup version '.$version.' found for file "'.$this->filePath.'"' );
		}
		if( !@copy( $filePath, $this->filePath ) ){
			throw new RuntimeException( 'Restoring backup to file '.$this->filePath.' failed' );
		}
		if( $this->preserveTimestamp ){
			touch( $this->filePath, filemtime( $filePath ) );
		}
		if( $removeBackup ){
			!$this->remove( $version );
		}
	}

	public function store( $removeOriginal = FALSE){
		$version	= $this->getVersion( $this->filePath );
		$version	= is_int( $version ) ? $version + 1 : NULL;
		$filePath	= $this->getVersionFilename( $version );
		if( !@copy( $this->filePath, $filePath ) ){
			throw new RuntimeException( 'Storing backup into file '.$filePath.' failed' );
		}
		if( $removeOriginal ){
			if( !@unlink( $this->filePath ) ){
				throw new RuntimeException( 'Removal of source file '.$this->filePath.' failed' );
			}
		}
	}
}
?>
