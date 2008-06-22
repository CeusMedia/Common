<?php
import ("de.ceus-media.file.csv.csvReader");
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@package		file.csv
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@package		file.csv
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_CSV_Writer
{
	/**	@var		string		$fileName		Flag: use ColumnHeaders in first line */
	protected $fileName;
	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ",";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of CSV File
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $fileName, $separator = NULL )
	{
		$this->fileName	= $fileName;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Saves an 2 dimensional array with or without column headers.
	 *	@access		public
	 *	@param		array		$data			2 dimensional array of data
	 *	@param		array		$headers		List of Column Headers
	 *	@return		bool
	 */
	public function write( $data, $headers = array() )
	{
		$output = array ();
		if( $headers )
		{
			$output[] = implode( $this->separator, $headers );
		}
		foreach( $data as $line )
		{
			$line = implode( $this->separator, $line );
			$output[] = $line;
		}
		$file	= new File_Writer( $this->fileName );
		return $file->writeArray( $output );
	}
}
?>