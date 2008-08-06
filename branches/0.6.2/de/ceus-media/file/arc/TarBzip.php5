<?php
import( 'de.ceus-media.file.arc.Tar' );
import( 'de.ceus-media.file.arc.Bzip' );
/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
 *	@package		file.arc
 *	@extends		File_Arc_Tar
 *	@uses			File_Arc_Bzip
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
 *	@package		file.arc
 *	@extends		File_Arc_Tar
 *	@uses			File_Arc_Bzip
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_Arc_TarBzip extends File_Arc_Tar
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to open
	 *	@return		void
	 */
	public function __construct( $fileName = false )
	{
		if( $fileName )
			$this->open( $fileName );
	}

	/**
	 *	Opens an existing Tar Bzip File and loads contents.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to open
	 *	@return		bool
	 */
	public function open( $fileName )
	{
		if( !file_exists( $fileName ) )																		// If the tar file doesn't exist...
			throw new RuntimeException( 'TBZ file "'.$fileName.'" is not existing.' );
		$this->fileName = $fileName;
		$this->readBzipTar( $fileName );
	}

	/**
	 *	Reads an existing Tar Bzip File.
	 *	@access		private
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to read
	 *	@return		bool
	 */
	private function readBzipTar( $fileName )
	{
		$f = new File_Arc_Bzip( $fileName );
		$this->content = $f->readString();
		$this->parseTar();																			// Parse the TAR file
		return true;
	}

	/**
	 *	Write down the currently loaded Tar Bzip Archive.
	 *	@access		public
	 *	@param		string		$fileName 		Name of Tar Bzip Archive to save
	 *	@return		bool
	 */
	public function save( $fileName = false )
	{
		if( !$fileName )
		{
			if( !$this->fileName )
				throw new RuntimeException( 'No TBZ file name for saving given.' );
			$fileName = $this->fileName;
		}
		$this->generateTar();												// Encode processed files into TAR file format
		$f = new File_Arc_Bzip( $fileName );
		$f->writeString( $this->content );
		return true;
	}
}
?>