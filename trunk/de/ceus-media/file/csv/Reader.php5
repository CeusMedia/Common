<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Reading comma separated values with or without column headers.
 *	@package		file.csv
 *	@extends		File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Reading comma separated values with or without column headers.
 *	@package		file.csv
 *	@extends		File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_CSV_Reader
{
	/**	@var		string		$fileName		Flag: use ColumnHeaders in first line */
	protected $fileName;
	/**	@var		bool		$headers		Flag: use ColumnHeaders in first line */
	protected $headers			= false;
	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ";";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of CSV File
	 *	@param		bool		$headers		Flag: use column headers
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $fileName, $headers = false, $separator = NULL )
	{
		$this->fileName	= $fileName;
		$this->headers	= $headers;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Returns columns headers if used.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumnHeaders()
	{
		$keys	= array();
		if( $this->headers )
		{
			$file	= new File_Reader( $this->fileName );
			$lines	= $file->readArray();
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
	public function getRowCount()
	{
		$file	= new File_Reader( $this->fileName );
		$lines	= $file->readArray();
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
	public function getSeparator()
	{
		return $this->separator;
	}

	/**
	 *	Sets the separator sign.
	 *	@access		public
	 *	@param		string	separator		Separator Sign
	 *	@return		void
	 */
	public function setSeparator( $separator )
	{
		$this->separator = $separator;
	}
	
	/**
	 *	Reads data an returns an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$data	= array();
		$file	= new File_Reader( $this->fileName );
		$lines	= $file->readArray();
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
	public function toAssocArray()
	{
		$data = array();
		if( $this->headers )
		{
			$c = 0;
			$file	= new File_Reader( $this->fileName );
			$lines	= $file->readArray();
			$line	= array_shift( $lines );
			$keys	= explode( $this->separator, trim( $line ) );
			foreach( $lines as $line )
			{
				if( !trim( $line ) )
					continue;
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