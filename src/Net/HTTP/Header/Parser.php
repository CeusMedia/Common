<?php
/**
 *	Parser for HTTP Headers.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2016 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.2.5
 *	@version		$Id$
 */
/**
 *	Parser for HTTP Headers.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2016 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.2.5
 *	@version		$Id$
 */
class Net_HTTP_Header_Parser
{
	/**
	 *	Parses block of HTTP headers and returns list of HTTP header field objects.
	 *	@static
	 *	@access		public
	 *	@param		$string			HTTP headers encoded as string
	 *	@return		Net_HTTP_Header_Section
	 */
	static public function parse( $string )
	{
		$section	= new Net_HTTP_Header_Section();
		$lines		= explode( PHP_EOL, trim( $string ) );
		foreach( $lines as $line )
		{
			if( preg_match( '@^HTTP/@', $line ) )
				continue;
			if( strlen( trim( $line ) ) )
				$section->addField( Net_HTTP_Header_Field_Parser::parse( $line ) );
		}
		return $section;
	}
}
