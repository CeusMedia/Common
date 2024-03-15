<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base File Writer.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Base File Writer.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Writer
{
	public static int $minFreeDiskSpace	= 10_485_760;

	/**	@var		File		$file			File object */
	protected File $file;

	/**	@var		string		$fileName		File Name of List, absolute or relative URI */
	protected string $fileName;

	/**
	 *	Constructor. Creates File if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		File|string		$file			File Name, absolute or relative URI
	 *	@param		integer			$creationMode	UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL		$creationUser	Username for chown()
	 *	@param		string|NULL		$creationGroup	Group Name for chgrp()
	 *	@return		void
	 *	@throws		RuntimeException			if no space is left on file system
	 *	@throws		IoException					if file is a directory or a link
	 *	@throws		IoException					if file creation failed
	 */
	public function __construct( File|string $file, int $creationMode = 0640, ?string $creationUser = NULL, ?string $creationGroup = NULL )
	{
		$this->file	= is_string( $file ) ? new File( $file ) : $file;
		$this->fileName	= $this->file->getPathName();
		if( $creationMode && !$this->file->exists() )
			$this->create( $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		$string		string to write to file
	 *	@return		integer		Number of written bytes
	 *	@throws		RuntimeException			if no space is left on file system
	 *	@throws		RuntimeException			if file is not writable
	 *	@throws		IoException					if file is a directory or a link
	 *	@throws		IoException					if file creation failed
	 */
	public function appendString( string $string ): int
	{
		if( !$this->file->exists() )
			$this->create();
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		$fp	= fopen( $this->fileName, 'a' );
		fputs( $fp, $string );
		fclose( $fp );
		return strlen( $string );
	}

	/**
	 *	Create a file and sets Rights, Owner and Group.
	 *	@access		public
	 *	@param		integer		$mode			UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL	$user			Username for chown()
	 *	@param		string|NULL	$group			Group Name for chgrp()
	 *	@return		self
	 *	@throws		RuntimeException			if no space is left on file system
	 *	@throws		IoException					if file is a directory, a link or already existing
	 *	@throws		IoException					if file creation failed
	 */
	public function create( int $mode = 0640, ?string $user = NULL, ?string $group = NULL ): self
	{
		if( self::$minFreeDiskSpace && self::$minFreeDiskSpace > disk_free_space( getcwd() ) )
			throw new RuntimeException( 'No space left' );

		$this->file->create( $mode );
		if( $user )
			$this->setOwner( $user );
		if( $group )
			$this->setGroup( $group );
		return $this;
	}

	/**
	 *	...
	 *	@param		string		$fileName
	 *	@return		bool
	 *	@throws		IoException					if file is a directory or a link
	 */
	public static function delete( string $fileName ): bool
	{
		$writer	= new Writer( $fileName, 0 );
		return $writer->remove();
	}

	/**
	 *	Return true if File is writable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isWritable(): bool
	{
		return is_writable( $this->fileName );
	}

	/**
	 *	Removing the file.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove(): bool
	{
		if( file_exists( $this->file->getPathName() ) )
			return unlink( $this->file->getPathName() );
		return FALSE;
	}

	/**
	 *	Saves Content into a File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		File|string			$file			URI of File
	 *	@param		string				$content		Content to save in File
	 *	@param		integer				$mode			UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL			$user			Username for chown()
	 *	@param		string|NULL			$group			Group Name for chgrp()
	 *	@param		boolean				$strict			Flag: throw exceptions, default: yes
	 *	@return		integer|boolean		Number of written bytes or FALSE on fail
	 *	@throws		RuntimeException	if file is not writable
	 *	@throws		RuntimeException	if written length is unequal to string length
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public static function save( File|string $file, string $content, int $mode = 0640, ?string $user = NULL, ?string $group = NULL, bool $strict = TRUE ): int|bool
	{
		$writer	= new Writer( $file, $mode, $user, $group );
		return $writer->writeString( $content, $strict );
	}

	/**
	 *	Saves an Array into a File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		File|string			$file			URI of File
	 *	@param		array				$array			Array to save
	 *	@param		string				$lineBreak		Line Break
	 *	@param		boolean				$strict			Flag: throw exceptions, default: yes
	 *	@return		integer|boolean		Number of written bytes
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public static function saveArray( File|string $file, array $array, string $lineBreak = "\n", bool $strict = TRUE ): int|bool
	{
		$writer	= new Writer( $file );
		return $writer->writeArray( $array, $lineBreak, $strict );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		self
	 */
	public function setGroup( string $groupName ): self
	{
		if( !$groupName )
			throw new InvalidArgumentException( 'No Group Name given.' );
		if( !file_exists( $this->file->getPathName() ) )
			throw new RuntimeException( 'File "'.$this->file->getPathName().'" is not existing' );
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->file->getPathName().'" is not writable' );
		if( !@chGrp( $this->file->getPathName(), $groupName ) )
			throw new RuntimeException( 'Only a superuser can change file group' );
		return $this;
	}

	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS username of new File Owner
	 *	@return		self
	 */
	public function setOwner( string $userName ): self
	{
		if( !$userName )
			throw new InvalidArgumentException( 'No User Name given.' );
		if( !file_exists( $this->file->getPathName() ) )
			throw new RuntimeException( 'File "'.$this->file->getPathName().'" is not existing' );
#		if( !$this->isOwner() )
#			throw new RuntimeException( 'File "'.$this->file->getPathName().'" is not owned by current user' );
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->file->getPathName().'" is not writable' );
		if( !@chOwn( $this->file->getPathName(), $userName ) )
			throw new RuntimeException( 'Only a superuser can change file owner' );
		return $this;
	}

	/**
	 *	Sets permissions of current File.
	 *	@access		public
	 *	@param		integer		$mode			OCTAL value of new rights (e.g. 0750)
	 *	@return		bool
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 */
	public function setPermissions( int $mode ): bool
	{
		return $this->file->getPermissions()->setByOctal( decoct( $mode ) );
	}

	/**
	 *	Writes an Array into the File and returns Length.
	 *	@access		public
	 *	@param		array				$array			List of String to write to File
	 *	@param		string				$lineBreak		Line Break
	 *	@param		boolean				$strict		Flag: throw exceptions, default: yes
	 *	@return		integer|boolean		Number of written bytes or FALSE on fail
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function writeArray( array $array, string $lineBreak = "\n", bool $strict = TRUE ): int|bool
	{
		$string	= implode( $lineBreak, $array );
		return $this->writeString( $string, $strict );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string				$string		string to write to file
	 *	@param		boolean				$strict		Flag: throw exceptions, default: yes
	 *	@return		integer|boolean		Number of written bytes
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function writeString( string $string, bool $strict = TRUE ): int|bool
	{
		return $this->file->setContent( $string, $strict );
	}
}
