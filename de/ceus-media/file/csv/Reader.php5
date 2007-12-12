<?php
import( 'de.ceus-media.file.File' );
/**
 *	Reading comma separated values with or without column headers.
 *	@package		file.csv
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Reading comma separated values with or without column headers.
 *	@package		file.csv
 *	@extends		File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_CSV_Reader extends File
{
	/**	@var	bool	$headers		Flag: use ColumnHeaders in first line */
	protected $headers = false;
	/**	@var	string	$separator		Separator Sign */
	protected $separator = ";";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of CSV File
	 *	@param		bool		$headers		Flag: use column headers
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $fileName, $headers = false, $separator = "," )
	{
		parent::__construct( $fileName );
		$this->headers	= $headers;
		$this->setSeparator( $separator );
	}

	/**
	 *	Returns columns headers if used.
	 *	@access		public
	 *	@return		array
	 */
	function getColumnHeaders()
	{
		$keys	= array();
		if( $this->headers )
		{
			$lines	= $this->readArray();
			$line	= array_shift( $lines );
			$keys	= explode( $this->separator, trim( $line ) );
		}
		return $keys;
	}

	/**
	 *	Returns the count of data rows.
	 *	@access		public
	 *	@return		int
	 */
	function getRowCount()
	{
		$lines	= $this->readArray();
		$count	= count( $lines );
		if( $this->headers )
			$count--;
		return $count;
	}

	/**
	 *	Returns the set separator.
	 *	@access		public
	 *	@return		string
	 */
	function getSeparator()
	{
		return $this->separator;
	}

	/**
	 *	Sets the separator sign.
	 *	@access		public
	 *	@param		string	separator		Separator Sign
	 *	@return		void
	 */
	function setSeparator( $separator )
	{
		$this->separator = $separator;
	}
	
	/**
	 *	Reads data an returns an array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		$data	= array();
		$lines	= $this->readArray();
		if( $this->headers )
			array_shift( $lines );
		foreach( $lines as $line )
		{
			$values	= explode( $this->separator, trim( $line ) );
			$data[]	= $values;
		}
		return $data;
	}
	
	/**
	 *	Reads data and returns an associative array if column headers are used.
	 *	@access		public
	 *	@return		array
	 */
	function toAssocArray()
	{
		$data = array();
		if( $this->headers )
		{
			$c = 0;
			$lines	= $this->readArray();
			$line	= array_shift( $lines );
			$keys	= explode( $this->separator, trim( $line ) );
			foreach( $lines as $line )
			{
				$c++;
				$values	= explode( $this->separator, trim( $line ) );
				if( count( $values ) != count( $keys ) )
					throw new Exception( "CSV File is invalid in Line ".($c+1)."." );
				$data[]	= array_combine( $keys, $values );
			}
		}
		return $data;
	}
}
?>