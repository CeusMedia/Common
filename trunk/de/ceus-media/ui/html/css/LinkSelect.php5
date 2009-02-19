<?php
import( 'de.ceus-media.adt.tree.menu.List' );
import( 'de.ceus-media.adt.tree.menu.Item' );
import( 'de.ceus-media.ui.html.css.TreeMenu' );
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
			$label	= $prefix ? $prefix.$link['label'] : $link['label'];
			$item	= new ADT_Tree_Menu_Item( $link['url'], $label );
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