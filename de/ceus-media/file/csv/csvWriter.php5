<?php
import ("de.ceus-media.file.csv.csvReader");
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@package		file
 *	@extends		csvReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@package		file
 *	@extends		csvReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class csvWriter extends csvReader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		File name of CSV File
	 *	@param		bool		$headers		Flag: use column headers
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $filename, $headers = false, $separator = ";" )
	{
		parent::__construct( $filename, $headers, $separator );
	}
	
	/**
	 *	Saves an 2 dimensional array with or without column headers.
	 *	@access		public
	 *	@param		array		$lines			2 dimensional array of data
	 *	@return		bool
	 */
	function save( $lines )
	{
		$output = array ();
		if( $this->_headers )
		{
			$output[] = implode( $this->_separator, $this->getColumnHeaders() );
		}
		foreach( $lines as $line )
		{
			$line = implode( $this->_separator, $line );
			$output[] = $line;
		}
		return $this->writeArray( $output );
	}

}
?>