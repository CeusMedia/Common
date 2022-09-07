<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\FS;

use CeusMedia\Common\Exception\IO as IOException;
use RuntimeException;

class File extends AbstractNode
{
	protected $pathName;

	/**
	 *	@param		string		$pathName
	 *	@param		boolean		$create
	 *	@param		integer		$mode			File permissions as octal, default: 0777
	 *	@param		bool		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@throws		IOException
	 */
	public function __construct( string $pathName, bool $create = FALSE, int $mode = 0777, bool $strict = TRUE )
	{
		parent::__construct( $pathName );
		if( $create && !$this->exists() )
			$this->create( $mode, $strict );
	}

	/**
	 *	Tries to create a file with given path name.
	 *	@param		integer		$mode			File permissions as octal, default: 0777
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		boolean
	 *	@throws		IOException
	 */
	public function create(int $mode = 0777, bool $strict = TRUE ): bool
	{
		if( $this->exists() ){
			if( $strict ){
				if( is_dir( $this->pathName ) )
					throw new IOException( 'A folder with this name is already existing', 0, $this->pathName );
				if( is_link( $this->pathName ) )
					throw new IOException( 'A link with this name is already existing', 0, $this->pathName );
				if( is_file( $this->pathName ) )
					throw new IOException( 'File is already existing', 0, $this->pathName );
			}
			return FALSE;
		}
		if( !touch( $this->pathName, $mode, TRUE ) ){
			if( $strict )
				throw new IOException( 'File creation failed', 0, $this->pathName );
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	Indicates whether a file is existing at the existing path name.
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		boolean
	 *	@throws		IOException
	 */
	public function exists( bool $strict = FALSE ): bool
	{
		if( !file_exists( $this->pathName ) ){
			if( $strict )
				throw new IOException( 'File is not existing', 0, $this->pathName );
			return FALSE;
		}
		if( !is_file( $this->pathName ) ){
			if( $strict )
				throw new IOException( 'Not a file', 0, $this->pathName );
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	...
	 *	@param		bool		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		false|string|null
	 *	@throws		IOException
	 */
	public function getContent( bool $strict = TRUE )
	{
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
	public function getMimeType(): string
	{
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

	/**
	 *	...
	 *	@param		bool		$withExtension		Flag: return file name with extension, default: yes
	 *	@return		string
	 */
	public function getName( bool $withExtension = TRUE ): string
	{
		if( $withExtension )
			return pathinfo( $this->pathName, PATHINFO_BASENAME );
		return pathinfo( $this->pathName, PATHINFO_FILENAME );
	}

	/**
	 *	...
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		false|integer|NULL
	 *	@throws		IOException
	 */
	public function getSize( bool $strict = TRUE )
	{
		if( !$this->exists( $strict ) )
			return NULL;
		return filesize( $this->pathName );
	}

	/**
	 *	...
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		integer|NULL
	 *	@throws		IOException
	 */
	public function getTime( bool $strict = TRUE ): ?int
	{
		if( !$this->exists( $strict ) )
			return NULL;
		return filemtime( $this->pathName );
	}

	/**
	 *	Write content into file
	 *	@param		string		$content		Content to write into file
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		boolean
	 *	@throws		IOException
	 */
	public function setContent( string $content, bool $strict = TRUE ): bool
	{
		if( !$this->exists( $strict ) || !$this->create( $strict ) )
			return FALSE;
		file_put_contents( $this->pathName, $content );
		return TRUE;
	}
}
