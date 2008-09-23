<?php
import( 'de.ceus-media.ui.html.Tag' );
class UI_HTML_Panel
{
	public static $class	= "panel";

	public static function create( $header, $content, $footer = "", $class = "default", $attributes = array() )
	{
		$divHead	= self::wrap( self::wrap( $header, 'panelHeadInner' ), 'panelHead' );
		$divContent	= self::wrap( self::wrap( $content, 'panelContentInner' ), 'panelContent' );
		$divFoot	= self::wrap( self::wrap( $footer, 'panelFootInner' ), 'panelFoot' );
		$class		= $class ? self::$class." ".$class : self::$class;
		$divPanel	= self::wrap( $divHead.$divContent.$divFoot, $class, $attributes );
		return $divPanel;
	}
	
	protected static function wrap( $content, $class, $attributes = array() )
	{
		$attributes	= array_merge( $attributes, array( 'class' => $class ) );
		return UI_HTML_Tag::create( "div", $content, $attributes );
	}
}
?>