<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Builder for iCalendar File from XML Tree.
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
 *	Builder for iCalendar File from XML Tree.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			RFC2445
 *	@link			https://www.w3.org/2002/12/cal/rfc2445
 */
class Builder
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
	 *	Builds Array of iCal Lines from XML Tree.
	 *	@access		public
	 *	@param		Node		$tree		XML Tree
	 *	@return 	string
	 */
	public function build( Node $tree ): string
	{
		$lines	= [];
		$children	= $tree->getChildren();
		foreach( $children as $child )
			foreach( self::buildRecursive( $child ) as $line )
				$lines[]	= $line;
		return implode( self::$lineBreak, $lines );
	}

	/**
	 *	Builds iCal Line.
	 *	@access		protected
	 *	@static
	 *	@param		string		$name		Line Name
	 *	@param		array		$param		Line Parameters
	 *	@param		string		$content	Line Value
	 *	@return 	string
	 */
	protected static function buildLine( string $name, array $param, string $content ): string
	{
		$params	= [];
		foreach( $param as $key => $value ){
			$value	= (string) $value;
			//  parameter values containing colon, semicolon or comma must be quoted (RFC 5545, 3.1)
			$isQuoted	= strlen( $value ) > 1 && str_starts_with( $value, '"' ) && str_ends_with( $value, '"' );
			if( !$isQuoted && 1 === preg_match( '/[;:,]/', $value ) )
				$value	= '"'.str_replace( '"', '', $value ).'"';
			$params[]	= strtoupper( trim( (string) $key ) )."=".$value;
		}
		$line	= strtoupper( $name );
		if( [] !== $params )
			//  parameters are separated by comma in 1.0.x (RFC 5545: semicolon, from 1.2 on)
			$line	.= ";".implode( ",", $params );
		$line	.= ":".$content;
		return self::foldLine( $line );
	}

	/**
	 *	Folds a content line into lines of at most 75 octets (excluding line break), as defined in RFC 5545.
	 *	Continuation lines start with a space. Multibyte UTF-8 characters are not split.
	 *	@access		protected
	 *	@static
	 *	@param		string		$line		Unfolded content line
	 *	@return 	string
	 */
	protected static function foldLine( string $line ): string
	{
		$lines	= [];
		$limit	= 75;
		while( strlen( $line ) > $limit ){
			$cut	= $limit;
			//  do not cut within a UTF-8 multibyte sequence
			while( $cut > 1 && ( ord( $line[$cut] ) & 0xC0 ) === 0x80 )
				$cut--;
			$lines[]	= substr( $line, 0, $cut );
			$line		= substr( $line, $cut );
			//  continuation lines start with a space, which counts to the limit
			$limit		= 74;
		}
		$lines[]	= $line;
		return implode( self::$lineBreak." ", $lines );
	}

	/**
	 *	Builds Array of iCal Lines from XML Tree recursive.
	 *	@access		protected
	 *	@static
	 *	@param		Node		$node		XML Node
	 *	@return 	array
	 */
	protected static function buildRecursive( Node $node  ): array
	{
		$lines	= [];
		$name	= $node->getNodeName();
		$value	= $node->getContent();
		$param	= $node->getAttributes();
		if( NULL === $value ){
			$lines[]	= "BEGIN:".strtoupper( $name );
			$children	= $node->getChildren();
			foreach( $children as $child )
				foreach( self::buildRecursive( $child ) as $line )
					$lines[]	= $line;
			$lines[]	= "END:".strtoupper( $name );
		}
		else
			$lines[]	= self::buildLine( $name, $param, $value );
		return $lines;
	}
}
