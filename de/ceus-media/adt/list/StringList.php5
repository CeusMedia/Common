<?php
/**
 *	Implementation of a Strings List using an Array.
 *	@package		adt
 *	@subpackage		list
 *	@extends		Object
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Implementation of a Strings List using an Array.
 *	@package		adt
 *	@subpackage		list
 *	@extends		Object
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class StringList
{
	/**	@var		array	$_list		Array of Strings */
	var $_list = array ();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	function StringList ()
	{
		$this->_list = array ();
	}
	
	/**
	 *	Adds a String to the List.
	 *	@access		public
	 *	@param		string	$content		String to add
	 *	@return		void
	 */
	function add ($content)
	{
		$this->_list [] = $content;		
	}
	
	/**
	 *	Changes the content of a String in the List by its old content.
	 *	@access		public
	 *	@param		string	$content		old content of String
	 *	@param		string	$new		new content of String
	 *	@return		void
	 */
	function change ($content, $new)
	{
		if (in_array ($content, $this->toArray ()))
		{
			$index = $this->getIndex ($content);
			$this->set ($index, $new);
		}
	}
	
	/**
	 *	Clears all Strings in the List.
	 *	@access		public
	 *	@param		string	$content		old content of String
	 *	@param		string	$new		new content of String
	 *	@return		void
	 */
	function clear ()
	{
		$this->_list = array ();
	}
	
	/**
	 *	Returns a String by its Index.
	 *	@access		public
	 *	@param		int		$index		Index of String in the List
	 *	@return		string
	 */
	function get ($index)
	{
		if ($this->isIndex ($index)) return $this->_list[$index];
		return false;
	}
	
	/**
	 *	Return the Index of a given String in the List.
	 *	@access		public
	 *	@param		string	$content		content of String
	 *	@return		int
	 */
	function getIndex ($content)
	{
		return array_search( $content, $this->toArray () );
	}
	
	/**
	 *	Returns the Size of the List.
	 *	@access		public
	 *	@return		int
	 */
	function getSize ()
	{
		return count ($this->toArray ());	
	}
	
	/**
	 *	Indicates wheter a Index is given.
	 *	@access		public
	 *	@param		int		$index		Index of String
	 *	@return		bool
	 */
	function isIndex ($index)
	{
		return isset ($this->_list[$index]);
	}
	
	/**
	 *	Removes a String out of the List by its Index.
	 *	@access		public
	 *	@param		int		$index		Index of String
	 *	@return		void
	 */
	function remove ($index)
	{
		if ($this->isIndex ($index))
		{
			unset ($this->_list[$index]);
		}
	}
	
	/**
	 *	Sets a String by a Index in the List.
	 *	@access		public
	 *	@param		int		$index		Index of String
	 *	@param		string	$content		new content of String
	 *	@return		void
	 */
	function set ($index, $content)
	{
		if ($this->isIndex ($index))
			$this->_list[$index] = $content;
	}
	
	/**
	 *	Returns the List as Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray ()
	{
		return $this->_list;
	}
}
?>