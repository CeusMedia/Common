<?php
/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		adt.tree.menu
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		0.1
 */
import( 'de.ceus-media.adt.tree.menu.List' );
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *	@package		adt.tree.menu
 *	@extends		ADT_Tree_Menu_List
 *	@uses			ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		0.1
 */
class ADT_Tree_Menu_Item extends ADT_Tree_Menu_List
{
	/**	@var		string		$url			URL of Item Link */
	public $url			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of Item Link
	 *	@param		string		$label			Label of Item Link
	 *	@param		array		$attributes		Array of Item Attributes (classItem,classLink,classList)
	 *	@return		void
	 */
	public function __construct( $url, $label, $attributes = array() )
	{
		parent::__construct( $label, $attributes );
		$this->url			= $url;
	}

	/**
	 *	Returns Attribute Value by a Key if set or NULL.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@return		mixed
	 */
	public function __get( $key )
	{
		return $this->attributes->get( $key );
	}
	
	/**
	 *	Returns URL of Tree Menu List Item.
	 *	@access		public
	 *	@return		array
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 *	Returns recursive Array Structure of this Item and its nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array	= array(
			'url'		=> $this->url,
			'label'		=> $this->label,
			'classLink'	=> $this->attributes->get( 'classLink' ),
			'classItem'	=> $this->attributes->get( 'classItem' ),
			'classList'	=> $this->attributes->get( 'classList' ),
			'children'	=> 	parent::toArray()
		);
		return $array;
	}
}
?>