<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Parses vCard String to vCard Data Object.
 *
 *	Copyright (c) 2010-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			https://www.ietf.org/rfc/rfc2426.txt
 */

namespace CeusMedia\Common\FS\File\VCard;

use CeusMedia\Common\ADT\VCard;
use CeusMedia\Common\Alg\Text\EncodingConverter;
use InvalidArgumentException;

/**
 *	Parses vCard String to vCard Data Object.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			https://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
class Parser
{
	/**
	 *	Parses vCard String to new vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string			$string			VCard String
	 *	@param		string|NULL		$charsetIn		Charset to convert from
	 *	@param		string|NULL		$charsetOut		Charset to convert to
	 *	@return		VCard
	 */
	public static function parse( string $string, ?string $charsetIn = NULL, ?string $charsetOut = NULL ): VCard
	{
		$vcard	= new VCard;
		return self::parseInto( $string, $vcard, $charsetIn, $charsetOut );
	}

	/**
	 *	Parses vCard String to an given vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string			$string			VCard String
	 *	@param		VCard			$vcard			VCard Data Object
	 *	@param		string|NULL		$charsetIn		Charset to convert from
	 *	@param		string|NULL		$charsetOut		Charset to convert to
	 *	@return		VCard
	 */
	public static function parseInto( string $string, VCard $vcard, ?string $charsetIn = NULL, ?string $charsetOut = NULL ): VCard
	{
		if( !$string )
			throw new InvalidArgumentException( 'String is empty ' );
		if( $charsetIn && $charsetOut && function_exists( 'iconv' ) )
			$string	= EncodingConverter::convert( $string, $charsetIn, $charsetOut );

		//  unfold folded lines (line break followed by space or tab) and support CRLF
		$string	= str_replace( ["\r\n", "\r"], "\n", $string );
		$string	= (string) preg_replace( "/\n[ \t]/", '', $string );
		foreach( explode( "\n", $string ) as $line )
			if( '' !== trim( $line ) )
				self::parseLine( $vcard, $line );
		return $vcard;
	}

	//  --  PROTECTED  --  //

	/**
	 *	@param		string		$string
	 *	@return		array
	 */
	protected static function parseAttributes( string $string ): array
	{
		$list	= [];
		foreach( explode( ';', $string ) as $part ){
			if( '' === trim( $part ) )
				continue;
			//  bare types like WORK (vCard 2.1) or TYPE=work,pref
			$parts1	= explode( '=', $part, 2 );
			$values	= explode( ',', $parts1[1] ?? $parts1[0] );
			foreach( $values as $value )
				if( '' !== trim( $value ) )
					$list[]	= strtolower( trim( $value ) );
		}
		return $list;
	}

	/**
	 *	@param		VCard		$vcard
	 *	@param		string		$line
	 *	@return		void
	 */
	protected static function parseLine( VCard $vcard, string $line ): void
	{
		$partsLine	= explode( ':', $line );
		$keyFull	= array_shift( $partsLine );

		//  --  GET KEY  --  //
		$partsKey	= explode( ';', $keyFull );
		$key		= array_shift( $partsKey );

		//  --  GET KEY ATTRIBUTES  --  //
		$attributes	= implode( ';', $partsKey );
		$attributes	= self::parseAttributes( $attributes );

		//  --  GET VALUES  --  //
		$rawValue	= implode( ':', $partsLine );
		$values		= self::splitValue( $rawValue, ';' );

		//  --  BUILD ARRAY(10) FOR VALUE FIELDS  --  //
		$values		= array_pad( $values, 10, '' );

		//  --  FILL VCARD OBJECT  --  //
		switch( strtolower( $key ) ){
			case 'n':
				$vcard->setName(
					$values[0],
					$values[1],
					'' !== $values[2] ? $values[2] : NULL,
					'' !== $values[3] ? $values[3] : NULL,
					'' !== $values[4] ? $values[4] : NULL,
				);
				break;
			case 'fn':
				$vcard->setFormattedName( $values[0] );
				break;
			case 'email':
				$vcard->addEmail( $values[0], $attributes );
				break;
			case 'geo':
				$vcard->addGeoTag( $values[0], $values[1], $attributes );
				break;
			case 'org':
				$vcard->setOrganisation( $values[0], '' !== $values[1] ? $values[1] : NULL );
				break;
			case 'title':
				$vcard->setTitle( $values[0] );
				break;
			case 'nickname':
				foreach( self::splitValue( $rawValue, ',' ) as $nickname )
					if( '' !== $nickname )
						$vcard->addNickname( $nickname );
				break;
			case 'role':
				$vcard->setRole( $values[0] );
				break;
			case 'url':
				//  URLs are not escaped
				$vcard->addUrl( $rawValue, $attributes );
				break;
			case 'tel':
				$vcard->addPhone( $values[0], $attributes );
				break;
			case 'adr':
				$vcard->addAddress(
					$values[2],
					$values[1],
					$values[3],
					$values[4],
					$values[5],
					$values[6],
					'' !== $values[0] ? $values[0] : NULL,
					$attributes
				);
				break;
		}
	}

	/**
	 *	Splits a value by unescaped delimiter and unescapes the parts (RFC 2426, 4).
	 *	@access		protected
	 *	@static
	 *	@param		string		$value			Value to split
	 *	@param		string		$delimiter		Delimiter, ; or ,
	 *	@return		string[]
	 */
	protected static function splitValue( string $value, string $delimiter ): array
	{
		$parts		= [];
		$current	= '';
		$length		= strlen( $value );
		for( $i = 0; $i < $length; $i++ ){
			$char	= $value[$i];
			if( '\\' === $char && $i + 1 < $length ){
				$next		= $value[++$i];
				$current	.= in_array( $next, ['n', 'N'], TRUE ) ? "\n" : $next;
			}
			else if( $delimiter === $char ){
				$parts[]	= $current;
				$current	= '';
			}
			else
				$current	.= $char;
		}
		$parts[]	= $current;
		return $parts;
	}
}
