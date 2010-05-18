<?php
/**
 *	Parses vCard String to vCard Data Object.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		File.VCard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 */
/**
 *	Parses vCard String to vCard Data Object.
 *	@category		cmClasses
 *	@package		File.VCard
 *	@uses			ADT_VCard
 *	@uses			Alg_Text_EncodingConverter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
class File_VCard_Parser
{
	/**
	 *	Parses vCard String to new vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			VCard String
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public static function parse( $string, $charsetIn = NULL, $charsetOut = NULL )
	{
		$vcard	= new ADT_VCard;
		return self::parseInto( $string, $vcard, $charsetIn, $charsetOut );
	}

	/**
	 *	Parses vCard String to an given vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			VCard String
	 *	@param		ADT_VCard	$vcard			VCard Data Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public static function parseInto( $string, ADT_VCard $vcard, $charsetIn = NULL, $charsetOut = NULL )
	{
		if( !$string )
			throw new InvalidArgumentException( 'String is empty ' );
		if( $charsetIn && $charsetOut && function_exists( 'iconv' ) )
		{
			import( 'de.ceus-media.alg.text.EncodingConverter' );
			$string	= Alg_Text_EncodingConverter::convert( $string, $charsetIn, $charsetOut );
		}

		$lines	= explode( "\n", $string );
		foreach( $lines as $line )
			self::parseLine( $vcard, $line );
		return $vcard;
	}

	protected static function parseAttributes( $string )
	{
		$parts	= explode( ";", $string );
		foreach( $parts as $part )
		{
			$parts1	= explode( "=", $part );
			$key	= array_shift( $parts1 );
			$values	= explode( ",", array_shift( $parts1 ) );
			foreach( $values as $value )
				$list[]	= $value;
		}
		return $list;
	}

	protected static function parseLine( $vcard, $line )
	{
		$partsLine	= explode( ":", $line );
		$keyFull	= array_shift( $partsLine );

		//  --  GET KEY  --  //
		$partsKey	= explode( ";", $keyFull );
		$key		= array_shift( $partsKey );

		//  --  GET KEY ATTRIBUTES  --  //
		$attributes	= implode( ";", $partsKey );
		$attributes	= self::parseAttributes( $attributes );	

		//  --  GET VALUES  --  //
		$values		= implode( ":", $partsLine );
		$values		= explode( ";", $values );	

		//  --  BUILD ARRAY(10) FOR VALUE FIELDS  --  //
		$list	= array();
		for( $i=0; $i<10; $i++ )
			$list[$i]	= NULL;
		for( $i=0; $i<count( $values); $i++ )
			$list[$i]	= $values[$i];
		$values	= $list;

		//  --  FILL VCARD OBJECT  --  //
		switch( strtolower( $key ) )
		{
			case 'n':
				$vcard->setName(
					$values[0],
					$values[1],
					$values[2],
					$values[3],
					$values[4]
				);
				break;
			case 'fn':
				$vcard->setFormatedName( $values[0] );
				break;
			case 'email':
				$vcard->addEmail( $values[0], $attributes );
				break;
			case 'geo':
				$vcard->addGeoTag( $values[0], $values[1], $attributes );
				break;
			case 'org':
				$vcard->setOrganisation( $values[0], $values[1] );
				break;
			case 'title':
				$vcard->setTitle( $values[0] );
				break;
			case 'nickname':
				$vcard->addNickname( $values[0] );
				break;
			case 'role':
				$vcard->setRole( $values[0] );
				break;
			case 'url':
				$vcard->addUrl( $values[0], $attributes );
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
					$values[0],
					$attributes
				);
				break;

		}
	}
}
?>