<?php
import( 'de.ceus-media.file.arc.Tar' );
import( 'de.ceus-media.file.arc.Gzip' );
/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
 *	@package		file.arc
 *	@extends		File_Arc_Tar
 *	@uses			File_Arc_Gzip
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Tar Gzip File allows creation and manipulation of gzipped tar archives.
 *	@package		file.arc
 *	@extends		File_Arc_Tar
 *	@uses			GzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_Arc_TarGzip extends File_Arc_Tar
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to open
	 *	@return		void
	 */
	public function __construct( $fileName = false )
	{
		if( $fileName )
			$this->open( $fileName );
	}

	/**
	 *	Opens an existing Tar Gzip File and loads contents.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to open
	 *	@return		bool
	 */
	public function open( $fileName )
	{
		if( !file_exists( $fileName ) )																		// If the tar file doesn't exist...
			throw new Exception( "TGZ file '".$fileName."' is not existing." );
		$this->fileName = $fileName;
		$this->readGzipTar( $fileName );
	}

	/**
	 *	Reads an existing Tar Gzip File.
	 *	@access		private
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to read
	 *	@return		bool
	 */
	private function readGzipTar( $fileName )
	{
		$f = new File_Arc_Gzip( $fileName );
		$this->content = $f->readString();
		$this->parseTar();																			// Parse the TAR file
		return true;
	}

	/**
	 *	Write down the currently loaded Tar Gzip Archive.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Gzip Archive to save
	 *	@return		bool
	 */
	public function save( $fileName = false )
	{
		if( !$fileName )
		{
			if( !$this->fileName )
				throw new Exception( "No TGZ file name for saving given." );
			$fileName = $this->fileName;
		}
		$this->generateTar();												// Encode processed files into TAR file format
		$f = new File_Arc_Gzip( $fileName );
		$f->writeString( $this->content);
		return true;
	}
}
?>