<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Parser for iCalendar Files.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			RFC2445
 *	@link			https://www.w3.org/2002/12/cal/rfc2445
 */

namespace CeusMedia\Common\FS\File\ICal;

use CeusMedia\Common\XML\DOM\Node;

/**
 *	Parser for iCalendar Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			RFC2445
 *	@link			https://www.w3.org/2002/12/cal/rfc2445
 */
class Parser
{
	/**	@var	string		$lineBreak		Line Break String */
	protected static string $lineBreak	= "\r\n";

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
	 *	Parses a single iCal line into name, parameters and value.
	 *	Parameters are separated by semicolons (RFC 5545). For files written by older versions of the builder,
	 *	commas followed by a parameter name are accepted as separator, too.
	 *	Colons, semicolons and commas within quoted parameter values are kept.
	 *	@access		protected
	 *	@static
	 *	@param		string		$line		Line to parse
	 *	@return 	array{name: string, param: array<string,string>, value: string}
	 */
	protected static function parseLine( string $line ): array
	{
		$parts		= [];
		$current	= '';
		$quoted		= FALSE;
		$value		= '';
		$length		= strlen( $line );
		for( $i = 0; $i < $length; $i++ ){
			$char	= $line[$i];
			if( '"' === $char ){
				$quoted		= !$quoted;
				$current	.= $char;
			}
			else if( !$quoted && ':' === $char ){
				$value	= substr( $line, $i + 1 );
				break;
			}
			else if( !$quoted && ( ';' === $char || ( ',' === $char && 1 === preg_match( '/^[A-Za-z0-9-]+=/', substr( $line, $i + 1 ) ) ) ) ){
				$parts[]	= $current;
				$current	= '';
			}
			else
				$current	.= $char;
		}
		$parts[]	= $current;

		$name	= array_shift( $parts );
		$params	= [];
		foreach( $parts as $part ){
			$pair	= explode( '=', $part, 2 );
			$key	= trim( $pair[0] );
			if( '' === $key )
				continue;
			$paramValue	= $pair[1] ?? '';
			if( strlen( $paramValue ) > 1 && str_starts_with( $paramValue, '"' ) && str_ends_with( $paramValue, '"' ) && 2 === substr_count( $paramValue, '"' ) )
				$paramValue	= substr( $paramValue, 1, -1 );
			$params[$key]	= $paramValue;
		}

		return [
			"name"	=> trim( (string) $name ),
			"param"	=> $params,
			"value"	=> $value,
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
				foreach( $parsed['param'] as $key => $value )
					$child->setAttribute( strtolower( $key ), $value );
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
		$string	= str_replace( self::$lineBreak."\t", "", $string );
		return str_replace( self::$lineBreak." ", "", $string );
	}
}
