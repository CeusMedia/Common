<?php
/**
 *	Loads XML from an URL and parses to a Tree of XML_DOM_Nodes.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\XML\DOM\Node;
use Exception;

/**
 *	Loads XML from an URL and parses to a Tree of XML_DOM_Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@uses			XML_DOM_Parser
 *	@uses			Net_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
class UrlReader
{
	/**	@var		string		$url			URL of XML File */
	protected $url;
	/**	@var		array		$mimeTypes		List of acceptable Response MIME Type */
	public static $mimeTypes	= array(
		'application/xml',
		'application/xslt+xml',
		'application/rss+xml',
		'text/xml',
	);
	public static $userAgent	= 'cmClasses:XML_DOM_UrlReader/0.7';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of XML File
	 *	@return		void
	 */
	public function __construct( $url )
	{
		$this->url	= $url;
	}

	/**
	 *	Loads a XML File statically and returns parsed Tree.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL of XML File
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		Node
	 */
	public static function load( $url, $curlOptions = array() )
	{
		$reader	= new \Net_Reader( $url );
		$reader->setUserAgent( self::$userAgent );
		$xml	= $reader->read( $curlOptions );
		$type	= explode( ";",$reader->getInfo( \Net_CURL::INFO_CONTENT_TYPE ) );
		$type	= array_shift( $type );

		if( !in_array( $type, self::$mimeTypes, TRUE ) )
			throw new Exception( 'URL "'.$url.'" is not an accepted XML File (MIME Type: '.$type.').' );

		$parser	= new Parser();
		$tree	= $parser->parse( $xml );
		return $tree;
	}

	/**
	 *	Reads XML File and returns parsed Tree.
	 *	@access		public
	 *	@return		Node
	 */
	public function read()
	{
		return self::load( $this->url );
	}
}
