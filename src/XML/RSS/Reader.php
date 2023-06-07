<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for RSS 2.0 Feeds.
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
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\RSS;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\Net\Reader as NetReader;

/**
 *	Reader for RSS 2.0 Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**
	 *	Reads RSS from File.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	File Name to XML RSS File
	 *	@return		array
	 */
	public static function readFile( string $fileName ): array
	{
		return Parser::parse( FileReader::load( $fileName ) );
	}

	/**
	 *	Reads RSS from URL.
	 *	@access		public
	 *	@static
	 *	@param		string		$url		URL to read RSS from
	 *	@return		array
	 */
	public static function readUrl( string $url ): array
	{
		return Parser::parse( NetReader::readUrl( $url ) );
	}

	/**
	 *	Reads RSS from XML.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 */
	public static function readXml( string $xml ): array
	{
		return Parser::parse( $xml );
	}
}
