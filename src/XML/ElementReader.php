<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for XML Elements from File or URL.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\Net\Reader as NetReader;
use Exception;

/**
 *	Reader for XML Elements from File or URL.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ElementReader
{
	/**
	 *	Reads XML from string.
	 *	@static
	 *	@access		public
	 *	@param		string		$xml		XML string to read
	 *	@return		Element
	 *	@throws		Exception	if the XML data could not be parsed
	 */
	public static function read( string $xml ): Element
	{
		return new Element( $xml );
	}

	/**
	 *	Reads XML from File.
	 *	@static
	 *	@access		public
	 *	@param		string		$fileName	File name to XML file
	 *	@return		Element
	 *	@throws		Exception	if the XML data could not be parsed
	 */
	public static function readFile( string $fileName ): Element
	{
		$xml	= FileReader::load( $fileName );
		return self::read( $xml );
	}

	/**
	 *	Reads XML from URL.
	 *	@static
	 *	@access		public
	 *	@param		string		$url		URL to read XML from
	 *	@return		Element
	 *	@throws		Exception	if the XML data could not be parsed
	 */
	public static function readUrl( string $url ): Element
	{
		return self::read( NetReader::readUrl( $url ) );
	}
}
