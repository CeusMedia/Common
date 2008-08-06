<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Editor for Files.
 *	@package		file
 *	@extends		File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
 */
/**
 *	Editor for Files.
 *	@package		file
 *	@extends		File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
 *	@todo			finish Writer Methods (create, isWritable)
 */
class File_Editor extends File_Reader
{
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
	public function changeGroup( $groupName )
	{
		if( !$groupName )
			throw new InvalidArgumentException( 'No Group Name given.' );
		return chGrp( $this->fileName, $groupName );
	}
	
	/**
	 *	Sets Mode of current File.
	 *	@access		public
	 *	@param		int			$mode			OS User Name of new File Owner
	 *	@return		bool
	 */
	public function changeMode( $mode )
	{
		if( !is_int( $mode ) )
			throw new InvalidArgumentException( 'Mode must be an Integer.' );
		if( strlen( $mode ) < 3 )
			throw new InvalidArgumentException( 'Mode must be at least 3 Digits.' );
		if( strlen( $mode ) > 4 )
			throw new InvalidArgumentException( 'Mode must be at most 4 Digits.' );
		if( strlen( $mode ) == 3 )
			$mode	= (int) "0".$mode;
		return chMod( $this->fileName, $mode );
	}
	
	/**
	 *	Sets Owner of current File.
	 *	@access		public
	 *	@param		string		$userName		OS User Name of new File Owner
	 *	@return		bool
	 */
	public function changeOwner( $userName )
	{
		if( !$userName )
			throw new InvalidArgumentException( 'No User Name given.' );
		return chOwn( $this->fileName, $userName );
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