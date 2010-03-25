<?php
/**
 *	Matches String against regular expression.
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
 *	@package		alg.preg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2008
 *	@version		$Id$
 *	@see			http://de.php.net/preg_match
 */
/**
 *	Matches String against regular expression.
 *	@category		cmClasses
 *	@package		alg.preg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2008
 *	@version		$Id$
 *	@see			http://de.php.net/preg_match
 */
class ALG_PREG_Match
{
	/**
	 *	Indicates whether a String matches a regular expression.
	 *	@access		public
	 *	@static
	 *	@param		string		$pattern		Regular expression, pattern String
	 *	@param		string		$string			String to test
	 *	@param		array		$modifiers		String, Array of Dictionary or Modifiers
	 *	@return		bool
	 */
	public static function accept( $pattern, $string, $modifiers = NULL )
	{
		if( !is_string( $pattern ) )
			throw new InvalidArgumentException( 'First parameter must be a String ('.gettype( $pattern ).' given).' );
		if( !is_string( $string ) )
			throw new InvalidArgumentException( 'Second parameter must be a String ('.gettype( $string ).' given).' );

		switch( gettype( $modifiers ) )
		{
			case 'NULL':
				$modifiers	= "";
				break;
			case 'string':
				break;
			case 'array':
				$modifiers	= implode( "", array_values( $modifiers ) );
				break;
			case 'object':
				if( is_a( $modifiers, 'ADT_List_Dictionary' ) )
				{
					$modifiers	= implode( "", array_values( $modifiers->getAll() ) );
					break;
				}
			default:
				throw new InvalidArgumentException( 'Modifiers must be a String, Array or Dictionary.' );
		}
		$pattern	= str_replace( "/", "\/", $pattern );
		$match		= @preg_match( "/".$pattern."/".(string) $modifiers, $string, $matches );
		if( $match === FALSE )
			throw new InvalidArgumentException( 'Pattern "'.$pattern.'" is invalid.' );
		if( $match )
			return $matches[0];
		return FALSE;
	}
}
?>