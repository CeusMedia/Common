<?php
/**
 *	Basic File Reader.
 *	@package		file
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Basic File Reader.
 *	@package		file
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_Reader
{
	/**	@var		string		$fileName		File Name or URI of File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name or URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Indicates whether current File is equal to another File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to compare with
	 *	@return		bool
	 */
	public function equals( $fileName )
	{
		$toCompare	= File_Reader::load( $fileName );
		$thisFile	= File_Reader::load( $this->fileName );
		return( $thisFile == $toCompare );
	}

	/**
	 *	Indicates whether current URI is an existing File.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		$exists	= file_exists( $this->fileName );
		$isFile	= is_file( $this->fileName );
		return $exists && $isFile;
	}

	/**
	 *	Returns Basename of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getBasename()
	{
		return basename( $this->fileName );
	}

	/**
	 *	Returns File Name of current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 *	Returns Extension of current File.
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
	 *	Returns canonical Path to the current File.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		$realpath	= realpath( $this->fileName );
		$path	= dirname( $realpath );
		$path	= str_replace( "\\", "/", $path );
		$path	.= "/";
		return	$path;
	}

	/**
	 *	Returns Size of current File.
	 *	@access		public
	 *	@param		int			$unit			Unit (SIZE_BYTE|SIZE_KILOBYTE|SIZE_MEGABYTE|SIZE_GIGABYTE)
	 *	@param		int			$precision		Precision of rounded Size (only if unit is set)
	 *	@return		int
	 */
	public function getSize( $unit = 1, $precision = NULL )
	{
		$size	= filesize( $this->fileName );
		if( $unit )
		{
			import( 'de.ceus-media.alg.UnitFormater' );
			$size	= Alg_UnitFormater::formatNumber( $size, $unit, $precision );
		}
		return $size;
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
	 *	Loads a File into a String statically.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to load
	 *	@return		string
	 */
	public static function load( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		return $reader->readString();
	}
	
	/**
	 *	Loads a File into an Array statically.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to load
	 *	@return		array
	 */
	public static function loadArray( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		return $reader->readArray();
	}

	/**
	 *	Reads file and returns it as string.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		if( !$this->exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing.' );
		if( !$this->isReadable( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not readable.' );
		return file_get_contents( $this->fileName );
	}

	/**
	 *	Reads file and returns it as array.
	 *	@access		public
	 *	@return		array
	 */
 	public function readArray( $lineBreak = "\n" )
	{
		$content	= $this->readString();
		return explode( $lineBreak, $content );
	}
}
?>