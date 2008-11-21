<?php
/**
 *	Implementation of CSS Menu, a dynamic Tree Navigation without JavaScript.
 *	@package		ui.html.tree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 *	@link			http://www.grc.com/menudemo.htm
 */
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Implementation of CSS Menu, a dynamic Tree Navigation without JavaScript.
 *	@package		ui.html.tree
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 */
class UI_HTML_Tree_Menu
{	
	/**
	 *	Builds HTML of Tree Menu from Tree Menu List Data Object.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_List	$tree		Tree Menu List Data Object
	 *	@return		string
	 */
	public static function build( ADT_Tree_Menu_List $tree )
	{
		$list	= array();
		foreach( $tree->getChildren() as $child )
			$list[]	= self::buildItemWithChildren( $child, 0 );
		return UI_HTML_Elements::unorderedList( $list, 0 );
	}

	/**
	 *	Builds HTML of Tree Menu from Data Array.
	 *	@access		public
	 *	@param		array		$tree		Data Array with Tree Menu Structure 
	 *	@return		string
	 */
	public static function buildFromArray( $tree )
	{
		$list	= array();
		foreach( $tree['children'] as $child )
			$list[]	= self::buildItemWithChildrenFromArray( $child, 0 );
		return UI_HTML_Elements::unorderedList( $list, 0 );
	}

	/**
	 *	Builds HTML of a List Item with its nested Tree Menu Items.
	 *	@access		protected
	 *	@param		ADT_Tree_Menu_Item	$node		Tree Menu Item
	 *	@return		string
	 */
	protected static function buildItemWithChildren( ADT_Tree_Menu_Item &$node, $level )
	{
		$children	= "";
		$label		= '<span class="label">'.$node->label.'</span>';
		if( $node->hasChildren() )
		{
			$children	= array();
			foreach( $node->getChildren() as $child )
				$children[]	= self::buildItemWithChildren( $child, $level + 1 );
			$classList	= $node->getAttribute( 'classList' );
			$attributes	= array( 'class' => $classList );
			$children	= "\n".UI_HTML_Elements::unorderedList( $children, $level + 1, $attributes );
			$children	.= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
			if( $level )
				$label		= '<span class="drop">'.$label.'&nbsp;&raquo;</span>';
			$label	.= '<!--[if gt IE 6]><!-->';
		}
		$classLink	= $node->getAttribute( 'classLink' );
		$classItem	= $node->getAttribute( 'classItem' );
		$labelLink	= UI_HTML_Elements::Link( $node->url, $label, $classLink );
		if( $node->hasChildren() )
			$labelLink	.= '<!--<![endif]--><!--[if lt IE 7]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->';
		$attributes	= array( 'class' => $classItem );
		return UI_HTML_Elements::ListItem( $labelLink.$children, $level, $attributes );
	}

	/**
	 *	Builds HTML of a List Item with its nested Tree Menu Items from Data Array.
	 *	@access		protected
	 *	@param		array			$node		Data Array of Tree Menu Item
	 *	@return		string
	 */
	protected static function buildItemWithChildrenFromArray( &$node, $level )
	{
		$children	= "";
		$label		= '<span class="label">'.$node['label'].'</span>';
		if( isset( $node['children'] ) && $node['children'] )
		{
			$children	= array();
			foreach( $node['children'] as $child )
				$children[]	= self::buildItemWithChildrenFromArray( $child, $level + 1 );
			$classList	= isset( $node['classList'] ) ? $node['classList'] : NULL;
			$attributes	= array( 'class' => $classList );
			$children	= "\n".UI_HTML_Elements::unorderedList( $children, $level + 1, $attributes );
			if( $level )
				$label		= '<span class="drop">'.$label.'&nbsp;&raquo;</span>';
		}
		$classLink	= isset( $node['classLink'] ) ? $node['classLink'] : NULL;
		$classItem	= isset( $node['classItem'] ) ? $node['classItem'] : NULL;
		$labelLink	= UI_HTML_Elements::Link( $node['url'], $label, $classLink );
		$attributes	= array( 'class' => $classItem );
		return UI_HTML_Elements::ListItem( $labelLink.$children, $level, $attributes );
	}
}
?>