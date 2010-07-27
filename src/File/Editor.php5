<?php
/**
 *	Editor for Files.
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
 *	@extends		File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$a
 */
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Editor for Files.
 *	@category		cmClasses
 *	@package		File
 *	@extends		File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$a
 *	@todo			finish Writer Methods (create, isWritable)
 */
class File_Editor extends File_Reader
{
	/**	@var		File_Writer	$writer			Instance of file writer class */
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
		$this->writer	= new File_Writer( $fileName, $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Sets Group of current File.
	 *	@access		public
	 *	@param		string		$groupName		OS Group Name of new File Owner
	 *	@return		bool
	 */
	public function setGroup( $groupName )
	{
		return $this->writer->setOwner( $groupName );
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
		return File_Writer::save( $fileName, $string );
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
		return File_Writer::saveArray( $fileName, $array, $lineBreak );
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
?>