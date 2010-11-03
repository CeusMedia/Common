<?php
/**
 *	Reading comma separated values with or without column headers.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		File.CSV
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Reading comma separated values with or without column headers.
 *	@category		cmClasses
 *	@package		File.CSV
 *	@extends		File_Reader
 *	@uses			Alg_Text_Unicoder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_CSV_Reader
{
	/**	@var		string		$fileName		File Name of CSV File */
	protected $fileName;
	/**	@var		bool		$withHeaders	Flag: use Column Headers in first line */
	protected $withHeaders			= false;
	/**	@var		string		$delimiter		Delimiter Sign */
	protected $delimiter		= ";";
	/**	@var		string		$enclosure		Enclosure Sign */
	protected $enclosure		= '"';
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of CSV File
	 *	@param		bool		$withHeaders	Flag: use Column Headers in first line
	 *	@param		string		$delimiter		Delimiter Sign
	 *	@return		void
	 */
	public function __construct( $fileName, $withHeaders = FALSE, $delimiter = NULL, $enclosure = NULL )
	{
		$this->fileName		= $fileName;
		$this->withHeaders	= $withHeaders;
		if( !is_null( $delimiter ) )
			$this->setDelimiter( $delimiter );
		if( !is_null( $enclosure ) )
			$this->setEnclosure( $enclosure );
	}

	/**
	 *	Returns columns headers if used.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumnHeaders()
	{
		if( !$this->withHeaders )
			throw new RuntimeException( 'Column headers not enabled' );
		$iterator	= new File_CSV_Iterator( $this->fileName, $this->delimiter );
		if( !$iterator->valid() )
			throw new RuntimeException( 'Invalid CSV file' );
		return $iterator->current();
	}

	/**
	 *	Returns the count of data rows.
	 *	@access		public
	 *	@return		int
	 */
	public function getRowCount()
	{
		$iterator	= new File_CSV_Iterator( $this->fileName, $this->delimiter );
		$counter	= 0;
		while( $iterator->next() )
			$counter++;
		if( $counter && $this->withHeaders )
			$counter--;
		return $counter;
	}

	/**
	 *	Returns the set delimiter.
	 *	@access		public
	 *	@return		string
	 */
	public function getDelimiter()
	{
		return $this->delimiter;
	}

	/**
	 *	Returns the set enclosure.
	 *	@access		public
	 *	@return		string
	 */
	public function getEnclosure()
	{
		return $this->enclosure;
	}

	/**
	 *	Sets the delimiter sign.
	 *	@access		public
	 *	@param		string		$delimiter		Delimiter Sign
	 *	@return		void
	 */
	public function setDelimiter( $delimiter )
	{
		$this->delimiter = $delimiter;
	}

	/**
	 *	Sets the enclosure sign.
	 *	@access		public
	 *	@param		string		$enclosure		Enclosure Sign
	 *	@return		void
	 */
	public function setEnclosure( $enclosure )
	{
		$this->enclosure = $enclosure;
	}
	
	/**
	 *	Reads data an returns an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$data		= array();
		$iterator	= new File_CSV_Iterator( $this->fileName, $this->delimiter );
		while( $iterator->next() )
			$data[]	= $iterator->current();
		if( $this->withHeaders )
			array_shift( $data );
		return $data;
	}
	
	/**
	 *	Reads data and returns an associative array if column headers are used.
	 *	@access		public
	 *	@return		array
	 */
	public function toAssocArray( $headerMap = array() )
	{
		$data		= array();
		$iterator	= new File_CSV_Iterator( $this->fileName, $this->delimiter );
		$keys		= $this->getColumnHeaders( $headerMap );
		if( $this->withHeaders )
			$iterator->next();
		while( $iterator->valid() )
		{
			$values	= $iterator->current();
			if( count( $keys ) != count( $values ) )
				throw new RuntimeException( 'Invalid line' );
			$data[]	= array_combine( $keys, $values );
		}
		return $data;
	}
}
?>