<?php
/**
 *	Implementation of a Section List using an Array.
 *	@package		adt.list
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Implementation of a Section List using an Array.
 *	@package		adt.list
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
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
	 *	@param		mixed	$entry		Entry to add
	 *	@param		string	$section		Section to add in
	 *	@return		void
	 */
	public function addEntry( $entry, $section )
	{
		if( !( is_array( $this->list[$section] ) && in_array( $entry, $this->list[$section] ) ) )
			$this->list[$section][] = $entry;
	}

	/**
	 *	Adds a Section to List.
	 *	@access		public
	 *	@param		string	$section		Name of Section to add
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
	 *	Return the Index of a given String in the List.
	 *	@access		public
	 *	@param		string	$content		content of String
	 *	@return		int
	 */
	public function getIndex( $entry, $section = false )
	{
		if( $section )
			return array_search( $entry, $this->list[$section] );
		else if( $section = $this->getSectionOfEntry( $entry ) )
			return $this->getIndex( $entry, $section );			
		return -1;
	}

	/**
	 *	Returns an entry in a section in the List.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getEntry( $index, $section )
	{
		if( isset( $this->list[$section][$index] ) )
			return $this->list[$section][$index];
		return NULL;
	}
	
	/**
	 *	Returns a list of Entries of a Section in the List.
	 *	@access		public
	 *	@return		array
	 */
	public function getEntries( $section )
	{
		if( isset( $this->list[$section] ) )
			return array_values( $this->list[$section] );
		return array();
	}

	/**
	 *	Return the Sections of an entry if available.
	 *	@access		public
	 *	@param		string	$entry		Entry to get section for
	 *	@return		int
	 */
	public function getSectionOfEntry( $entry )
	{
		foreach( $this->getSections() as $section )
			if( -1 < ( $pos = $this->getIndex( $entry, $section ) ) )
				return $section;
		return NULL;
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
	 *	Returns the amount of Entries in a Sections.
	 *	@access		public
	 *	@return		int
	 */
	public function getSectionSize( $section )
	{
		return count( $this->list[$section] );
	}
	
	/**
	 *	Returns the amount of Sections in the List.
	 *	@access		public
	 *	@return		int
	 */
	public function getSectionsSize()
	{
		return count( $this->list );
	}

	/**
	 *	Removes an entry in a section in the List.
	 *	@access		public
	 *	@return		void
	 */
	public function removeEntry( $entry, $section = false )
	{
		if( $section )
		{
			if( in_array( $entry, $this->list[$section] ) )
				unset( $this->list[$section][$this->getIndex( $entry, $section )] );
		}
		else if( $section = $this->getSectionOfEntry( $entry ) )
			return $this->removeEntry( $entry, $section );
	}

	/**
	 *	Removes a section in the List.
	 *	@access		public
	 *	@return		void
	 */
	public function removeSection( $section)
	{
		if( in_array( $section, array_keys( $this->list ) ) )
			unset( $this->list[$section] );
	}
	
	/**
	 *	Returns the Section List as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->list;
	}
	
	/**
	 *	Returns a representative String of Section List.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		foreach( $this->list as $section => $list )
		{
			$code .= "[".$section."]<br/>";
			foreach( $list as $entry )
				$code .= $entry."<br/>";
		}
		return $code;
	}
}
?>