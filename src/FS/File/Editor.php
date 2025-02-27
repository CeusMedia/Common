<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for Files.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Editor for Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			finish Writer Methods (create, isWritable)
 */
class Editor extends Reader
{
	/**	@var		Writer	$writer			Instance of file writer class */
	protected Writer $writer;

	/**
	 *	Constructor. Creates File if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		File|string		$file			File Name or URI of File
	 *	@param		integer			$creationMode	UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL		$creationUser	UserName for chown()
	 *	@param		string|NULL		$creationGroup	Group Name for chgrp()
	 *	@throws		FileNotExistingException	if check and file is not existing, not readable or given path is not a file
	 *	@throws		IoException					if file is a directory, a link or already existing
	 *	@throws		IoException					if file creation failed
	 */
	public function __construct( File|string $file, int $creationMode = 0640, ?string $creationUser = NULL, ?string $creationGroup = NULL )
	{
		parent::__construct( $file, FALSE );
		$this->writer	= new Writer( $this->file->getPathName(), $creationMode, $creationUser, $creationGroup );
	}

	public function appendString( string $string ): int
	{
		return $this->writer->appendString( $string );
	}

	public function copy( string $fileName ): bool
	{
		return @copy( $this->file->getPathName(), $fileName );
	}

	/**
	 *	Removes current File.
	 *	@param		string		$fileName
	 *	@return		bool
	 *	@throws		IoException					if file is a directory or a link
	 */
	public static function delete( string $fileName ): bool
	{
		return Writer::delete( $fileName );
	}

	/**
	 *	Return true if File is writable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isWritable(): bool
	{
		return $this->writer->isWritable();
	}

	/**
	 *	Removes current File.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove(): bool
	{
		return $this->writer->remove();
	}

	/**
	 *	Renames current File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to rename to
	 *	@return		bool
	 */
	public function rename( string $fileName ): bool
	{
		if( !$fileName )
			throw new InvalidArgumentException( 'No File Name given.' );
		$result	= @rename( $this->file->getPathName(), $fileName );
		if( $result === FALSE )
			throw new RuntimeException( 'File "'.$this->file->getPathName().'" could not been renamed.' );
		$this->__construct( $fileName );
		return $result;
	}

	/**
	 *	Saves a String into the File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name to write to
	 *	@param		string		$string			List of String to write to File
	 *	@return		int
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public static function save( string $fileName, string $string ): int
	{
		return Writer::save( $fileName, $string );
	}

	/**
	 *	Writes an Array into the File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name to write to
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public static  function saveArray( string $fileName, array $array, string $lineBreak = "\n" ): int
	{
		return Writer::saveArray( $fileName, $array, $lineBreak );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		void
	 */
	public function setGroup( string $groupName ): void
	{
		$this->writer->setGroup( $groupName );
	}

	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS UserName of new File Owner
	 *	@return		void
	 */
	public function setOwner( string $userName ): void
	{
		$this->writer->setOwner( $userName );
	}

	/**
	 *	Sets permissions of current File.
	 *	@access		public
	 *	@param		integer		$mode			OCTAL value of new rights (e.g. 0750)
	 *	@return		bool
	 */
	public function setPermissions( int $mode ): bool
	{
		return $this->writer->setPermissions( $mode );
	}

	/**
	 *	Writes an Array into the File and returns Length.
	 *	@access		public
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function writeArray( array $array, string $lineBreak = "\n" ): int
	{
		return $this->writer->writeArray( $array, $lineBreak );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		$string		string to write to file
	 *	@param		boolean				$strict		Flag: throw exceptions, default: yes
	 *	@return		integer|boolean		Number of written bytes or FALSE on fail
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function writeString( string $string, bool $strict = TRUE ): int|bool
	{
		return $this->writer->writeString( $string, $strict );
	}
}
