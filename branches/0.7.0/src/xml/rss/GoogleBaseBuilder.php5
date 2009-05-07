<?php
/**
 *	Builds RSS for Google Base - Froogle.
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
 *	@package		xml.rss
 *	@extends		XML_RSS_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.02008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.rss.Builder' );
/**
 *	Builds RSS for Google Base - Froogle.
 *	@package		xml.rss
 *	@extends		XML_RSS_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.02008
 *	@version		0.1
 */
class XML_RSS_GoogleBaseBuilder extends XML_RSS_Builder
{
	protected $itemElements	= array(
		'title'						=> false,
		'description'				=> false,
		'titel'						=> TRUE,
		'beschreibung'				=> TRUE,
		'link'						=> TRUE,
		'g:id'						=> TRUE,
		'g:preis'					=> TRUE,
		'g:autor'					=> TRUE,
		'g:isbn'					=> false,
		'g:bild_url'				=> false,
		'g:name_publikation'		=> false,
		'g:produktart'				=> false,
		'g:sprache'					=> false,
		'g:standort'				=> false,
		'g:währung'					=> false,
		'g:zustand'					=> false,
		'g:veröffentlichungs_datum'	=> false,
		'g:herstellungsjahr'		=> false,
		'g:veröffentlichung_band'	=> false,
	);
	/**	@var		string		$namespaceUri		URI of Google Base Namespace */
	public static $namespaceUri	= "http://base.google.com/ns/1.0";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->registerNamespace( 'g', self::$namespaceUri );
	}
}
?>