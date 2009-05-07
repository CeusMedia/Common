<?php
/**
 *	Implementation of a Section List using an Array.
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
 *	@package		adt.list
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Implementation of a Section List using an Array.
 *	@package		adt.list
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class ADT_List_SectionList
{
	/**	@var		array	$sections	List of Sections */
	protected $sections = array();
	/**	@var		array	$list		List of sectioned  Items */
	protected $list = array();
	
	/**
	 *	Adds an Entry to a Section of the List.
	 *	@access		public
	 *	@param		mixed		$entry			Entry to add
	 *	@param		string		$section		Section to add in
	 *	@return		void
	 */
	public function addEntry( $entry, $section )
	{
		if( isset( $this->list[$section] ) && is_array( $this->list[$section] ) && in_array( $entry, $this->list[$section] ) )
			throw new InvalidArgumentException( 'Entry "'.$entry.'" is already in Section "'.$section.'".' );
		$this->list[$section][] = $entry;
	}

	/**
	 *	Adds a Section to List.
	 *	@access		public
	 *	@param		string		$section		Name of Section to add
	 *	@return		void
	 */
	public function addSection( $section )
	{
		if( !isset( $this->list[$section] ) )
			$this->list[$section] = array();
	}
		
	/**
	 *	Clears all Sections and Entries in the List.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		$this->list = array();
	}

	/**
	 *	Returns the amount of Entries in a Sections.
	 *	@access		public
	 *	@param		string		$section		Section to count Entries for
	 *	@return		int
	 */
	public function countEntries( $section )
	{
		return count( $this->getEntries( $section ) );
	}
	
	/**
	 *	Returns the amount of Sections in the List.
	 *	@access		public
	 *	@return		int
	 */
	public function countSections()
	{
		return count( $this->list );
	}

	/**
	 *	Returns an entry in a section in the List.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getEntry( $index, $section )
	{
		if( !isset( $this->list[$section][$index] ) )
			throw new InvalidArgumentException( 'No Entry with Index '.$index.' in Section "'.$section.'" found.' );
		return $this->list[$section][$index];
	}
	
	/**
	 *	Returns a list of Entries of a Section in the List.
	 *	@access		public
	 *	@param		string		$section		Section to get Entries for
	 *	@return		array
	 */
	public function getEntries( $section )
	{
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		return array_values( $this->list[$section] );
	}

	/**
	 *	Return the Index of a given String in the List.
	 *	@access		public
	 *	@param		string		$content		Content String of Entry
	 *	@param		string		$section		Section of Entry
	 *	@return		int
	 */
	public function getIndex( $entry, $section = NULL )
	{
		if( !$section )
			$section	= $this->getSectionOfEntry( $entry );
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		return array_search( $entry, $this->list[$section] );
	}
	
	/**
	 *	Returns the Section List as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getList()
	{
		return $this->list;
	}

	/**
	 *	Return the Sections of an entry if available.
	 *	@access		public
	 *	@param		string		$entry			Entry to get Section for
	 *	@return		string
	 */
	public function getSectionOfEntry( $entry )
	{
		foreach( $this->getSections() as $section )
			if( in_array( $entry, $this->list[$section] ) )
				return $section;
		throw new InvalidArgumentException( 'Entry "'.$entry.'" not found in any Section.' );
	}
	
	/**
	 *	Returns a list of Sections.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections()
	{
		return array_keys( $this->list );
	}

	/**
	 *	Removes an entry in a section in the List.
	 *	@access		public
	 *	@param		string		$entry			Entry to remove
	 *	@param		string		$section		Section of Entry
	 *	@return		void
	 */
	public function removeEntry( $entry, $section = NULL )
	{
		if( !$section )
			$section	= $this->getSectionOfEntry( $entry );
		$index	= $this->getIndex( $entry, $section );
		if( $index === FALSE )
			throw new InvalidArgumentException( 'Entry "'.$entry.'" not found in Section "'.$section.'".' );
		unset( $this->list[$section][$index] );
	}

	/**
	 *	Removes a section in the List.
	 *	@access		public
	 *	@param		string		$section		Section to remove
	 *	@return		void
	 */
	public function removeSection( $section )
	{
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		unset( $this->list[$section] );
	}
}
?>