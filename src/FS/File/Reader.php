<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Basic File Reader.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File;
use RuntimeException;

/**
 *	Basic File Reader.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**	@var		File		$file		File Name or URI of File */
	protected File $file;

	/**
	 *	Loads a File into a String statically.
	 *	@access		public
	 *	@static
	 *	@param		File|string		$file		Name of File to load
	 *	@return		string|NULL
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 *	@throws		IoException					if strict and file is not readable
	 */
	public static function load( File|string $file ): string|NULL
	{
		$reader	= new self( $file, FALSE );
		return $reader->readString();
	}

	/**
	 *	Loads a File into an Array statically.
	 *	@access		public
	 *	@static
	 *	@param		File|string		$file		Name of File to load
	 *	@return		array
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 */
	public static function loadArray( File|string $file ): array
	{
		$reader	= new self( $file, FALSE );
		return $reader->readArray();
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		File|string		$file		File Name or URI of File
	 *	@return		void
	 *	@throws		FileNotExistingException	if check and file is not existing, not readable or given path is not a file
	 */
	public function __construct( File|string $file, bool $check = TRUE )
	{
		$this->file	= is_string( $file ) ? new File( $file ) : $file;
		if( $check )
			$this->file->exists( TRUE ) && $this->file->isReadable( TRUE );
	}

	/**
	 *	Indicates whether current File is equal to another File.
	 *	@access		public
	 *	@param		File|string		$file		Name of File to compare with
	 *	@return		bool
	 *	@throws		FileNotExistingException	if check and file is not existing, not readable or given path is not a file
	 */
	public function equals( File|string $file ): bool
	{
		$toCompare	= self::load( $file );
		$thisFile	= self::load( $this->file );
		return( $thisFile == $toCompare );
	}

	/**
	 *	Indicates whether a file is existing at the existing path name.
	 *	@param		boolean		$strict			Flag: throw exception if anything goes wrong, default: no
	 *	@return		boolean
	 *	@throws		FileNotExistingException	if strict and file is not existing, not readable or given path is not a file
	 */
	public function exists( bool $strict = FALSE ): bool
	{
		return $this->file->exists( $strict );
	}

	/**
	 *	Returns Basename of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename(): string
	{
		return $this->file->getBasename();
	}

	/**
	 *	Returns the file date as timestamp.
	 *	@access		public
	 *	@return		int
	 *	@throws		FileNotExistingException	if check and file is not existing or given path is not a file
	 */
	public function getDate(): int
	{
		return $this->file->getTime();
	}

	/**
	 *	Returns the encoding (character set) of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException			if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpUnused
	 */
	public function getEncoding(): string
	{
		return $this->file->getEncoding();
	}

	/**
	 *	Returns Extension of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException			if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 */
	public function getExtension(): string
	{
		return $this->file->getExtension();
	}

	/**
	 *	Returns File Name of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName(): string
	{
		return $this->file->getPathName();
	}

	/**
	 *	Returns group name or ID of file.
	 *
	 *	@access		public
	 *	@param		boolean		$resolveName	Try to resolve username instead of returning ID
	 *	@return		string|int
	 *	@throws		FileNotExistingException	if file is not existing
	 */
	public function getGroup( bool $resolveName = TRUE ): int|string
	{
		return $this->file->getGroup( $resolveName );
	}

	/**
	 *	Returns the MIME type of current File.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException	if Fileinfo is not installed
	 *	@throws		FileNotExistingException	if file is not existing
	 */
	public function getMimeType(): string
	{
		return $this->file->getMimeType();
	}

	/**
	 *	Returns owner name or ID of file.
	 *
	 *	@access		public
	 *	@param		boolean		$resolveName	Try to resolve username instead of returning ID
	 *	@return		string|int
	 *	@throws		FileNotExistingException	if file is not existing
	 */
	public function getOwner( bool $resolveName = TRUE ): int|string
	{
		return $this->file->getOwner( $resolveName );
	}

	/**
	 *	Returns canonical Path to the current File.
	 *	@access		public
	 *	@throws		FileNotExistingException	if file is not existing or given path is not a file
	 */
	public function getPath(): string
	{
		$this->exists( TRUE );
		return dirname( realpath( $this->file->getPathName() ) ).'/';
	}

	/**
	 *	Returns OS permissions of current file as octal value.
	 *	@access		public
	 *	@return		Permissions		File permissions object
	 *	@throws		FileNotExistingException	if file is not existing or given path is not a file
	 */
	public function getPermissions(): Permissions
	{
		return $this->file->getPermissions();
	}

	/**
	 *	@access		public
	 *	@param		integer|NULL	$precision		Precision of rounded Size (only if unit is set)
	 *	@return		integer|string
	 *	@throws		FileNotExistingException	if file is not existing or given path is not a file
	 */
	public function getSize( int $precision = NULL ): int|string
	{
		return $this->file->getSize( $precision );
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
		return $this->file->isOwner( $user );
	}

	/**
	 *	Indicates whether a file is readable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isReadable(): bool
	{
		return $this->file->isReadable();
	}

	/**
	 *	Reads set file and returns it as array.
	 *	@access		public
	 *	@return		array
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@throws		IoException					if strict and file is not readable
	 */
 	public function readArray(): array
	{
		$content	= $this->readString();
		if( NULL === $content )
			return [];
		return preg_split( '/\r*\n/', $content );
	}

	/**
	 *	Reads set file and returns it as string.
	 *	@access		public
	 *	@return		string|NULL
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@throws		IoException					if strict and file is not readable
	 */
 	public function readString(): ?string
	{
		return $this->file->getContent();
	}
}
