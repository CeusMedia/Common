<?php
/**
 *	Builder for Tree Menu.
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
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.7
 */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\ADT\Tree\Menu\Collection as MenuCollection;
use CeusMedia\Common\Alg\Tree\Menu\Converter as MenuConverter;
use CeusMedia\Common\UI\HTML\Elements;
use CeusMedia\Common\UI\HTML\Tag;

/**
 *	Builder for Tree Menu.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.7
 */
class Menu
{
	protected $target	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$rootId			ID Tree Root
	 *	@param		string		$rootLabel		Label of Tree Root
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		public
	 *	@param		MenuCollection	$list	Tree Menu Structure
	 *	@return
	 */
	public function buildMenuFromMenuList( MenuCollection $list )
	{
		$tree		= $this->buildMenuRecursive( $list );
		$code		= Tag::create( 'div', $tree, $list->getAttributes( TRUE ) );
		return $code;
	}

	/**
	 *	Builds Layer Menu from OPML String.
	 *	@access		public
	 *	@param		string		$opml			OPML String
	 *	@param		string		$rootClass		CSS Class of root node
	 *	@return
	 */
	public function buildMenuFromOpml( $opml, $rootClass = NULL )
	{
		$list		= MenuConverter::convertFromOpml( $opml, $this->rootLabel, $rootClass );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Layer Menu from OPML File.
	 *	@access		public
	 *	@param		string		$fileName		URL of OPML File
	 *	@param		string		$rootClass		CSS Class of root node
	 *	@return
	 */
	public function buildMenuFromOpmlFile( $fileName, $rootClass = NULL )
	{
		$list	= MenuConverter::convertFromOpmlFile( $fileName, NULL, $rootClass );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Tree Menu from Tree Menu Structure.
	 *	@access		protected
	 *	@static
	 *	@param		MenuCollection		$list	Tree Menu Structure
	 *	@param		string				$parent	ID of parent Node
	 *	@param		array				$steps	List of Steps in Tree
	 *	@param		int					$level	Depth Level of Tree
	 *	@return		string
	 */
	protected function buildMenuRecursive( MenuCollection $tree, $level = 1 )
	{
		$list	= array();
		foreach( $tree->getChildren() as $child )
		{
			$class		= $child->getAttributes()->get( 'class' );
			$label		= $child->label;
			if( !empty( $child->url ) )
				$label	= Elements::Link( $child->url, $child->label, $class, $this->target );
			else
				$label	= Tag::create( 'span', $child->label );

			$sublist	= "";
			if( $child->hasChildren() )
				$sublist	= "\n".$this->buildMenuRecursive( $child, $level+1 );
			$classes	= array( 'level-'.$level );
			if( $child->hasChildren() )
				$classes[]	= "parent";
			$class		= implode( " ", $classes );
			$list[]		= Elements::ListItem( $label.$sublist, $level, array( 'class' => $class ) );
		}
		$list	= Elements::unorderedList( $list, $level );
		return $list;
	}

	public function setTarget( $target )
	{
		$this->target	= $target;
	}
}
