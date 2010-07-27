<?php
/**
 *	Base File Writer.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Base File Writer.
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_Writer
{
	public static $minFreeDiskSpace	= 10485760;
	
	/**	@var		string		$fileName		File Name of List, absolute or relative URI */
	protected $fileName;

	/**
	 *	Constructor. Creates if File if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		string		$fileName		File Name, absolute or relative URI
	 *	@param		string		$creationMode	UNIX rights for chmod()
	 *	@param		string		$creationUser	User Name for chown()
	 *	@param		string		$creationGroup	Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( $fileName, $creationMode = NULL, $creationUser = NULL, $creationGroup = NULL )
	{
		$this->fileName	= $fileName;
		if( $creationMode && !file_exists( $fileName ) )
			$this->create( $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Create a file and sets Rights, Owner and Group.
	 *	@access		public
	 *	@param		string		$mode			UNIX rights for chmod()
	 *	@param		string		$user			User Name for chown()
	 *	@param		string		$group			Group Name for chgrp()
	 *	@return		void
	 */
	public function create( $mode = NULL, $user = NULL, $group = NULL )
	{
		if( !@touch( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" could not been created' );
		if( disk_free_space(  $this->fileName  ) < self::$minFreeDiskSpace )
		{
			$this->remove();
			throw new RuntimeException( 'Disk is full' );
		}

		if( $mode )
			$this->setPermissions( $mode );
		if( $user )
			$this->setOwner( $user );
		if( $group )
			$this->setGroup( $group );
	}

	/**
	 *	Return true if File is writable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isWritable()
	{
		return is_writable( $this->fileName );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		bool
	 */
	public function setGroup( $groupName )
	{
		if( !$groupName )
			throw new InvalidArgumentException( 'No Group Name given.' );
		if( !$this->exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
		if( !$this->writer->isWritable( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		if( !@chGrp( $this->fileName, $groupName ) )
			throw new RuntimeException( 'Only a superuser can change file group' );
	}

	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS User Name of new File Owner
	 *	@return		bool
	 */
	public function setOwner( $userName )
	{
		if( !$userName )
			throw new InvalidArgumentException( 'No User Name given.' );
		if( !$this->exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing' );
#		if( !$this->isOwner() )
#			throw new RuntimeException( 'File "'.$this->fileName.'" is not owned by current user' );
		if( !$this->writer->isWritable( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		if( !@chOwn( $this->fileName, $userName ) )
			throw new RuntimeException( 'Only a superuser can change file owner' );
	}

	/**
	 *	Sets permissions of current File.
	 *	@access		public
	 *	@param		integer		$mode			OCTAL value of new rights (eg. 0750)
	 *	@return		bool
	 */
	public function setPermissions( $mode )
	{
		$permissions	= new File_Permissions( $this->fileName );
		return $permissions->setByOctal( $mode );
	}

	/**
	 *	Removing the file.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove()
	{
		return @unlink( $this->fileName );
	}
	
	/**
	 *	Saves Content into a File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName 		URI of File
	 *	@param		string		$content		Content to save in File
	 *	@param		string		$mode			UNIX rights for chmod()
	 *	@param		string		$user			User Name for chown()
	 *	@param		string		$group			Group Name for chgrp()
	 *	@return		integer		Number of written bytes
	 */
	public static function save( $fileName, $content, $mode = NULL, $user = NULL, $group = NULL )
	{
		$writer	= new File_Writer( $fileName, $mode, $user, $group );
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
	 */
	public static function saveArray( $fileName, $array, $lineBreak = "\n" )
	{
		$writer	= new File_Writer( $fileName );
		return $writer->writeArray( $array, $lineBreak );
	}

	/**
	 *	Writes an Array into the File and returns Length.
	 *	@access		public
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		integer		Number of written bytes
	 */
	public function writeArray( $array, $lineBreak = "\n" )
	{
		$string	= implode( $lineBreak, $array );
		return $this->writeString( $string );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		string		string to write to file
	 *	@return		integer		Number of written bytes
	 */
	public function writeString( $string )
	{
		if( !file_exists( $this->fileName ) )
			$this->create();
		if( !$this->isWritable( $this->fileName ) )			
			throw new RuntimeException( 'File "'.$this->fileName.'" is not writable' );
		$count	= file_put_contents( $this->fileName, $string );
		if( $count === false )	
			throw new RuntimeException( 'File "'.$fileName.'" could not been written' );
		return $count;
	}
}
?>