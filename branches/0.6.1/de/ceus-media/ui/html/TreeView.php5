<?php
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.JQuery' );
/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview.
 *	@package		ui.html
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2008
 *	@version		0.1
 */
/**
 *	Builds HTML Tree with nested Lists for JQuery Plugin Treeview.
 *	@package		ui.html
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2008
 *	@version		0.1
 */
class UI_HTML_TreeView
{
	/**	@var		string		$baseUrl			Base URL for linked Items */
	protected $baseUrl;
	/**	@var		string		$queryKey			Query Key for linked Items */
	protected $queryKey;

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
	 *	Constructs Tree View recursive.
	 *	@access		private
	 *	@param		string		$currentLink		Current Link
	 *	@param		array		$nodes				Array of Nodes
	 *	@param		array		$attributes			Attributes for List Tag
	 *	@param		int			$level				Depth of List
	 *	@return		string
	 */
	public function constructTree( $nodes, $attributes = array(), $level = 0, $path = "" )
	{
		$list	= array();
		foreach( $nodes as $node )
		{
			$node['type']	= ( isset( $node['type'] ) && $node['type'] ) ? $node['type'] : isset( $node['children'] ) && $node['children'];
			$node['class']	= ( isset( $node['class'] ) && $node['class'] ) ? $node['class'] : $node['type'];
			$node['linked']	= ( isset( $node['linked'] ) && $node['linked'] ) ? TRUE : $node['type'] == "leaf";

			$way	= $path ? $path."/" : "";
			if( !isset( $node['id'] ) )
				$node['id']	= $way.$node['label'];

			$label	= UI_HTML_Tag::create( "span", $node['label'], array( 'class' => $node['class'] ) );
			if( isset( $node['linked'] ) && $node['linked'] )
			{
				$url	= $this->baseUrl.$this->queryKey.$node['id'];
				$link	= UI_HTML_Elements::Link( $url, $node['label'] );
				$label	= UI_HTML_Tag::create( "span", $link, array( 'class' => $node['class'] ) );
			}
			$sublist	= $node['type'] == "node" ? $this->constructTree( $node['children'], array(), $level + 1 ) : "";
			$label		.= $sublist;
			$item		= UI_HTML_Elements::ListItem( $label, $level, array( 'id' => $node['id'] ) );
			$list[]		= $item;
		}
		if( count( $list ) )
			return UI_HTML_Elements::unorderedList( $list, $level, $attributes );
		return "";
	}
	
	/**
	 *	Builds JavaScript to call Plugin.
	 *	@access		public
	 *	@param		string		$selector			JQuery Selector of Tree
	 *	@param		string		$cookieId			Store Tree in Cookie
	 *	@param		string		$animated			Speed of Animation (fast|slow)
	 *	@param		bool		$unique				Flag: open only 1 Node in every Level
	 *	@param		bool		$collapsed			Flag: start with collapsed Nodes
	 *	@return		string
	 */
	public function buildJavaScript( $selector, $cookieId = NULL, $animated = "fast", $unique = FALSE, $collapsed = FALSE )
	{
		$options	= array();
		if( $cookieId )
		{
			$options['persist']		= '"cookie"';
			$options['cookieId']	= '"'.$cookieId.'"';
		}
		else
			$options['persist']		= '"location"';
		if( $animated )
			$options['animated']	= '"'.$animated.'"';
		if( $unique )
			$options['unique']		= "true";
		if( $collapsed )
			$options['collapsed']	= "true";
		
		return UI_HTML_JQuery::buildPluginCall( "treeview", $selector, $options );
	}
}
?>