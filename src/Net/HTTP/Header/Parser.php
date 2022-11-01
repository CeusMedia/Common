<?php
/**
 *	Parser for HTTP Headers.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;
use CeusMedia\Common\Net\HTTP\Header\Field\Parser as FieldParser;

/**
 *	Parser for HTTP Headers.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**
	 *	Parses block of HTTP headers and returns list of HTTP header field objects.
	 *	@static
	 *	@access		public
	 *	@param		string      $string			HTTP headers encoded as string
	 *	@return		HeaderSection
	 */
	public static function parse( string $string ): HeaderSection
	{
		$section	= new HeaderSection();
		$lines		= explode( PHP_EOL, trim( $string ) );
		foreach( $lines as $line )
		{
			if( preg_match( '@^HTTP/@', $line ) )
				continue;
			if( strlen( trim( $line ) ) )
				$section->addField( FieldParser::parse( $line ) );
		}
		return $section;
	}
}
