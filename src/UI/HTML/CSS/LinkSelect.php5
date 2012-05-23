<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.02.2009
 *	@version		$Id$
 *	@link			http://www.grc.com/menudemo.htm
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		UI.HTML.CSS
 *	@uses			ADT_Tree_Menu_List
 *	@uses			ADT_Tree_Menu_Item
 *	@uses			UI_HTML_CSS_TreeMenu
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.02.2009
 *	@version		$Id$
 */
class UI_HTML_CSS_LinkSelect
{
	public static function build( $name, $links, $value = NULL, $class = NULL, $prefix = NULL )
	{
		$list	= new ADT_Tree_Menu_List();
		$value	= is_null( $value ) ? NULL : (string) $value;
		
		foreach( $links as $link )
		{
			$key	= isset( $link['key'] ) ? (string) $link['key'] : NULL;
			$url	= isset( $link['url'] ) ? (string) $link['url'] : NULL;
			if( $key === $value && $url )
			{
				$label	= $prefix ? $prefix.$link['label'] : $link['label'];
				$main	= new ADT_Tree_Menu_Item( "javascript: void();", $label );
			}
		}
		if( !( isset( $main ) && $main instanceof ADT_Tree_Menu_Item ) )
		{
			$i=0;
			do
			{
				$first	= array_slice( $links, $i++, 1 );
				$first	= array_pop( $first );
			}
			while( empty( $first['url'] ) && $i < count( $links ) );
			$label	= $prefix ? $prefix.$first['label'] : $first['label'];
			$main	= new ADT_Tree_Menu_Item( "#", $label );
			$value	= isset( $first['key'] ) ? $first['key'] : NULL;
		}
		$value	= is_null( $value ) ? NULL : (string) $value;
		
		$list->addChild( $main );

		foreach( $links as $link )
		{
			if( !( isset( $link['attributes'] ) && is_array( $link['attributes'] ) ) )				//  no attributes given
				$link['attributes']	= array();														//  set empty array

			$attributes	= array();
			if( isset( $link['class'] ) )
				$attributes['class']	= $link['class'];
			else if( isset( $link['disabled'] ) )
				$attributes['disabled']	= $link['disabled'];
			
			$key	= isset( $link['key'] ) ? (string) $link['key'] : NULL;
			$url	= isset( $link['url'] ) ? (string) $link['url'] : NULL;
			$item	= new ADT_Tree_Menu_Item( $url, $link['label'], $attributes );
			$key	= is_null( $key ) ? NULL : (string) $key;
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