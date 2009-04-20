<?php
/**
 *	Formats JSON String.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		adt.json
 *	@author			Umbrae <umbrae@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.01.2008
 *	@version		0.2
 */
/**
 *	Formats JSON String.
 *	@package		adt.json
 *	@author			Umbrae <umbrae@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.01.2008
 *	@version		0.2
 *	@todo			Unit Test
 */
class ADT_JSON_Formater
{
	/**
	 *	Formats JSON String.
	 *	@access		public
	 *	@param		string		$json		JSON String or Object to format
	 *	@return		string
	 */
	public static function format( $json, $validateSource = FALSE )
	{
		$tab			= "  ";
		$content		= "";
		$indentLevel	= 0;
		$inString		= FALSE;

		if( !is_string( $json ) )
			$json	= json_encode( $json );

		if( $validateSource )
			if( json_decode( $json ) === FALSE )
				throw new InvalidArgumentException( 'JSON String is not valid.' );
			
		$len	= strlen( $json );
		for( $c=0; $c<$len; $c++ )
		{
			$char	= $json[$c];
			switch( $char )
			{
				case '{':
				case '[':
					$content .= $char;
					if( !$inString )
					{
						$content .= "\n".str_repeat( $tab, $indentLevel + 1 );
						$indentLevel++;
					}
					break;
				case '}':
				case ']':
					if( !$inString )
					{
						$indentLevel--;
						$content .= "\n".str_repeat( $tab, $indentLevel );
					}
					$content .= $char;
					break;
				case ',':
					$content .= $inString ? $char : ",\n" . str_repeat( $tab, $indentLevel );
					break;
				case ':':
					$content .= $inString ? $char : ": ";
					break;
				case '"':
					if( $c > 0 && $json[$c-1] != '\\' )
						$inString = !$inString;
				default:
					$content .= $char;
					break;                   
			}
		}
		return $content;
	}
}
?>