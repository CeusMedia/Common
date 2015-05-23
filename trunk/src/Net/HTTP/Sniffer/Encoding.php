<?php
/**
 *	Sniffer for Encoding Methods accepted by a HTTP Request.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.HTTP.Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
/**
 *	Sniffer for Encoding Methods accepted by a HTTP Request.
 *	@category		cmClasses
 *	@package		Net.HTTP.Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
class Net_HTTP_Sniffer_Encoding
{
	/**
	 *	Returns prefered allowed and accepted Encoding Method.
	 *	@access		public
	 *	@static
	 *	@param		array	$allowed		Array of Encoding Methods supported and allowed by the Application
	 *	@param		string	$default		Default Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	public static function getEncoding( $allowed, $default = NULL )
	{
		if( !$default)
			$default = $allowed[0];
		else if( !in_array( $default, $allowed ) )
			throw new InvalidArgumentException( 'Default Encoding Method must be an allowed Encoding Method.' );
		
		$pattern	= '/^([a-z]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		$accepted	= getEnv( 'HTTP_ACCEPT_ENCODING' );
		if( !$accepted )
			return $default;
		$accepted		= preg_split( '/,\s*/', $accepted );
		$currentCode	= $default;
		$currentQuality	= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$codeQuality	=  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			if( in_array( $matches[1], $allowed ) )
			{
				if( $codeQuality > $currentQuality )
				{
					$currentCode	= $matches[1];
					$currentQuality	= $codeQuality;
				}
			}
		}
		return $currentCode;
	}
}
?>