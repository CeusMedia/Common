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
	public static $minFreeDiskSpace	= 10_485_760;

	/**	@var		string		$fileName		File Name of List, absolute or relative URI */
	protected $fileName;

	/**
	 *	Constructor. Creates File if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		string		$fileName		File Name, absolute or relative URI
	 *	@param		integer		$creationMode	UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL	$creationUser	Username for chown()
	 *	@param		string|NULL	$creationGroup	Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( string $fileName, int $creationMode = 0640, ?string $creationUser = NULL, ?string $creationGroup = NULL )
	{
		$this->fileName	= $fileName;
		if( $creationMode && !file_exists( $fileName ) )
			$this->create( $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		$string		string to write to file
	 *	@return		integer		Number of written bytes
	 *	@throws		InvalidArgumentException if no string is given
	 *	@throws		RuntimeException if file is not writable
	 *	@throws		RuntimeException if written length is unequal to string length
	 */
	public function appendString( string $string ): int
	{
		if( !file_exists( $this->fileName ) )
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
	 *	@throws		RuntimeException if no space is left on file system
	 *	@throws		RuntimeException if file could not been created
	 *	@return		void
	 */
	public function create( int $mode = 0640, ?string $user = NULL, ?string $group = NULL )
	{
		if( self::$minFreeDiskSpace && self::$minFreeDiskSpace > disk_free_space( getcwd() ) )
			throw new RuntimeException( 'No space left' );

		if( !@touch( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" could not been created' );

		if( $mode )
			$this->setPermissions( $mode );
		if( $user )
			$this->setOwner( $user );
		if( $group )
			$this->setGroup( $group );
	}

	public static function delete( string $fileName ): bool
	{
		$writer	= new Writer( $fileName );
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
		if( file_exists( $this->fileName ) )
			return unlink( $this->fileName );
		return FALSE;
	}

	/**
	 *	Saves Content into a File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName 		URI of File
	 *	@param		string		$content		Content to save in File
	 *	@param		integer		$mode			UNIX rights for chmod() as octal integer (starting with 0), default: 0640
	 *	@param		string|NULL	$user			Username for chown()
	 *	@param		string|NULL	$group			Group Name for chgrp()
	 *	@return		integer		Number of written bytes
	 *	@throws		InvalidArgumentException if no string is given
	 */
	public static function save( string $fileName, string $content, int $mode = 0640, ?string $user = NULL, ?string $group = NULL ): int
	{
		$writer	= new Writer( $fileName, $mode, $user, $group );
		return $writer->writeString( $content );
	}

	/**
	 *	Saves an Array into a File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of File
	 *	@param		array		$array			Array to save
	 *	@param		string		$lineBreak		Line Break
	 *	@return		integer		Number of written bytes
	 *	@throws		InvalidArgumentException if no array is given
	 */
	public static function saveArray( string $fileName, array $array, string $lineBreak = "\n" ): int
	{
		$writer	= new Writer( $fileName );
		return $writer->writeArray( $array, $lineBreak );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		void
	 */
	public function setGroup( string $groupName )
	{
		if( !$groupName )
			throw new InvalidArgumentException( 'No Group Name given.' );
		if( !file_exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		if( !@chGrp( $this->fileName, $groupName ) )
			throw new RuntimeException( 'Only a superuser can change file group' );
	}

	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS username of new File Owner
	 *	@return		void
	 */
	public function setOwner( string $userName )
	{
		if( !$userName )
			throw new InvalidArgumentException( 'No User Name given.' );
		if( !file_exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
#		if( !$this->isOwner() )
#			throw new RuntimeException( 'File "'.$this->fileName.'" is not owned by current user' );
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		if( !@chOwn( $this->fileName, $userName ) )
			throw new RuntimeException( 'Only a superuser can change file owner' );
	}

	/**
	 *	Sets permissions of current File.
	 *	@access		public
	 *	@param		integer		$mode			OCTAL value of new rights (e.g. 0750)
	 *	@return		bool
	 */
	public function setPermissions( int $mode ): bool
	{
		$mode			= decoct( $mode );
		$permissions	= new Permissions( $this->fileName );
		return $permissions->setByOctal( $mode );
	}

	/**
	 *	Writes an Array into the File and returns Length.
	 *	@access		public
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		integer		Number of written bytes
	 *	@throws		InvalidArgumentException if no array is given
	 */
	public function writeArray( array $array, string $lineBreak = "\n" ): int
	{
		$string	= implode( $lineBreak, $array );
		return $this->writeString( $string );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		$string		string to write to file
	 *	@return		integer		Number of written bytes
	 *	@throws		RuntimeException if file is not writable
	 *	@throws		RuntimeException if written length is unequal to string length
	 */
	public function writeString( string $string ): int
	{
		if( !file_exists( $this->fileName ) )
			$this->create();
		if( !$this->isWritable() )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		$count	= file_put_contents( $this->fileName, $string );
		if( $count != strlen( $string ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" could not been written' );
		return $count;
	}
}
