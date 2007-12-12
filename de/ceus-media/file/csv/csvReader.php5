<?php
import( 'de.ceus-media.file.File' );
/**
 *	Reading comma separated values with or without column headers.
 *	@package	file
 *	@extends	File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Reading comma separated values with or without column headers.
 *	@package	file
 *	@extends	File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class csvReader extends File
{
	/**	@var	string	_headers		Flag: use ColumnHeaders in first line */
	var $_headers = false;
	/**	@var	string	_separator	Separator Sign */
	var $_separator = ";";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		File name of CSV File
	 *	@param		bool		headers		Flag: use column headers
	 *	@param		string	separator	Separator sign
	 *	@return		void
	 */
	public function __construct( $filename, $headers = false, $separator = ";" )
	{
		parent::__construct( $filename );
		$this->_headers	= $headers;
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
		if( $this->_headers )
		{
			$lines	= $this->readArray();
			$line		= array_shift( $lines );
			$keys	= explode( $this->_separator, trim( $line ) );
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
		if( $this->_headers )
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
		return $this->_separator;
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
		foreach( $lines as $line )
		{
			$values	= explode( $this->_separator, trim( $line ) );
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
		if( $this->_headers )
		{
			$c = 0;
			$lines	= $this->readArray();
			$line		= array_shift( $lines );
			$keys	= explode( $this->_separator, trim( $line ) );
			foreach( $lines as $line )
			{
				$values	= explode( $this->_separator, trim( $line ) );
				if( count( $values ) != count( $keys ) )
					trigger_error( "CSV File is invalid.", E_USER_ERROR );
				$data[]	= $this->array_combine( $keys, $values );
			}
		}
		return $data;
	}

	/**
	 *	Sets the separator sign.
	 *	@access		public
	 *	@param		string	separator		Separator Sign
	 *	@return		void
	 */
	function setSeparator( $separator )
	{
		$this->_separator = $separator;
	}

	/**
	 *	Combines to arrays to an associative array.
	 *	@access		public
	 *	@param		array	keys			Array of keys
	 *	@param		array	values		Array of values
	 *	@return		array
	 */
	function array_combine( $keys, $values )
	{
		$output	= array();
		if( count( $keys ) != count( $values ) )
			return $output;
		if( count( $keys ) <= 0)
			return $output;
		$keys	= array_values( $keys );
		$values	= array_values( $values );
		for( $i = 0; $i < count( $keys ); $i++ )
			$output[$keys[$i]] = $values[$i];
		return $output;
	}
}
?>