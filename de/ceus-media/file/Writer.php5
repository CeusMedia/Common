<?php
/**
 *	Base File Writer.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base File Writer.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_Writer
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName			URI of File
	 *	@param		string		$creationMode		UNIX rights for chmod()
	 *	@param		string		$creationUser		User Name for chown()
	 *	@param		string		$creationGroup		Group Name for chgrp()
	 *	@return		void
	 */
	public function __construct( $fileName, $creationMode = false, $creationUser = false, $creationGroup = false )
	{
		$this->fileName = $fileName;
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
	public function create( $mode = false, $user = false, $group = false )
	{
		if( false !== ( $fp = fopen( $this->fileName, "w+" ) ) )
		{
			fputs( $fp, "" );
			fclose( $fp );
			if( $mode )
				chmod( $this->fileName, $mode );
			if( $user )
				chown( $this->fileName, $user );
			if( $group )
				chgrp( $this->fileName, $group );
			return true;
		}
		return false;
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
	 *	Writes a string to the file.
	 *	@access		public
	 *	@param		string		string		string to write to file
	 *	@return		bool
	 */
	public function writeString( $string )
	{
		if( !file_exists( $this->fileName ) )
			if( !$this->create() )
				throw new Exception( "File '".$this->fileName."' could not be created." );
		if( !$this->isWritable( $this->fileName ) )			
			throw new Exception( "File '".$this->fileName."' is not writable." );
		$count	= file_put_contents( $this->fileName, $string );
		return $count !== FALSE;
	}

	/**
	 *	Writes an array to the file.
	 *	@access		public
	 *	@param		array		array		array of strings to write to file
	 *	@param		string		break		line break string
	 *	@return		bool
	 */
	public function writeArray( $array, $break = "\n" )
	{
		$string	= implode( $break, $array );
		return $this->writeString( $string );
	}
}
?>