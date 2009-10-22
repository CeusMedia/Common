<?php
/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview.
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
 *	@category		cmClasses
 *	@package		ui.html
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2008
 *	@version		0.1
 */
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.JQuery' );
/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview.
 *	@category		cmClasses
 *	@package		ui.html
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2008
 *	@version		0.1
 *	@deprecated		use UI_HTML_Tree_ArrayView instead
 *	@deprecated		to be removed in 0.6.8
 *	@todo			remove in 0.6.8
 */
class UI_HTML_TreeView
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
		$options	= array();
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
		
		return UI_HTML_JQuery::buildPluginCall( "treeview", $selector, $options );
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
	public function constructTree( ArrayObject $nodes, $currentId = NULL, $attributes = array(), $level = 0, $path = "" )
	{
		$target	= $this->target ? $this->target : NULL;
		$list	= array();
		foreach( $nodes as $node )
		{
			if( !isset( $node['label'] ) )
				throw new InvalidArgumentException( 'A Node must at least have a Label.' );
	
			$node['type']	= ( isset( $node['type'] ) && $node['type'] ) ? $node['type'] : isset( $node['children'] ) && $node['children'];
			$node['class']	= ( isset( $node['class'] ) && $node['class'] ) ? $node['class'] : $node['type'];
			$node['linked']	= ( isset( $node['linked'] ) && $node['linked'] ) ? TRUE : $node['type'] == "leaf";

			$way	= $path ? $path."/" : "";
			if( !isset( $node['id'] ) )																		//  no ID set
				$node['id']	= rawurlencode( $way.$node['label'] );											//  generate ID

			$linkClass	= rawurlencode( $currentId ) == $node['id'] ? 'selected' : NULL;

			$label	= UI_HTML_Tag::create( "span", $node['label'], array( 'class' => $node['class'] ) );
			if( $node['linked'] )																			//  linked Item
			{
				$url	= $this->baseUrl.$this->queryKey.$node['id'];										//  generate URL
				$link	= UI_HTML_Elements::Link( $url, $node['label'], $linkClass, $target );				//  generate Link Tag
				$label	= $link;																			//  linked Nodes have no Span Container
				if( 1 || $node['type'] == "leaf" )																//  linked Leafes have a Span Container
					$label	= UI_HTML_Tag::create( "span", $link, array( 'class' => $node['class'] ) );
			}
			$sublist	= "";
			$children	= new ArrayObject();
			if( $node['type'] == "node" )
				$children	= new ArrayObject( $node['children'] );

			$sublist	= "\n".$this->constructTree( $children, $currentId, array(), $level + 1, $way.$node['label'] );
			$label		.= $sublist;
			$item		= UI_HTML_Elements::ListItem( $label, $level, array( 'id' => $node['id'], 'class' => $node['class'] ) );
			$list[]		= $item;
		}
		if( count( $list ) )
			return UI_HTML_Elements::unorderedList( $list, $level, $attributes );
		return "";
	}
	
	public function setTarget( $target )
	{
		$this->target	= $target;
	}
}
?>