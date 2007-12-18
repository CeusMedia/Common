<?php
/**
 *	Basic File Reader.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Basic File Reader.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_Reader
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
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
	
	public static function load( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "File '".$fileName."' is not existing." );
		if( !is_readable( $fileName ) )
			throw new Exception( "File '".$fileName."' is not readable." );
		return file_get_contents( $fileName );
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
		return file_get_contents( $this->fileName );
	}

	/**
	 *	Reads file and returns it as array.
	 *	@access		public
	 *	@return		array
	 */
 	public function readArray()
	{
		if( !$this->exists( $this->fileName ) )
			throw new Exception( "File '".$this->fileName."' is not existing." );
		if( !$this->isReadable( $this->fileName ) )
			throw new Exception( "File '".$this->fileName."' is not readable." );
		return file( $this->fileName );
	}
}
?>