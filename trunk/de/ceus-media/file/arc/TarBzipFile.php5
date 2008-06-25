<?php
import( 'de.ceus-media.file.arc.TarFile' );
import( 'de.ceus-media.file.arc.BzipFile' );
/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
 *	@package		file
 *	@subpackage		arc
 *	@extends		TarFile
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Tar Bzip File allows creation and manipulation of bzipped tar archives.
 *	@package		file
 *	@subpackage		arc
 *	@extends		TarFile
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class TarBzipFile extends TarFile
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
			throw new Exception( "TBZ file '".$fileName."' is not existing." );
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
		$f = new BzipFile( $fileName );
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
				throw new Exception( "No TBZ file name for saving given." );
			$fileName = $this->fileName;
		}
		$this->generateTar();												// Encode processed files into TAR file format
		$f = new BzipFile( $fileName );
		$f->writeString( $this->content );
		return true;
	}
}
?>