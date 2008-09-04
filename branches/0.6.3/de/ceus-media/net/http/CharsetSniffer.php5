<?php
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_CharsetSniffer
{
	/**	@var		$pattern	Reg Ex Pattern */
	protected static $pattern	= '/^([0-9a-z-]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns prefered allowed and accepted Character Set from HTTP_ACCEPT_CHARSET.
	 *	@access		public
	 *	@param		array	$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string	$default		Default Character Sets supported and allowed by the Application
	 *	@return		string
	 */
	public static function getCharset( $allowed, $default = false )
	{
		$accepted	= getEnv( 'HTTP_ACCEPT_CHARSET' );
		return self::getCharsetFromString( $accepted, $allowed, $default );
	}
	
	/**
	 *	Returns prefered allowed and accepted Character Set from String.
	 *	@access		public
	 *	@param		array	$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string	$default		Default Character Sets supported and allowed by the Application
	 *	@return		string
	 */
	public static function getCharsetFromString( $string, $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string );
		$currentCharset	= $default;
		$currentQuality	= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match ( self::$pattern, $accept, $matches ) )
				continue;
			$charsetQuality	= isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			if( $charsetQuality > $currentQuality )
			{
				$currentCharset	= strtolower( $matches[1] );
				$currentQuality	= $charsetQuality;
			}
		}
		return $currentCharset;
	}
}
?>