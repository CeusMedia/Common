<?php
/**
 *	Base File Writer.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Base File Writer.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_Writer
{
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
		if( false === @file_put_contents( $this->fileName, "" ) )
			throw new RuntimeException( "File '".$this->fileName."' could not been created." );
			
		if( $mode )
			chmod( $this->fileName, $mode );
		if( $user )
			chown( $this->fileName, $user );
		if( $group )
			chgrp( $this->fileName, $group );
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
	 *	@param		string		$fileName 		URI of File
	 *	@param		string		$content		Content to save in File
	 *	@return		int
	 */
	public static function save( $fileName, $content )
	{
		$writer	= new File_Writer( $fileName );
		return $writer->writeString( $content );
	}

	/**
	 *	Saves an Array into a File statically and returns Length.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@param		array		$array			Array to save
	 *	@param		string		$lineBreak		Line Break
	 *	@return		int
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
	 *	@return		int
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
	 *	@return		int
	 */
	public function writeString( $string )
	{
		if( !file_exists( $this->fileName ) )
			$this->create();
		if( !$this->isWritable( $this->fileName ) )			
			throw new RuntimeException( "File '".$this->fileName."' is not writable." );
		$count	= file_put_contents( $this->fileName, $string );
		if( $count === false )	
			throw new RuntimeException( 'File "'.$fileName.'" could not been written.' );
		return $count;
	}
}
?>