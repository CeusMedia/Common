<?php
/**
 *	Implementation of CSS Menu, a dynamic Tree Navigation without JavaScript.
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
 *	@package		ui.html.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		0.1
 *	@link			http://www.grc.com/menudemo.htm
 */
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Implementation of CSS Menu, a dynamic Tree Navigation without JavaScript.
 *	@category		cmClasses
 *	@package		ui.html.css
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.11.2008
 *	@version		0.1
 */
class UI_HTML_CSS_TreeMenu
{
	/**	@var		string				$contentDrop		Indicator HTML Code for Items containing further Items */
	protected $contentDrop;
	/**	@var		string				$contentDrop		Indicator HTML Code for Items containing further Items */
	public static $contentDropDefault	= "&nbsp;";
	
	/**
	 *	Constructor, sets Indicator HTML Code for Items containing further Items.
	 *	@access		public
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@return		void
	 */
	public function __construct( $contentDrop = NULL )
	{
		$this->contentDrop	= $contentDrop;
	}

	/**
	 *	Builds HTML of Tree Menu from Tree Menu List Data Object dynamically.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_List	$tree				Tree Menu List Data Object
	 *	@return		string
	 */
	public function build( ADT_Tree_Menu_List $tree )
	{
		return self::buildMenu( $tree, $this->contentDrop );
	}

	/**
	 *	Builds HTML of Tree Menu from Data Array dynamically.
	 *	@access		public
	 *	@static
	 *	@param		array				$tree				Data Array with Tree Menu Structure 
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@return		string
	 */
	public static function buildFromArray( $tree )
	{
		return self::buildMenuFromArray( $tree, $this->contentDrop );
	}
	
	/**
	 *	Builds HTML of Tree Menu from Tree Menu List Data Object statically.
	 *	@access		public
	 *	@static
	 *	@param		ADT_Tree_Menu_List	$tree				Tree Menu List Data Object
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@param		array				$attributes			Map of HTML Attributes of List Tag
	 *	@return		string
	 */
	public static function buildMenu( ADT_Tree_Menu_List $tree, $contentDrop = NULL, $attributes = array() )
	{
		$list	= array();
		foreach( $tree->getChildren() as $child )
			$list[]	= self::buildItemWithChildren( $child, 1, $contentDrop );
		return UI_HTML_Elements::unorderedList( $list, 1, $attributes );
	}

	/**
	 *	Builds HTML of Tree Menu from Data Array statically.
	 *	@access		public
	 *	@static
	 *	@param		array				$tree				Data Array with Tree Menu Structure 
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@return		string
	 */
	public static function buildMenuFromArray( $tree, $contentDrop = NULL )
	{
		$list	= array();
		foreach( $tree['children'] as $child )
			$list[]	= self::buildItemWithChildrenFromArray( $child, 1, $contentDrop );
		return UI_HTML_Elements::unorderedList( $list, 1 );
	}

	/**
	 *	Builds HTML of a List Item with its nested Tree Menu Items statically.
	 *	@access		protected
	 *	@static
	 *	@param		ADT_Tree_Menu_Item	$node				Tree Menu Item
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@return		string
	 */
	protected static function buildItemWithChildren( ADT_Tree_Menu_Item &$node, $level, $contentDrop = NULL )
	{
		$contentDrop	= $contentDrop !== NULL ? $contentDrop : self::$contentDropDefault;
		$children		= "";
		$label			= self::buildLabelSpan( $node->label, $level, $node->class, $node->disabled, $node->url );
		if( $node->hasChildren() )
		{
			$children	= array();
			foreach( $node->getChildren() as $child )
				$children[]	= self::buildItemWithChildren( $child, $level + 1, $contentDrop );
			$classList	= $node->getAttribute( 'classList' );
			$attributes	= array( 'class' => $classList );
			$children	= "\n".UI_HTML_Elements::unorderedList( $children, $level + 1, $attributes );
			$children	.= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
			$drop		= $level > 1 ? $contentDrop : "&nbsp;";
			$label		= '<span class="drop">'.$label.$drop.'</span><!--[if gt IE 6]><!-->';
		}
		$classLink	= $node->getAttribute( 'classLink' )." level-".$level;
		$classItem	= $node->getAttribute( 'classItem' )." level-".$level;
		$labelLink	= $label;
		if( $node->url && !$node->getAttribute( 'disabled' ) )
			$labelLink	= UI_HTML_Elements::Link( $node->url, $label, $classLink );
		if( $node->hasChildren() )
			$labelLink	.= '<!--<![endif]--><!--[if lt IE 7]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->';
		$attributes	= array( 'class' => $classItem );
		if( $node->getAttribute( 'disabled' ) )
			$attributes['class']	.= " disabled";
		return UI_HTML_Elements::ListItem( $labelLink.$children, $level, $attributes );
	}

	/**
	 *	Builds HTML of a List Item with its nested Tree Menu Items from Data Array statically.
	 *	@access		protected
	 *	@static
	 *	@param		array				$node				Data Array of Tree Menu Item
	 *	@param		string				$contentDrop		Indicator HTML Code for Items containing further Items
	 *	@return		string
	 */
	protected static function buildItemWithChildrenFromArray( &$node, $level, $contentDrop = NULL )
	{
		$contentDrop	= $contentDrop !== NULL ? $contentDrop : self::$contentDropDefault;
		$children	= "";
		$class		= isset( $node['class'] ) ? $node['class'] : 'option';
		$disabled	= isset( $node['disabled'] ) ? $node['disabled'] : FALSE;
		$label		= self::buildLabelSpan( $node['label'], $level, $class, $disabled, $node['url'] );
		if( isset( $node['children'] ) && $node['children'] )
		{
			$children	= array();
			foreach( $node['children'] as $child )
				$children[]	= self::buildItemWithChildrenFromArray( $child, $level + 1 );
			$classList	= isset( $node['classList'] ) ? $node['classList'] : NULL;
			$attributes	= array( 'class' => $classList );
			$children	= "\n".UI_HTML_Elements::unorderedList( $children, $level + 1, $attributes );
			$children	.= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
			$drop		= $level > 1 ? $contentDrop : "&nbsp;";
			$label		= '<span class="drop">'.$label.$drop.'</span><!--[if gt IE 6]><!-->';
		}
		$classLink	= isset( $node['classLink'] ) ? $node['classLink']." level-".$level : NULL;
		$classItem	= isset( $node['classItem'] ) ? $node['classItem']." level-".$level : NULL;
		$labelLink	= UI_HTML_Elements::Link( $node['url'], $label, $classLink );
		if( isset( $node['children'] ) && $node['children'] )
			$labelLink	.= '<!--<![endif]--><!--[if lt IE 7]><table border="0" cellpadding="0" cellspacing="0"><tr><td><![endif]-->';
		$attributes	= array( 'class' => $classItem );
		return UI_HTML_Elements::ListItem( $labelLink.$children, $level, $attributes );
	}

	protected static function buildLabelSpan( $label, $level, $class, $disabled, $url )
	{
		$attributes	= array(
			'class'	=> array(
				$class,
				'level-'.$level
			)
		);
		if( $disabled )
		{
			$attributes['src']		= $url;
			$attributes['class'][]	= "disabled";
			if( is_string( $disabled ) )
				$attributes['title']	= $disabled;
		}
		$attributes['class']	= implode( " ", $attributes['class'] );
		$label		= UI_HTML_Tag::create( "span", $label, $attributes );
		return $label;
	}
}
?>