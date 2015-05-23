<?php
/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.Tree.Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		$Id$
 */
/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *	@category		cmClasses
 *	@package		ADT.Tree.Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		$Id$
 */
class ADT_Tree_Menu_List
{
	/**	@var		string		$label			Label of Item Link */
	public $label				= NULL;
	/**	@var		array		$attributes		Array of Item Attributes (classItem,classLink,classList) */
	public $attributes			= NULL;
	/**	@var		array		$children		List of nested Tree Menu Items */
	public $children			= array();
	
	public $defaultAttributes	= array(
		'class'		=> "option",
		'default'	=> FALSE,
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$label			Label of Item Link
	 *	@param		array		$attributes		Array of Item Attributes (classItem,classLink,classList)
	 *	@return		void
	 */
	public function __construct( $label = NULL, $attributes = array() )
	{
		$this->setLabel( $label );
		$attributes			= array_merge( $this->defaultAttributes, $attributes );
		$this->attributes	= new ADT_List_Dictionary( $attributes );
	}

	/**
	 *	Adds a nested Tree Menu Item to this Tree Menu List.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_Item	$child		Nested Tree Menu Item Data Object
	 *	@return		void
	 */
	public function addChild( ADT_Tree_Menu_List $child )
	{
		$this->children[]	= $child;
	}

	/**
	 *	Indicated whether there are nested Tree Menu Items.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren()
	{
		return (bool) count( $this->children );
	}

	/**
	 *	Returns Value of a set Attribute by its Key.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@return		string
	 */
	public function getAttribute( $key )
	{
		return $this->attributes->get( $key );
	}
	
	/**
	 *	Returns all set Attributes as Dictionary or Array.
	 *	@access		public
	 *	@param		bool		$asArray		Return Array instead of Dictionary
	 *	@return		mixed
	 */
	public function getAttributes( $asArray = FALSE )
	{
		if( $asArray )
			return $this->attributes->getAll();
		return $this->attributes;
	}
	
	/**
	 *	Returns List of nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 *	Returns Label of Tree Menu List.
	 *	@access		public
	 *	@return		string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 *	Sets an Attribute.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@param		string		$value			Attribute Value
	 *	@return		string
	 */
	public function setAttribute( $key, $value )
	{
		$this->attributes->set( $key, $value );
	}

	/**
	 *	Sets Attributes from Map Array or Dictionary.
	 *	@access		public
	 *	@param		mixed		$array			Map Array or Dictionary of Attributes to set
	 *	@return		void
	 */
	public function setAttributes( $array )
	{
		if( is_a( $array, 'ADT_List_Dictionary' ) )
			$array	= $array->getAll();
		foreach( $array as $key => $value )
			$this->attributes->set( $key, $value );
	}
	/**
	 *	Sets Label of Tree Menu List.
	 *	@access		public
	 *	@return		string
	 */
	public function setLabel( $label )
	{
		$this->label	= $label;
	}

	/**
	 *	Returns recursive Array Structure of this List and its nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$children	= array();
		foreach( $this->children as $child )
			$children[]	= $child->toArray();
		return $children;
	}
}
?>
