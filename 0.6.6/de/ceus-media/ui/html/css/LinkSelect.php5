<?php
/**
 *	...
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
 *	@package		ui.html.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.02.2009
 *	@version		0.1
 *	@link			http://www.grc.com/menudemo.htm
 */
import( 'de.ceus-media.adt.tree.menu.List' );
import( 'de.ceus-media.adt.tree.menu.Item' );
import( 'de.ceus-media.ui.html.css.TreeMenu' );
/**
 *	...
 *	@package		ui.html.css
 *	@uses			ADT_Tree_Menu_List
 *	@uses			ADT_Tree_Menu_Item
 *	@uses			UI_HTML_CSS_TreeMenu
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.02.2009
 *	@version		0.1
 */
class UI_HTML_CSS_LinkSelect
{
	public static function build( $name, $links, $value = NULL, $class = NULL, $prefix = NULL )
	{
		$list	= new ADT_Tree_Menu_List();
		$value	= is_null( $value ) ? NULL : (string) $value;
		
		foreach( $links as $link )
		{
			$key	= is_null( $link['key'] ) ? NULL : (string) $link['key'];
			if( $key === $value )
			{
				$label	= $prefix ? $prefix.$link['label'] : $link['label'];
				$main	= new ADT_Tree_Menu_Item( "#", $label );
			}
		}
		if( !( isset( $main ) && $main instanceof ADT_Tree_Menu_Item ) )
		{
			$first	= array_pop( array_slice( $links, 0, 1 ) );
			$label	= $prefix ? $prefix.$first['label'] : $first['label'];
			$main	= new ADT_Tree_Menu_Item( "#", $label );
			$value	= $first['key'];
		}
		$value	= is_null( $value ) ? NULL : (string) $value;
		
		$list->addChild( $main );

		foreach( $links as $link )
		{
			$item	= new ADT_Tree_Menu_Item( $link['url'], $link['label'] );
			$key	= is_null( $link['key'] ) ? NULL : (string) $link['key'];
			if( $key === $value )
				continue;
			$main->addChild( $item );
		}
		$code	= UI_HTML_CSS_TreeMenu::buildMenu( $list );
		$code	= UI_HTML_Tag::create( "span", $code, array( 'class' => $class ) );
		return $code;
	}
}
?>