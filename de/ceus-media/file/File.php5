<?php
import( 'de.ceus-media.Object' );
/**
 *	Base File implementation to inherit.
 *	@package		file
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Base File implementation to inherit.
 *	@package		file
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class File
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName, $creationMode = false, $creationUser = false, $creationGroup = false )
	{
		$this->fileName = $fileName;
		if( $creationMode && !$this->exists() )
			$this->create( $creationMode, $creationUser, $creationGroup );
	}

	/**
	 *	Create a file and sets Rights, Owner and Group.
	 *	@access		public
	 *	@param		string	mod		UNIX rights for chmod()
	 *	@param		string	user		user name for chown()
	 *	@param		string	user		group name for chgrp()
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
	 *	Indicates whether a file contains same data like another file.
	 *	@access		public
	 *	@param		string	fileName		Name of File to compare with
	 *	@return		bool
	 */
	public function equals( $fileName )
	{
		$file	= new File( $fileName );
		while( $string = $this->readString() )
			$file1 .= $string;
		while( $string = $file->readString() )
			$file2 .= $string;
		return( $file1 == $file2 );
	}

	/**
	 *	Proving existence of the file.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		return( file_exists( $this->fileName ) && is_file( $this->fileName ) );
	}

	/**
	 *	Returns the basename of the file.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename()
	{
		return basename( $this->fileName );
	}

	/**
	 *	Returns the uri of the file.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 *	Returns the extension of the file.
	 *	@access		public
	 *	@return		string
	 */
	public function getExtension()
	{
		$info = pathinfo( $this->fileName );
		$ext = $info['extension'];
		return $ext;
	}

	/**
	 *	Returns the canonical pathname of the file.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		$realpath	= realpath( $this->fileName );
		$path	= dirname( $realpath );
		$path	= str_replace( "\\", "/", $path );
		if( $path == "." )
			$path	= "";
		else
			$path	.= "/";
		return	$path;
	}

	/**
	 *	Returns the file size in bytes.
	 *	@access		public
	 *	@return		int
	 */
	public function getSize()
	{
		return filesize( $this->fileName );
	}
	
	/**
	 *	Returns the file date as timestamp.
	 *	@access		public
	 *	@return		int
	 */
	public function getDate()
	{
		return filemtime( $this->fileName );
	}

	/**
	 *	Indicates whether a file is readable.
	 *	@access		public
	 *	@return		bool
	 */
	public function isReadable()
	{
		return is_readable( $this->fileName );
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
	 *	Reads file and returns it as string.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		if( !$this->exists( $this->fileName ) )
			throw new Exception( "File '".$this->fileName."' is not existing." );
		if( !$this->isReadable( $this->fileName ) )
			throw new Exception( "File '".$this->fileName."' is not readable." );
		$content	= "";
		if( function_exists( 'file_get_contents' ) )
			return file_get_contents( $this->fileName );
		$fp = @fopen( $this->fileName, "r" );
		while( !feof( $fp ) )
			$content .= fgets( $fp, 4096 );
		fclose( $fp );		
		return $content;
	}

	/**
	 *	Reads file and returns it as array.
	 *	@access		public
	 *	@return		array
	 */
 	public function readArray()
	{
		if( !$this->exists( $this->fileName ) )
			trigger_error( "File '".$this->fileName."' is not existing.", E_USER_ERROR );
		if( !$this->isReadable( $this->fileName ) )
			trigger_error( "File '".$this->fileName."' is not readable.", E_USER_ERROR );
		return file( $this->fileName );
	}

	/**
	 *	Removing the file.
	 *	@access		public
	 *	@return		bool
	 */
	public function remove()
	{
		return unlink( $this->fileName );
	}

	/**
	 *	Writes a string to the file.
	 *	@access		public
	 *	@param		string		string		string to write to file
	 *	@return		bool
	 */
	public function writeString( $string )
	{
		if( function_exists( 'file_put_contents' ) )
			return (bool) file_put_contents( $this->fileName, $string );
		$fp = fopen( $this->fileName, "w" );
		if ($fp)
		{
			@fwrite( $fp, $string );
			@fclose( $fp );
			return true;
		}
		return false;
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
		if( !$this->exists( $this->fileName ) )
			if( !$this->create() )
				throw new Exception( "File '".$this->fileName."' could not be created." );
		if( !$this->isWritable( $this->fileName ) )			
			throw new Exception( "File '".$this->fileName."' is not writable." );
		$fp = @fopen( $this->fileName, "w");
		if( $fp )
		{
			foreach( $array as $string )
				@fwrite( $fp, rtrim( $string ).$break );
			@fclose( $fp );
			return true;
		}
		return false;
	}
}
?>