<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\ADT\Tree\Menu\Collection as TreeMenuCollection;
use CeusMedia\Common\Alg\Tree\Menu\Converter as TreeMenuConverter;
use CeusMedia\Common\UI\HTML\Elements as HtmlElements;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Builder for Tree Menu.
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
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Builder for Tree Menu.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Menu
{
	protected ?string $target	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		public
	 *	@param		TreeMenuCollection	$list	Tree Menu Structure
	 *	@return     string
	 */
	public function buildMenuFromMenuList( TreeMenuCollection $list ): string
	{
		$tree		= $this->buildMenuRecursive( $list );
		return HtmlTag::create( 'div', $tree, $list->getAttributes( TRUE ) );
	}

	/**
	 *	Builds Layer Menu from OPML String.
	 *	@access		public
	 *	@param		string		    $opml			OPML String
	 *	@param		string		    $rootLabel		Label of Top Tree Menu List
	 *	@param		string|NULL		$rootClass		CSS Class of root node
	 *	@return     string
	 */
	public function buildMenuFromOpml( string $opml, string $rootLabel = '', ?string $rootClass = NULL ): string
	{
		$list		= TreeMenuConverter::convertFromOpml( $opml, $rootLabel, $rootClass );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Layer Menu from OPML File.
	 *	@access		public
	 *	@param		string		    $fileName		URL of OPML File
	 *	@param		string		    $rootLabel		Label of Top Tree Menu List
	 *	@param		string|NULL		$rootClass		CSS Class of root node
	 *	@return     string
	 */
	public function buildMenuFromOpmlFile( string $fileName, string $rootLabel = '', ?string $rootClass = NULL ): string
	{
		$list	= TreeMenuConverter::convertFromOpmlFile( $fileName, $rootLabel, $rootClass );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Tree Menu from Tree Menu Structure.
	 *	@access		protected
	 *	@static
	 *	@param		TreeMenuCollection	$tree   	Tree Menu Structure
	 *	@param		int					$level	    Depth Level of Tree
	 *	@return		string
	 */
	protected function buildMenuRecursive( TreeMenuCollection $tree, int $level = 1 ): string
	{
		$list	= [];
		foreach( $tree->getChildren() as $child ){
			$class		= $child->getAttributes()->get( 'class' );
			$label		= $child->label;
			if( !empty( $child->url ) )
				$label	= HtmlElements::Link( $child->url, $child->label, $class, $this->target );
			else
				$label	= HtmlTag::create( 'span', $child->label );

			$sublist	= "";
			if( $child->hasChildren() )
				$sublist	= "\n".$this->buildMenuRecursive( $child, $level+1 );
			$classes	= array( 'level-'.$level );
			if( $child->hasChildren() )
				$classes[]	= "parent";
			$class		= implode( " ", $classes );
			$list[]		= HtmlElements::ListItem( $label.$sublist, $level, array( 'class' => $class ) );
		}
		return HtmlElements::unorderedList( $list, $level );
	}

	public function setTarget( string $target ): self
	{
		$this->target	= $target;
		return $this;
	}
}