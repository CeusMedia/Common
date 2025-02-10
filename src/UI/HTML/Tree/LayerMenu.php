<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\ADT\Tree\Menu\Collection as TreeMenuCollection;
use CeusMedia\Common\Alg\Tree\Menu\Converter as TreeMenuConverter;
use CeusMedia\Common\ADT\Tree\Menu\Item as TreeMenuItem;
use CeusMedia\Common\UI\HTML\Elements as HtmlElements;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	Builder for Layer Menu.
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
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Builder for Layer Menu.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class LayerMenu
{
	protected string $rootId;

	protected string $rootLabel;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$rootId			ID Tree Root
	 *	@param		string		$rootLabel		Label of Tree Root
	 *	@return		void
	 */
	public function __construct( string $rootId, string $rootLabel )
	{
		$this->rootId		= $rootId;
		$this->rootLabel	= $rootLabel;
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		protected
	 *	@static
	 *	@param		TreeMenuCollection	$tree	    Tree Menu Structure
	 *	@param		string				$parent	    ID of parent Node
	 *	@param		array				$steps	    List of Steps in Tree
	 *	@param		int					$level  	Depth Level of Tree
	 *	@return		string
	 */
	protected static function buildLayersRecursive( TreeMenuCollection $tree, string $parent, array $steps = [], int $level = 0 ): string
	{
		$backlinks	= "";
		if( count( $steps ) > 1 ){
			$backlinks	= [];
			for( $i=1; $i<count( $steps ); $i++ ){
				$step			= $steps[$i-1];
				$label			= HtmlTag::create( "span", $step['label'] );
				$attributes		= array(
					'class'		=> "level".$step['level'],
					'onclick'	=> "stepOutTo('".$step['id']."');"
				);
				$backlinks[]	= HtmlElements::ListItem( $label, $level, $attributes );
			}
			$backlinks	= implode( "\n", $backlinks );
			$backlinks	= HtmlTag::create( "ol", $backlinks, array( 'class' => "back" ) );
		}

		$list		= [];
		foreach( $tree->getChildren() as $id => $item ){
			if( $item instanceof TreeMenuItem ){
				$link	= HtmlElements::Link( $item->url, $item->label );
				$list[]	= HtmlElements::ListItem( $link, $level );
			}
			else if( $item->hasChildren() ){
				$label	= HtmlTag::create( "span", $item->label );
				$attributes		= array(
					'class'		=> "parent",
					'onclick'	=> "stepInTo('".$parent."_".$id."');"
				);
				$list[]	= HtmlElements::ListItem( $label, $level, $attributes );
			}
		}
		$list	= HtmlElements::unorderedList( $list, $level );
		$nested		= count( $steps ) > 1 ? " nested" : "";
		$attributes	= array(
			"id"	=> "layer_".$parent,
			"class"	=> "stepLayer".$nested
		);

		$heading	= '<div class="heading">'.$tree->label.'</div>';

		$list	= HtmlTag::create( "div", $backlinks.$heading.$list, $attributes );

		foreach( $tree->getChildren() as $id => $item ){
			if( $item->hasChildren() ){
				$newSteps	= $steps;
				$newSteps[]	= array(
					'id'	=> $parent."_".$id,
					'level'	=> $level+1,
					'label'	=> $item->label,
				);
				$list	.= self::buildLayersRecursive( $item, $parent."_".$id, $newSteps );
			}
		}
		return $list;
	}

	/**
	 *	Builds Layer Menu from Tree Menu Structure.
	 *	@access		public
	 *	@param		TreeMenuCollection	    $list	Tree Menu Structure
	 *	@return     string
	 */
	public function buildMenuFromMenuList( TreeMenuCollection $list ): string
	{
		$root	= array(
			array(
				'id' 	=> $this->rootId,
				'level' => 0,
				'label' => $this->rootLabel
			)
		);
		return self::buildLayersRecursive( $list, $this->rootId, $root );
	}

	/**
	 *	Builds Layer Menu from OPML String.
	 *	@access		public
	 *	@param		string		$opml			OPML String
	 *	@return     string
	 */
	public function buildMenuFromOpml( string $opml ): string
	{
		$list		= TreeMenuConverter::convertFromOpml( $opml, $this->rootLabel );
		return $this->buildMenuFromMenuList( $list );
	}

	/**
	 *	Builds Layer Menu from OPML File.
	 *	@access		public
	 *	@param		string		$fileName		URL of OPML File
	 *	@return     string
	 */
	public function buildMenuFromOpmlFile( string $fileName ): string
	{
		$list	= TreeMenuConverter::convertFromOpmlFile( $fileName, $this->rootLabel );
		return $this->buildMenuFromMenuList( $list );
	}
}