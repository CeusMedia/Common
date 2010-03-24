<?php
/**
 *	Sniffer for Mime Types accepted by a HTTP Request.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
/**
 *	Sniffer for Mime Types accepted by a HTTP Request.
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
class Net_HTTP_MimeTypeSniffer
{
	/**
	 *	Returns prefered allowed and accepted Mime Types.
	 *	@access		public
	 *	@param		array	$allowed		Array of Mime Types supported and allowed by the Application
	 *	@param		string	$default		Default Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	public function getMimeType( $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		$pattern		= '@^([a-z\*\+]+(/[a-z\*\+]+)*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$@i';
		$accepted	= getEnv( 'HTTP_ACCEPT' );
		if( !$accepted )
			return $default;
		$accepted	= preg_split( '/,\s*/', $accepted );
		$curr_mime	= $default;
		$curr_qual	= 0;
		foreach( $accepted as $accept)
		{
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$mime_code = explode ( '/', $matches[1] );
			$mime_quality =  isset( $matches[3] ) ? (float) $matches[3] : 1.0;
			while( count( $mime_code ) )
			{
				if( in_array( strtolower( join( '/', $mime_code ) ), $allowed ) )
				{
					if( $mime_quality > $curr_qual )
					{
						$curr_mime	= strtolower( join( '/', $mime_code ) );
						$curr_qual	= $mime_quality;
						break;
					}
				}
				array_pop( $mime_code );
			}
		}
		return $curr_mime;
	}
}
?>