<?php
/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview from a Array of Nodes.
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
 *	@since			18.06.2008
 */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\UI\HTML\Elements;
use CeusMedia\Common\UI\HTML\JQuery;
use CeusMedia\Common\UI\HTML\Tag;
use ArrayObject;
use InvalidArgumentException;

/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview from a Array of Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.06.2008
 */
class ArrayView
{
	/**	@var		string		$baseUrl			Base URL for linked Items */
	protected $baseUrl;

	/**	@var		string		$queryKey			Query Key for linked Items */
	protected $queryKey;

	protected $target			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$baseUrl			Base URL for linked Items
	 *	@param		string		$queryKey			Query Key for linked Items
	 *	@return		void
	 */
	public function __construct( $baseUrl, $queryKey )
	{
		$this->baseUrl	= $baseUrl;
		$this->queryKey	= $queryKey;
	}

	/**
	 *	Builds JavaScript to call Plugin.
	 *	@access		public
	 *	@static
	 *	@param		string		$selector			JQuery Selector of Tree
	 *	@param		string		$cookieId			Store Tree in Cookie
	 *	@param		string		$animated			Speed of Animation (fast|slow)
	 *	@param		bool		$unique				Flag: open only 1 Node in every Level
	 *	@param		bool		$collapsed			Flag: start with collapsed Nodes
	 *	@return		string
	 */
	public static function buildJavaScript( $selector, $cookieId = NULL, $animated = "fast", $unique = FALSE, $collapsed = FALSE )
	{
		$options	= [];
		if( $cookieId )
		{
			$options['persist']		= "cookie";
			$options['cookieId']	= $cookieId;
		}
		else
			$options['persist']		= "location";
		if( $animated )
			$options['animated']	= strtolower( (string) $animated );
		if( $unique )
			$options['unique']		= "true";
		if( $collapsed )
			$options['collapsed']	= "true";

		return JQuery::buildPluginCall( "treeview", $selector, $options );
	}

	/**
	 *	Constructs Tree View recursive.
	 *	@access		private
	 *	@param		ArrayObject	$nodes				Array of Nodes
	 *	@param		string		$currentId			Current ID selected in Tree
	 *	@param		array		$attributes			Attributes for List Tag
	 *	@param		int			$level				Depth of List
	 *	@param		string		$path				Path for generated IDs
	 *	@return		string
	 *	@link		http://docs.jquery.com/Plugins/Treeview/treeview#options
	 */
	public function constructTree( ArrayObject $nodes, $currentId = NULL, $attributes = [], $level = 0, $path = "" )
	{
		$target	= $this->target ? $this->target : NULL;
		$list	= [];
		foreach( $nodes as $node )
		{
			if( !isset( $node['label'] ) )
				throw new InvalidArgumentException( 'A Node must at least have a Label.' );

			$node['type']	= ( isset( $node['type'] ) && $node['type'] ) ? $node['type'] : isset( $node['children'] ) && $node['children'];
			$node['class']	= ( isset( $node['class'] ) && $node['class'] ) ? $node['class'] : $node['type'];
			$node['linked']	= ( isset( $node['linked'] ) && $node['linked'] ) ? TRUE : $node['type'] == "leaf";

			$way	= $path ? $path."/" : "";
			//  no ID set
			if( !isset( $node['id'] ) )
				//  generate ID
				$node['id']	= rawurlencode( $way.$node['label'] );

			$linkClass	= rawurlencode( $currentId ) == $node['id'] ? 'selected' : NULL;

			$label	= Tag::create( "span", $node['label'], ['class' => $node['class']] );
			//  linked Item
			if( $node['linked'] )
			{
				//  generate URL
				$url	= $this->baseUrl.$this->queryKey.$node['id'];
				//  generate Link Tag
				$link	= Elements::Link( $url, $node['label'], $linkClass, $target );
				//  linked Nodes have no Span Container
				$label	= $link;
				//  linked Leafes have a Span Container
				if( 1 || $node['type'] == "leaf" )
					$label	= Tag::create( "span", $link, ['class' => $node['class']] );
			}
			$sublist	= "";
			$children	= new ArrayObject();
			if( $node['type'] == "node" )
				$children	= new ArrayObject( $node['children'] );

			$sublist	= "\n".$this->constructTree( $children, $currentId, [], $level + 1, $way.$node['label'] );
			$label		.= $sublist;
			$item		= Elements::ListItem( $label, $level, ['id' => $node['id'], 'class' => $node['class']] );
			$list[]		= $item;
		}
		if( count( $list ) )
			return Elements::unorderedList( $list, $level, $attributes );
		return "";
	}

	public function setTarget( $target )
	{
		$this->target	= $target;
	}
}
