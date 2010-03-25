<?php
/**
 *	Sniffer for Languages accepted by a HTTP Request.
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
 *	Sniffer for Languages accepted by a HTTP Request.
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
class Net_HTTP_LanguageSniffer
{
	/**	@var		$pattern	Reg Ex Pattern */
	protected static $pattern	= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns prefered allowed and accepted Language from HTTP_ACCEPT_LANGUAGE.
	 *	@access		public
	 *	@static
	 *	@param		array	$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string	$default		Default Languages supported and allowed by the Application
	 *	@return		string
	 */
	public static function getLanguage( $allowed, $default = false )
	{
		$accept	= getEnv( 'HTTP_ACCEPT_LANGUAGE' );
		return self::getLanguageFromString( $accept, $allowed, $default );
	}

	/**
	 *	Returns prefered allowed and accepted Language from String.
	 *	@access		public
	 *	@static
	 *	@param		array	$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string	$default		Default Languages supported and allowed by the Application
	 *	@return		string
	 */
	public static function getLanguageFromString( $string, $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string );
		$currentLanguage	= $default;
		$currentQuality		= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match( self::$pattern, $accept, $matches ) )
				continue;
			$languageCode = explode ( '-', $matches[1] );
			$languageQuality =  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			while( count( $languageCode ) )
			{
				if( in_array( strtolower( join( '-', $languageCode ) ), $allowed ) )
				{
					if( $languageQuality > $currentQuality )
					{
						$currentLanguage	= strtolower( join( '-', $languageCode ) );
						$currentQuality		= $languageQuality;
						break;
					}
				}
				array_pop( $languageCode );
			}
		}
		return $currentLanguage;
	}
}
?>