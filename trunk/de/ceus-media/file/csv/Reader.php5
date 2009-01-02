<?php
/**
 *	Reading comma separated values with or without column headers.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		file.csv
 *	@extends		File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.alg.StringUnicoder' );
/**
 *	Reading comma separated values with or without column headers.
 *	@package		file.csv
 *	@extends		File_Reader
 *	@uses			Alg_StringUnicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class File_CSV_Reader
{
	/**	@var		string		$fileName		File Name of CSV File */
	protected $fileName;
	/**	@var		bool		$withHeaders	Flag: use Column Headers in first line */
	protected $withHeaders			= false;
	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ";";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of CSV File
	 *	@param		bool		$withHeaders	Flag: use Column Headers in first line
	 *	@param		string		$separator		Separator Sign
	 *	@return		void
	 */
	public function __construct( $fileName, $withHeaders = FALSE, $separator = NULL )
	{
		$this->fileName		= $fileName;
		$this->withHeaders	= $withHeaders;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Returns columns headers if used.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumnHeaders( $headerMap = array() )
	{
		$keys	= array();
		if( !$this->withHeaders )
			throw new RuntimeException( 'CSV is not using Column Headers.' );
		$file	= new File_Reader( $this->fileName );
		$lines	= $file->readArray();
		$line	= array_shift( $lines );
		$list	= explode( $this->separator, trim( $line ) );
		$keys	= array();
		foreach( $list as $key )
			$keys[]	= preg_replace( '@(^")|("$)@', "", $key );
		if( $headerMap )
		{
			foreach( $headerMap as $key => $value )
			{
				if( !$value )
					continue;
				$pos	= array_search( $key, $keys );
				if( is_int( $pos ) && $pos >= 0 )
					$keys[$pos] = $value;
			}
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
		if( $this->withHeaders )
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
		if( $this->withHeaders )
			array_shift( $lines );
		foreach( $lines as $line )
		{
			$values	= $this->getLineValues( $line );
			$data[]	= $values;
		}
		return $data;
	}
	
	/**
	 *	Reads data and returns an associative array if column headers are used.
	 *	@access		public
	 *	@return		array
	 */
	public function toAssocArray( $headerMap = array() )
	{
		$data = array();
		if( !$this->withHeaders )
			throw new RuntimeException( 'CSV is not using Column Headers.' );

		$c = 0;
		$file	= new File_Reader( $this->fileName );
		$lines	= $file->readArray();
		$keys	= $this->getColumnHeaders( $headerMap );
		array_shift( $lines );
		foreach( $lines as $line )
		{
			if( !trim( $line ) )
				continue;
			$c++;
			$values	= $this->getLineValues( $line );
			if( count( $values ) != count( $keys ) )
				throw new Exception( "CSV File is invalid in Line ".($c+1)."." );
			$data[]	= array_combine( $keys, $values );
		}
		return $data;
	}
	
	protected function getLineValues( $line, $forceUnicode = FALSE )
	{
		$values	= explode( $this->separator, trim( $line ) );
		foreach( $values as $key => $value )
		{
			$value	= preg_replace( '@(^")|("$)@', "", $value );
			$value	= trim( $value );
			if( $forceUnicode )
				$value	= Alg_StringUnicoder::convertToUnicode( $value );
			$values[$key]	= $value;
		}
		return $values;
	}
}
?>