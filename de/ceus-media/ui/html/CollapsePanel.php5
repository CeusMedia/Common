<?php
import( 'de.ceus-media.ui.html.Panel' );
import( 'de.ceus-media.ui.html.JQuery' );
class UI_HTML_CollapsePanel extends UI_HTML_Panel
{
	public static $class	= "collapsable";

	public static function create( $id, $header, $content, $footer = "", $class = "default" )
	{
		$class		= $class ? self::$class." ".$class : self::$class;
		return parent::create( $header, $content, $footer, $class, array( 'id' => $id ) );
	}

	public static function createScript( $selector, $options = array() )
	{
		return UI_HTML_JQuery::buildPluginCall( "CollapsePanel", $selector, $options );	
	}
}
?>