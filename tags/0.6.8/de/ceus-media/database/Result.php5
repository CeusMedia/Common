<?php
/**
 *	Result Object for Database Result.
 *	All Result Rows can be iterated like an Array.
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
 *	@package		database
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Result Object for Database Result.
 *	All Result Rows can be iterated like an Array.
 *	@category		cmClasses
 *	@package		database
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
class Database_Result implements Countable, Iterator
{
	/**	@var		int			$cursor			Internal Pointer to current Row */
	protected $cursor;
	/**	@var		array		$rows			List of all fetched Rows */
	public $rows;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->cursor = 0;
		$this->rows = array();
	}

	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->rows );
	}
	
	/**
	 *	Returns current Value.
	 *	@access		public
	 *	@return		mixed
	 */
	public function current()
	{
		return $this->rows[$this->key()];		
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchArray()
	{
		if( isset( $this->rows[$this->cursor] ) )
		{
			$row = $this->rows[$this->cursor];
			return $row->getPairs();
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextArray()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			$row = $this->rows[$this->cursor-1];
			return $row->getPairs();
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextObject()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			return $this->rows[$this->cursor-1];
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextRow()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			$row = $this->rows[$this->cursor-1];
			return $row->getValues();
		}
		else return FALSE;
	}

	/**
	 *	Returns found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchObject()
	{
		if( isset( $this->rows[$this->cursor] ) )
			return $this->rows[$this->cursor];
		return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchRow()
	{
		if( isset( $this->rows[$this->cursor] ) )
		{
			$row = $this->rows[$this->cursor];
			return $row->getValues();
		}
		else return FALSE;
	}

	/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		mixed
	 */
	public function key()
	{
		$keys	= array_keys( $this->rows );
		return $keys[$this->cursor];
	}
	
	/**
	 *	Selects next Pair.
	 *	@access		public
	 *	@return		void
	 */
	public function next()
	{
		$this->cursor++;
	}	

	/**
	 *	Returns the number found rows in this result.
	 *	@access		public
	 *	@return		int
	 */
	public function recordCount()
	{
		return count( $this->rows );
	}

	/**
	 *	Resets Pair Pointer.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->cursor	= 0;
	}
	
	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		bool
	 */
	public function valid()
	{
		return $this->cursor < $this->count();
	}	
}
?>