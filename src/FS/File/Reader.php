<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Basic File Reader.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\Alg\UnitFormater;
use RuntimeException;

/**
 *	Basic File Reader.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**	@var		string		$fileName		File Name or URI of File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name or URI of File
	 *	@return		void
	 */
	public function __construct( string $fileName, bool $check = FALSE )
	{
		$this->fileName = $fileName;
		if( $check && !$this->exists() )
			throw new RuntimeException( 'File "'.addslashes( $fileName ).'" is not existing' );
		if( $check && !$this->isReadable() )
			throw new RuntimeException( 'File "'.$fileName.'" is not readable' );
	}

	/**
	 *	Indicates whether current File is equal to another File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to compare with
	 *	@return		bool
	 */
	public function equals( string $fileName ): bool
	{
		$toCompare	= Reader::load( $fileName );
		$thisFile	= Reader::load( $this->fileName );
		return( $thisFile == $toCompare );
	}

	/**
	 *	Indicates whether current URI is an existing File.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists(): bool
	{
		$exists	= file_exists( $this->fileName );
		$isFile	= is_file( $this->fileName );
		return $exists && $isFile;
	}

	/**
	 *	Returns Basename of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename(): string
	{
		return basename( $this->fileName );
	}

	/**
	 *	Returns the file date as timestamp.
	 *	@access		public
	 *	@return		int
	 */
	public function getDate(): int
	{
		return filemtime( $this->fileName );
	}

	/**
	 *	Returns the encoding (character set) of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException	if Fileinfo is not installed
	 *	@noinspection	PhpUnused
	 */
	public function getEncoding(): string
	{
		if( function_exists( 'finfo_open' ) ){
			$magicFile	= ini_get( 'mime_magic.magicfile' );
//			$magicFile	= str_replace( "\\", "/", $magicFile );
//			$magicFile	= preg_replace( "@\.mime$@", "", $magicFile );
			$fileInfo	= finfo_open( FILEINFO_MIME_ENCODING, $magicFile );
			$mimeType	= finfo_file( $fileInfo, realpath( $this->fileName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( substr( PHP_OS, 0, 3 ) != "WIN" ){
			$command	= 'file -b --mime-encoding '.escapeshellarg( $this->fileName );
			return trim( exec( $command ) );
		}
		throw new RuntimeException( 'PHP extension Fileinfo is missing' );
	}

	/**
	 *	Returns Extension of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getExtension(): string
	{
		$info = pathinfo( $this->fileName );
		return $info['extension'];
	}

	/**
	 *	Returns File Name of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function getGroup(): string
	{
		$group	= filegroup( $this->fileName );
		if( FALSE === $group )
			throw new RuntimeException( 'Could not get group of file "'.$this->fileName.'"' );
		return $group;
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
			$mimeType	= finfo_file( $fileInfo, realpath( $this->fileName ) );
			finfo_close( $fileInfo );
			return $mimeType;
		}
		else if( substr( PHP_OS, 0, 3 ) != "WIN" ){
			$command	= 'file -b --mime-type '.escapeshellarg( $this->fileName );
			return trim( exec( $command ) );
		}
		else if( function_exists( 'mime_content_type' ) && $mimeType = mime_content_type( $this->fileName ) ){
			return $mimeType;
		}
		throw new RuntimeException( 'PHP extension Fileinfo is missing' );
	}

	public function getOwner(): string
	{
		$user	= fileowner( $this->fileName );
		if( FALSE === $user )
			throw new RuntimeException( 'Could not get owner of file "'.$this->fileName.'"' );
		return $user;
	}

	/**
	 *	Returns canonical Path to the current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath(): string
	{
		$realpath	= realpath( $this->fileName );
		$path	= dirname( $realpath );
		$path	= str_replace( "\\", "/", $path );
		$path	.= "/";
		return	$path;
	}

	/**
	 *	Returns OS permissions of current file as octal value.
	 *	@access		public
	 *	@return		Permissions		File permissions object
	 */
	public function getPermissions(): Permissions
	{
		return new Permissions( $this->fileName );
	}

	/**
	 *	Returns Size of current File.
	 *	@access		public
	 *	@param		integer|NULL	$precision		Precision of rounded Size (only if unit is set)
	 *	@return		int
	 */
	public function getSize( int $precision = NULL ): int
	{
		$size	= filesize( $this->fileName );
		if( $precision )
			$size	= UnitFormater::formatBytes( $size, $precision );
		return $size;
	}

	/**
	 *	Indicates whether a given user is owner of current file.
	 *	On Windows this method always returns TRUE.
	 *	@access		public
	 *	@param		string|NULL	$user		Name of user to check ownership for, current user by default
	 *	@return		boolean
	 *	@noinspection	PhpUnused
	 */
	public function isOwner( ?string $user = NULL ): bool
	{
		$user	= $user ?? get_current_user();
		if( !function_exists( 'posix_getpwuid' ) )
			return TRUE;
		$uid	= fileowner( $this->fileName );
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
	 *	@return		bool
	 */
	public function isReadable(): bool
	{
		return is_readable( $this->fileName );
	}

	/**
	 *	Loads a File into a String statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name of File to load
	 *	@return		string
	 */
	public static function load( string $fileName ): string
	{
		$reader	= new Reader( $fileName );
		return $reader->readString();
	}

	/**
	 *	Loads a File into an Array statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name of File to load
	 *	@return		array
	 */
	public static function loadArray( string $fileName ): array
	{
		$reader	= new Reader( $fileName );
		return $reader->readArray();
	}

	/**
	 *	Reads file and returns it as array.
	 *	@access		public
	 *	@return		array
	 */
 	public function readArray(): array
	{
		$content	= $this->readString();
		return preg_split( '/\r?\n/', $content );
	}

	/**
	 *	Reads file and returns it as string.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException			if File is not existing
	 *	@throws		RuntimeException			if File is not readable
	 */
 	public function readString(): string
	{
		if( !$this->exists() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
		if( !$this->isReadable() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not readable' );
		return file_get_contents( $this->fileName );
	}
}
