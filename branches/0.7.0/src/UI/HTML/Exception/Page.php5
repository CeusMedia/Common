<?php
class UI_HTML_Exception_Page
{
	public static function render( Exception $e )
	{
		$page	= new UI_HTML_PageFrame();
		$page->setTitle( 'Exception' );
		$page->addJavaScript( 'http://localhost/ceusmedia.de/js/jquery/1.4.2.min.js' );
		$page->addJavaScript( 'http://localhost/ceusmedia.de/js/jquery/cmExceptionView/0.1.js' );
		$page->addStylesheet( 'http://localhost/ceusmedia.de/js/jquery/cmExceptionView/0.1.css' );
		$options	= array( 'foldTraces' => TRUE );
		$script		= UI_HTML_JQuery::buildPluginCall( 'cmExceptionView', 'dl.exception', $options );
		$page->addHead( UI_HTML_Tag::create( 'script', $script ) );
		$page->addBody( UI_HTML_Exception_View::render( $e ) );
		return $page->build( array( 'style' => 'margin: 1em' ) );
	}
}
?>