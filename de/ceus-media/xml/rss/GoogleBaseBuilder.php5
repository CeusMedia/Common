<?php
import( 'de.ceus-media.xml.rss.Builder' );
/**
 *	Builds RSS for Google Base - Froogle.
 *	@package		xml.rss
 *	@extends		XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.02008
 *	@version		0.1
 */
/**
 *	Builds RSS for Google Base - Froogle.
 *	@package		xml.rss
 *	@extends		XML_RSS_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.02008
 *	@version		0.1
 */
class XML_RSS_GoogleBaseBuilder extends XML_RSS_Builder
{
	protected $itemElements	= array(
		'titel'						=> false,
		'beschreibung'				=> false,
		'g:preis'					=> false,
		'g:isbn'					=> false,
		'g:autor'					=> false,
		'g:bild_url'				=> false,
		'g:format'					=> false,
		'g:id'						=> false,
		'g:künstler'				=> false,
		'g:name_publikation'		=> false,
		'g:produktart'				=> false,
		'g:sprache'					=> false,
		'g:standort'				=> false,
		'g:währung'					=> false,
		'g:veröffentlichungs_datum'	=> false,
	);
	/**	@var		string		$namespaceUri		URI of Google Base Namespace */
	public static $namespaceUri	= "http://base.google.com/ns/1.0";

	public function __construct()
	{
		parent::__construct();
		$this->registerNamespace( 'g', self::$namespaceUri );
	}
}
?>