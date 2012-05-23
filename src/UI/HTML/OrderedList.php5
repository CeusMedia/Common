<?php
/**
 *	Builder of HTML Ordered List Elements.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder of HTML Ordered List Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_OrderedList extends UI_HTML_Abstract
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$items		List of Item Elements or Strings
	 *	@param		array		$attributes	Map of Attributes
	 *	@return		void
	 */
	public function __construct( $items = NULL, $attributes = NULL )
	{
		if( !is_null( $items ) )
			$this->addItems( $items );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}
	
	/**
	 *	Adds an Item.
	 *	@access		public
	 *	@param		UI_HTML_ListItem|string	$item	List Item Element or String
	 *	@return		void
	 */
	public function addItem( $item )
	{
		$this->listItems[]	= $item;
	}
	
	/**
	 *	Adds an Item.
	 *	@access		public
	 *	@param		array		$items		List of List Item Elements or Strings
	 *	@return		void
	 */
	public function addItems( $items )
	{
		if( $items instanceof UI_HTML_Buffer ) 
			$this->addItem( $items->render() );
		else
			foreach( $items as $item )
				$this->addItem( $item );
	}

	/**
	 *	Returns rendered List Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$list	= array();
		foreach( $this->listItems as $item )
			$list[]	= $this->renderInner( $item );
		return UI_HTML_Tag::create( "ol", join( $list ), $this->getAttributes() );
	}
}
?>