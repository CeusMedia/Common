<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Loads XML from a URL and parses to a Tree of XML_DOM_Nodes.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\Reader as NetReader;
use CeusMedia\Common\Net\CURL as CURL;
use Exception;

/**
 *	Loads XML from a URL and parses to a Tree of XML_DOM_Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class UrlReader
{
	/**	@var		string				$url			URL of XML File */
	protected string $url;

	/**	@var		array				$mimeTypes		List of acceptable Response MIME Type */
	public static array $mimeTypes		= [
		'application/xml',
		'application/xslt+xml',
		'application/rss+xml',
		'text/xml',
	];

	public static string $userAgent		= 'CeusMediaCommon:XML.DOM.UrlReader/1.0';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of XML File
	 *	@return		void
	 */
	public function __construct( string $url )
	{
		$this->url	= $url;
	}

	/**
	 *	Loads an XML File statically and returns parsed Tree.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL of XML File
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		Node
	 *	@throws		IoException
	 *	@throws		Exception
	 */
	public static function load( string $url, array $curlOptions = [] ): Node
	{
		$reader	= new NetReader( $url );
		$reader->setUserAgent( self::$userAgent );
		$xml	= $reader->read( $curlOptions );
		$type	= explode( ";",$reader->getInfo( CURL::INFO_CONTENT_TYPE ) );
		$type	= array_shift( $type );

		if( !in_array( $type, self::$mimeTypes, TRUE ) )
			throw new Exception( 'URL "'.$url.'" is not an accepted XML File (MIME Type: '.$type.').' );

		$parser	= new Parser();
		return $parser->parse( $xml );
	}

	/**
	 *	Reads XML File and returns parsed Tree.
	 *	@access		public
	 *	@return		Node
	 *	@throws		Exception
	 */
	public function read(): Node
	{
		return self::load( $this->url );
	}
}
