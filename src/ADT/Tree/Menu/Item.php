<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Tree\Menu;

/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree_Menu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Item extends Collection
{
	/**	@var		string		$url			URL of Item Link */
	public string $url;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of Item Link
	 *	@param		string		$label			Label of Item Link
	 *	@param		array		$attributes		Array of Item Attributes (classItem,classLink,classList)
	 *	@return		void
	 */
	public function __construct( string $url, string $label, array $attributes = [] )
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
	public function __get( string $key )
	{
		return $this->attributes->get( $key );
	}

	/**
	 *	Returns URL of Tree Menu List Item.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 *	Returns recursive Array Structure of this Item and its nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		return [
			'url'		=> $this->url,
			'label'		=> $this->label,
			'classLink'	=> $this->attributes->get( 'classLink' ),
			'classItem'	=> $this->attributes->get( 'classItem' ),
			'classList'	=> $this->attributes->get( 'classList' ),
			'children'	=> 	parent::toArray()
		];
	}
}
