<?php
class FS_File extends FS_AbstractNode{

	protected $pathName;

	public function __construct( $pathName, $create = FALSE, $mode = 0777, $strict = TRUE ){
		$this->setPathName( $pathName );
		if( $create && !$this->exists() )
			$this->create( $mode, $strict );
	}

	public function create( $mode = 0777, $strict = TRUE ){
		if( $this->exists( FALSE ) ){
			if( $strict ){
				if( is_dir( $this->pathName ) )
					throw new Exception_IO( 'A folder with this name is already existing', 0, $this->pathName );
				if( is_link( $this->pathName ) )
					throw new Exception_IO( 'A link with this name is already existing', 0, $this->pathName );
				if( is_file( $this->pathName ) )
					throw new Exception_IO( 'File is already existing', 0, $this->pathName );
			}
			return FALSE;
		}
		if( !touch( $this->pathName, 0777, TRUE ) ){
			if( $strict )
				throw new Exception_IO( 'File creation failed', 0, $this->pathName );
			return FALSE;
		}
		return TRUE;
	}

	public function exists( $strict = FALSE ){
		if( !file_exists( $this->pathName ) ){
			if( $strict )
				throw new Exception_IO( 'Folder is not existing', 0, $targetPath );
			return FALSE;
		}
		if( !is_file( $this->pathName ) ){
			if( $strict )
				throw new Exception_IO( 'Not a file', 0, $targetPath );
			return FALSE;
		}
		return TRUE;
	}

	public function getContent( $strict = TRUE ){
		if( !$this->exists( $strict ) )
			return NULL;
		return file_get_contents( $this->pathName );
	}

	/**
	 *	Returns the MIME type of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException	if Fileinfo is not installed
	 */
	public function getMimeType(){
		if( function_exists( 'finfo_open' ) ){
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_TYPE, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->pathName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( substr( PHP_OS, 0, 3 ) != "WIN" ){
			$command	= 'file -b --mime-type '.escapeshellarg( $this->pathName );
			return trim( exec( $command ) );
		}
		else if( function_exists( 'mime_content_type' ) ){
			if( $mimeType = mime_content_type( $this->pathName ) )
				return $mimeType;
		}
		throw new RuntimeException( 'PHP extension Fileinfo is missing' );
	}

	public function getName( $withExtension = TRUE, $strict = TRUE ){
		if( $withExtension )
			return pathinfo( $this->pathName, PATHINFO_BASENAME );
		return pathinfo( $this->pathName, PATHINFO_FILENAME );
	}

	public function getSize( $strict = TRUE ){
		if( !$this->exists( $strict ) )
			return NULL;
		return filesize( $this->pathName );
	}

	public function getTime( $strict = TRUE ){
		if( !$this->exists( $strict ) )
			return NULL;
		return filemtime( $this->pathName );
	}

	public function setContent( $content, $strict = TRUE ){
		if( !$this->exists() ){
			if( !$this->create( $strict ) ){
				return FALSE;
			}
		}
		file_put_contents( $this->pathName, $content );
	}

	public function setPathName( $pathName ){
		$pathName	= trim( $pathName );
		if( $pathName !== '/' )
			$pathName	= rtrim( $pathName, '/' );
		$this->pathName	= $pathName;
	}
}
?>
