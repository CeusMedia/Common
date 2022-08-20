<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for iCalendar Files.
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
 *	@package		CeusMedia_Common_FS_File_ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			RFC2445
 *	@link			http://www.w3.org/2002/12/cal/rfc2445
 */

namespace CeusMedia\Common\FS\File\ICal;

use CeusMedia\Common\XML\DOM\Node;

/**
 *	Parser for iCalendar Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			RFC2445
 *	@link			http://www.w3.org/2002/12/cal/rfc2445
 */
class Parser
{
	/**	@var	string		$lineBreak		Line Break String */
	protected static $lineBreak;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$lineBreak		Line Break String
	 *	@return 	void
	 */
	public function __construct( string $lineBreak = "\r\n" )
	{
		self::$lineBreak	= $lineBreak;
	}

	/**
	 *	Parses iCal Lines and returns an XML Tree.
	 *	@access		public
	 *	@param		string		$name		Line Name
	 *	@param		string		$string		String of iCal Lines
	 *	@return 	Node
	 */
	public function parse( string $name, string $string ): Node
	{
		$root	= new Node( $name );

		$string	= self::unfoldString( $string );
		$lines = explode( self::$lineBreak, $string );

		while( count( $lines ) ){
			$line	= array_shift( $lines );
			$parsed	= self::parseLine( $line );
			if( $parsed['name'] == "BEGIN" )
				self::parseRecursive( $parsed['value'], $root, $lines );
		}
		return $root;
	}

	/**
	 *	Parses a single iCal Lines.
	 *	@access		protected
	 *	@static
	 *	@param		string		$line		Line to parse
	 *	@return 	array
	 */
	protected static function parseLine( string $line ): array
	{
		$pos	= strpos( $line, ":" );
		$name	= substr( $line, 0, $pos );
		$value	= substr( $line, $pos+1 );

		$params	= array();
		if( substr_count( $name, ";" ) ){
			$pos	= strpos( $name, ";" );
			$params	= substr( $name, $pos+1 );
			$name	= substr( $name, 0, $pos );
			$params	= explode( ",", utf8_decode( $params ) );
		}

		return [
			"name"	=> trim( $name ),
			"param"	=> $params,
			"value"	=> utf8_decode( $value ),
		];
	}

	/**
	 *	Parses iCal Lines and returns an XML Tree recursive.
	 *	@access		protected
	 *	@static
	 *	@param		string		$type			String to unfold
	 *	@param		Node		$root			Parent XML Node
	 *	@param		array		$lines			Array of iCal Lines
	 *	@return 	array
	 */
	protected static function parseRecursive( string $type, Node $root, array &$lines ): array
	{
		$node = new Node( strtolower( $type ) );
		$root->addChild( $node );
		while( count( $lines ) ){
			$line	= array_shift( $lines );
			$parsed	= self::parseLine( $line );
			if( $parsed['name'] == "END" && $parsed['value'] == $type )
				return $lines;
			else if( $parsed['name'] == "BEGIN" )
				$lines	= self::parseRecursive( $parsed['value'], $node, $lines );
			else{
				$child	= new Node( strtolower( $parsed['name'] ), $parsed['value'] );
				foreach( $parsed['param'] as $param ){
					$parts	= explode( "=", $param );
					$child->setAttribute( strtolower( $parts[0] ), $parts[1] );
				}
				$node->addChild( $child );
			}
		}
		return [];
	}

	/**
	 *	Unfolds folded Contents of iCal Lines.
	 *	@static
	 *	@access		protected
	 *	@param		string		$string		String to unfold
	 *	@return 	string
	 */
	protected static function unfoldString( string $string ): string
	{
		$string	= str_replace( self::$lineBreak." ;", ";", $string );
		$string	= str_replace( self::$lineBreak." :", ":", $string );
		return str_replace( self::$lineBreak." ", "", $string );
	}
}
