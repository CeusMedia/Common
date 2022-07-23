<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Tree\Menu;

use CeusMedia\Common\ADT\Collection\Dictionary;

/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Collection
{
	/**	@var		string			$label			Label of Item Link */
	public $label					= NULL;

	/**	@var		Dictionary		$attributes		Array of Item Attributes (classItem,classLink,classList) */
	public $attributes				= NULL;

	/**	@var		array			$children		List of nested Tree Menu Items */
	public $children				= array();

	public $defaultAttributes		= array(
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
	public function __construct( string $label, array $attributes = [] )
	{
		$this->setLabel( $label );
		$attributes			= array_merge( $this->defaultAttributes, $attributes );
		$this->attributes	= new Dictionary( $attributes );
	}

	/**
	 *	Adds a nested Tree Menu Item to this Tree Menu List.
	 *	@access		public
	 *	@param		Item		$child		Nested Tree Menu Item Data Object
	 *	@return		void
	 */
	public function addChild( Collection $child )
	{
		$this->children[]	= $child;
	}

	/**
	 *	Returns Value of a set Attribute by its Key.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@return		string
	 */
	public function getAttribute( string $key ): string
	{
		return $this->attributes->get( $key );
	}

	/**
	 *	Returns all set Attributes as Dictionary or Array.
	 *	@access		public
	 *	@param		bool		$asArray		Return Array instead of Dictionary
	 *	@return		Dictionary|array
	 */
	public function getAttributes( bool $asArray = FALSE )
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
	public function getChildren(): array
	{
		return $this->children;
	}

	/**
	 *	Returns Label of Tree Menu List.
	 *	@access		public
	 *	@return		string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 *	Indicated whether there are nested Tree Menu Items.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren(): bool
	{
		return count( $this->children ) > 0;
	}

	/**
	 *	Sets an Attribute.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@param		string		$value			Attribute Value
	 *	@return		bool
	 */
	public function setAttribute( string $key, string $value ): bool
	{
		return $this->attributes->set( $key, $value );
	}

	/**
	 *	Sets Attributes from Map Array or Dictionary.
	 *	@access		public
	 *	@param		Dictionary|array	$array			Map Array or Dictionary of Attributes to set
	 *	@return		void
	 */
	public function setAttributes( $array )
	{
		if( $array instanceof Dictionary )
			$array	= $array->getAll();
		foreach( $array as $key => $value )
			$this->attributes->set( $key, $value );
	}

	/**
	 *	Sets Label of Tree Menu List.
	 *	@access		public
	 *	@param		string		$label
	 *	@return		self
	 */
	public function setLabel( string $label ): self
	{
		$this->label	= $label;
		return $this;
	}

	/**
	 *	Returns recursive Array Structure of this List and its nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		$children	= array();
		foreach( $this->children as $child )
			$children[]	= $child->toArray();
		return $children;
	}
}
