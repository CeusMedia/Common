<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS;

use CeusMedia\Common\Alg\UnitFormater;
use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Exception\MissingExtension as MissingExtensionException;
use CeusMedia\Common\FS\File\Permissions;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class File extends AbstractNode
{
	/**
	 *	@param		string		$pathName
	 *	@param		boolean		$create
	 *	@param		integer		$mode			File permissions as octal, default: 0777
	 *	@param		bool		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@throws		IoException
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
	 *	@throws		IoException					if file is a directory, a link or already existing
	 *	@throws		IoException					if file creation failed
	 */
	public function create(int $mode = 0777, bool $strict = TRUE ): bool
	{
		if( $this->exists() ){
			if( $strict ){
				if( is_dir( $this->pathName ) )
					throw new IoException( 'A folder with this name is already existing', 0, $this->pathName );
				if( is_link( $this->pathName ) )
					throw new IoException( 'A link with this name is already existing', 0, $this->pathName );
				if( is_file( $this->pathName ) )
					throw new IoException( 'File is already existing', 0, $this->pathName );
			}
			return FALSE;
		}
		if( !touch( $this->pathName ) ){
			if( $strict )
				throw new IoException( 'File creation failed', 0, $this->pathName );
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *	Indicates whether a file is existing at the existing path name.
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: no
	 *	@return		boolean
	 *	@throws		FileNotExistingException	if strict and file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function exists( bool $strict = FALSE ): bool
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		return $this->check( FALSE, FALSE, FALSE, $strict );
	}

	/**
	 *	Returns Basename of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename(): string
	{
		return basename( $this->pathName );
	}

	/**
	 *	...
	 *	@param		bool		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		string|NULL
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getContent( bool $strict = TRUE ): string|NULL
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		if( !$this->check( TRUE, FALSE, FALSE, $strict ) )
			return NULL;
		$content	= file_get_contents( $this->pathName );
		return FALSE !== $content ? $content : NULL;
	}

	/**
	 *	Returns the encoding (character set) of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		MissingExtensionException	if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getEncoding(): string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check( TRUE );
		if( function_exists( 'finfo_open' ) ){
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_ENCODING, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->pathName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( !str_starts_with( PHP_OS, "WIN" ) ){
			$command	= 'file -b --mime-encoding '.escapeshellarg( $this->pathName );
			return trim( exec( $command ) );
		}
		throw new MissingExtensionException( 'PHP extension Fileinfo is missing' );
	}

	/**
	 *	Returns Extension of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getExtension(): string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check();
		$info = pathinfo( $this->pathName );
		return $info['extension'];
	}

	/**
	 *	Returns group name or ID of file.
	 *
	 *	@access		public
	 *	@param		boolean		$resolveName		Try to resolve username instead of returning ID
	 *	@return		string|int
	 *	@throws		RuntimeException			if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getGroup( bool $resolveName = TRUE ): int|string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check();
		$groupId	= filegroup( $this->pathName );
		if( FALSE === $groupId )
			throw new RuntimeException( 'Could not get group of file "'.$this->pathName.'"' );
		if( $resolveName ){
			/** @noinspection PhpComposerExtensionStubsInspection */
			$group	= posix_getgrgid( $groupId );
			if( is_array( $group ) )
				return $group['name'];
		}
		return $groupId;
	}

	/**
	 *	Returns the MIME type of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		MissingExtensionException	if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if strict and file is not existing, not a file or not readable
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getMimeType(): string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check( TRUE );
		if( function_exists( 'finfo_open' ) ){
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_TYPE, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->pathName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( !str_starts_with( PHP_OS, "WIN" ) ){
			$command	= 'file -b --mime-type '.escapeshellarg( $this->pathName );
			return trim( exec( $command ) );
		}
		else if( function_exists( 'mime_content_type' ) ){
			if( $mimeType = mime_content_type( $this->pathName ) )
				return $mimeType;
		}
		throw new MissingExtensionException( 'PHP extension Fileinfo is missing' );
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
	 *	Returns owner name or ID of file.
	 *
	 *	@access		public
	 *	@param		boolean		$resolveName	...
	 *	@return		string|int
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getOwner( bool $resolveName = TRUE ): int|string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check();
		$userId	= fileowner( $this->pathName );
		if( FALSE === $userId )
			throw new RuntimeException( 'Could not get owner of file "'.$this->pathName.'"' );
		if( $resolveName ){
			$user = posix_getpwuid( $userId );
			if( is_array( $user ) )
				return $user['name'];
		}
		return $userId;
	}

	/**
	 *	Returns OS permissions of current file as octal value.
	 *	@access		public
	 *	@return		Permissions		File permissions object
	 *	@throws		FileNotExistingException	if file is not existing or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getPermissions(): Permissions
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check();
		return new Permissions( $this->pathName );
	}

	/**
	 *	@access		public
	 *	@param		integer|NULL	$precision		Precision of rounded Size (only if unit is set)
	 *	@return		integer|string
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function getSize( int $precision = NULL ): int|string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$this->check();
		$size	= filesize( $this->pathName );
		if( $precision )
			$size	= UnitFormater::formatBytes( $size, $precision );
		return $size;
	}


	/**
	 *	...
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		integer|NULL
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 */
	public function getTime( bool $strict = TRUE ): ?int
	{
		if( !$this->exists( $strict ) )
			return NULL;
		return filemtime( $this->pathName );
	}

	/**
	 *	Indicates whether a given user is owner of current file.
	 *	On Windows this method always returns TRUE.
	 *	@access		public
	 *	@param		string|NULL		$user		Name of user to check ownership for, current user by default
	 *	@return		boolean
	 *	@noinspection	PhpUnused
	 */
	public function isOwner( ?string $user = NULL ): bool
	{
		$user	??= get_current_user();
		if( !function_exists( 'posix_getpwuid' ) )
			return TRUE;
		$uid	= fileowner( $this->getPathName() );
		if( !$uid )
			return TRUE;
		$owner	= posix_getpwuid( $uid );
		if( !$owner )
			return TRUE;
//		print_m( $owner );
		return $user == $owner['name'];
	}

	/**
	 *	Indicates whether a file is readable.
	 *	@access		public
	 *	@return		bool		$strict			Flag: throw exceptions
	 *	@return		bool
	 *	@throws		FileNotExistingException	if strict and file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public function isReadable( bool $strict = FALSE ): bool
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		return $this->check( TRUE, FALSE, FALSE, $strict );
	}

	/**
	 *	Write content into file
	 *	@param		string		$content		Content to write into file
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: yes
	 *	@return		boolean|integer				Number of written bytes or FALSE on fail
	 *	@throws		IoException					if strict and file is not writable
	 *	@throws		IoException					if strict and fallback file creation failed
	 *	@throws		IoException					if number of written bytes does not match content length
	 */
	public function setContent( string $content, bool $strict = TRUE ): bool|int
	{
		if( !$this->exists() && !$this->create( 0777, $strict ) )
			return FALSE;
		if( !$this->check( FALSE, TRUE, FALSE, $strict ) )
			return FALSE;
		$bytes	= file_put_contents( $this->pathName, $content );
		if( $bytes != strlen( $content ) )
			throw new IoException( 'Number of written bytes does not match content length', 0, $this->pathName );
		return $bytes;
	}

	/**
	 *	Checks if set filename is: existing, readable, writable
	 *	@access		protected
	 *	@param		boolean		$isReadable		Flag: enable readable check, default: off
	 *	@param		boolean		$isWritable		Flag: enable writable check, default: off
	 *	@param		boolean		$isOwner		Flag: enable owner check, default: off
	 *	@param		boolean		$strict			Flag: throw exception on fail, default: on
	 *	@return		bool
	 *	@throws		FileNotExistingException	if strict & file is not existing
	 *	@throws		FileNotExistingException	if strict & given path is not a file
	 *	@throws		IoException					if strict & isReadable & file is not readable
	 *	@throws		IoException					if strict & isWritable & file is not writable
	 *	@throws		IoException					if strict & isOwner & file is not owned
	 */
	protected function check( bool $isReadable = FALSE, bool $isWritable = FALSE, bool $isOwner = FALSE, bool $strict = TRUE ): bool
	{
		if( !file_exists( $this->getPathName() ) ){
			if( $strict )
				throw new FileNotExistingException( 'File is not existing', 0, $this->getPathName() );
			return FALSE;
		}
		if( !is_file( $this->getPathName() ) ){
			if( $strict )
				throw new FileNotExistingException( 'Not a file', 0, $this->getPathName() );
			return FALSE;
		}
		if( $isReadable && !is_readable( $this->getPathName() ) ){
			if( $strict )
				throw new IoException( 'File is not readable', 0, $this->getPathName() );
			return FALSE;
		}
		if( $isWritable && !is_writable( $this->getPathName() ) ){
			if( $strict )
				throw new IoException( 'File is not writable', 0, $this->getPathName() );
			return FALSE;
		}
		if( $isOwner && !$this->isOwner() ){
			if( $strict )
				throw new IoException( 'File is not owned', 0, $this->getPathName() );
			return FALSE;
		}
		return TRUE;
	}
}
