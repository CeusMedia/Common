<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Implementation of a Section List using an Array.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Collection
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Collection;

use InvalidArgumentException;

/**
 *	Implementation of a Section List using an Array.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Collection
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SectionList
{
	/**	@var		array	$sections	List of Sections */
	protected $sections = [];

	/**	@var		array	$list		List of sectioned  Items */
	protected $list = [];

	/**
	 *	Adds an Entry to a Section of the List.
	 *	@access		public
	 *	@param		mixed		$entry			Entry to add
	 *	@param		string		$section		Section to add in
	 *	@return		void
	 */
	public function addEntry( $entry, string $section )
	{
		if( isset( $this->list[$section] ) && is_array( $this->list[$section] ) )
		 	if( in_array( $entry, $this->list[$section], TRUE ) )
				throw new InvalidArgumentException( 'Entry "'.$entry.'" is already in Section "'.$section.'".' );
		$this->list[$section][] = $entry;
	}

	/**
	 *	Adds a Section to List.
	 *	@access		public
	 *	@param		string		$section		Name of Section to add
	 *	@return		void
	 */
	public function addSection( string $section )
	{
		if( !isset( $this->list[$section] ) )
			$this->list[$section] = [];
	}

	/**
	 *	Clears all Sections and Entries in the List.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		$this->list = [];
	}

	/**
	 *	Returns the amount of Entries in a Sections.
	 *	@access		public
	 *	@param		string		$section		Section to count Entries for
	 *	@return		int
	 */
	public function countEntries( string $section ): int
	{
		return count( $this->getEntries( $section ) );
	}

	/**
	 *	Returns the amount of Sections in the List.
	 *	@access		public
	 *	@return		int
	 */
	public function countSections(): int
	{
		return count( $this->list );
	}

	/**
	 *	Returns a list of Entries of a Section in the List.
	 *	@access		public
	 *	@param		string		$section		Section to get Entries for
	 *	@return		array
	 */
	public function getEntries( string $section ): array
	{
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		return array_values( $this->list[$section] );
	}

	/**
	 *	Returns an entry in a section in the List.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getEntry( int $index, string $section )
	{
		if( !isset( $this->list[$section][$index] ) )
			throw new InvalidArgumentException( 'No Entry with Index '.$index.' in Section "'.$section.'" found.' );
		return $this->list[$section][$index];
	}

	/**
	 *	Return the Index of a given String in the List.
	 *	@access		public
	 *	@param		mixed		$entry			Content String of Entry
	 *	@param		string|NULL	$section		Section of Entry
	 *	@return		int
	 */
	public function getIndex( $entry, ?string $section = NULL ): int
	{
		if( !$section )
			$section	= $this->getSectionOfEntry( $entry );
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		$index	= array_search( $entry, $this->list[$section], TRUE );
		if( FALSE === $index )
			return -1;
		return $index;
	}

	/**
	 *	Returns the Section List as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getList(): array
	{
		return $this->list;
	}

	/**
	 *	Return the Sections of an entry if available.
	 *	@access		public
	 *	@param		mixed		$entry			Entry to get Section for
	 *	@return		string
	 */
	public function getSectionOfEntry( $entry ): string
	{
		foreach( $this->getSections() as $section )
			if( in_array( $entry, $this->list[$section], TRUE ) )
				return $section;
		throw new InvalidArgumentException( 'Entry "'.$entry.'" not found in any Section.' );
	}

	/**
	 *	Returns a list of Sections.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections(): array
	{
		return array_keys( $this->list );
	}

	/**
	 *	Removes an entry in a section in the List.
	 *	@access		public
	 *	@param		mixed		$entry			Entry to remove
	 *	@param		string|NULL	$section		Section of Entry
	 *	@return		void
	 *	@throws		InvalidArgumentException	if entry is not existing
	 */
	public function removeEntry( $entry, ?string $section = NULL )
	{
		if( !$section )
			$section	= $this->getSectionOfEntry( $entry );
		$index	= $this->getIndex( $entry, $section );
		if( $index === -1 )
			throw new InvalidArgumentException( 'Entry "'.$entry.'" not found in Section "'.$section.'".' );
		unset( $this->list[$section][$index] );
	}

	/**
	 *	Removes a section in the List.
	 *	@access		public
	 *	@param		string		$section		Section to remove
	 *	@return		void
	 */
	public function removeSection( string $section )
	{
		if( !isset( $this->list[$section] ) )
			throw new InvalidArgumentException( 'Invalid Section "'.$section.'".' );
		unset( $this->list[$section] );
	}
}
