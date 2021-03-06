<?php
/**
 *	Editor for Files.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 *	@version		$Id$a
 */
/**
 *	Editor for Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@extends		FS_File_Reader
 *	@uses			FS_File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 *	@version		$Id$a
 *	@todo			finish Writer Methods (create, isWritable)
 */
class FS_File_Editor extends FS_File_Reader
{
	/**	@var		FS_File_Writer	$writer			Instance of file writer class */
	protected $writer;

	/**
	 *	Constructor. Creates File if not existing and Creation Mode is set.
	 *	@access		public
	 *	@param		string		$fileName		File Name or URI of File
	 *	@param		string		$creationMode	UNIX rights for chmod()
	 *	@param		string		$creationUser	User Name for chown()
	 *	@param		string		$creationGroup	Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( $fileName, $creationMode = NULL, $creationUser = NULL, $creationGroup = NULL )
	{
		parent::__construct( $fileName );
		$this->writer	= new FS_File_Writer( $fileName, $creationMode, $creationUser, $creationGroup );
	}

	public function appendString( $string )
	{
		$this->writer->appendString( $string );
	}

	public function copy( $fileName )
	{
		return @copy( $this->fileName, $fileName );
	}

	public static function delete( $fileName )
	{
		return FS_File_Writer::delete( $fileName );
	}

	/**
	 *	Return true if File is writable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isWritable()
	{
		return $this->writer->isWritable();
	}

	/**
	 *	Removes current File.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove()
	{
		return $this->writer->remove();
	}

	/**
	 *	Renames current File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to rename to
	 *	@return		bool
	 */
	public function rename( $fileName )
	{
		if( !$fileName )
			throw new InvalidArgumentException( 'No File Name given.' );
		$result	= @rename( $this->fileName, $fileName );
		if( $result === FALSE )
			throw new RuntimeException( 'File "'.$this->fileName.'" could not been renamed.' );
		$this->__construct( $fileName );
		return $result;
	}

	/**
	 *	Saves a String into the File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
	 */
	public static function save( $fileName, $string )
	{
		return FS_File_Writer::save( $fileName, $string );
	}

	/**
	 *	Writes an Array into the File statically and returns Length.
	 *	@access		public
	 *	@static
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
	 */
	public static  function saveArray( $fileName, $array, $lineBreak = "\n" )
	{
		return FS_File_Writer::saveArray( $fileName, $array, $lineBreak );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		bool
	 */
	public function setGroup( $groupName )
	{
		return $this->writer->setGroup( $groupName );
	}

	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS User Name of new File Owner
	 *	@return		bool
	 */
	public function setOwner( $userName )
	{
		return $this->writer->setOwner( $userName );
	}

	/**
	 *	Sets permissions of current File.
	 *	@access		public
	 *	@param		integer		$mode			OCTAL value of new rights (eg. 0750)
	 *	@return		bool
	 */
	public function setPermissions( $mode )
	{
		return $this->writer->setPermissions( $mode );
	}

	/**
	 *	Writes an Array into the File and returns Length.
	 *	@access		public
	 *	@param		array		$array			List of String to write to File
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
	 */
	public function writeArray( $array, $lineBreak = "\n" )
	{
		return $this->writer->writeArray( $array, $lineBreak );
	}

	/**
	 *	Writes a String into the File and returns Length.
	 *	@access		public
	 *	@param		string		string		string to write to file
	 *	@return		int
	 */
	public function writeString( $string )
	{
		return $this->writer->writeString( $string );
	}
}
