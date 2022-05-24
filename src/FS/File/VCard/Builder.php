<?php
/**
 *	Builds vCard String from vCard Data Object.
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
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			02.09.2008
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 */
/**
 *	Builds vCard String from vCard Data Object.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			02.09.2008
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
class FS_File_VCard_Builder
{
	public static $version	= "3.0";
	public static $prodId	= "";

	/**
	 *	Builds vCard String from vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		ADT_VCard	$card			VCard Data Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public static function build( ADT_VCard $card, $charsetIn = NULL, $charsetOut = NULL )
	{
		$lines		= array();

		//  NAME FIELDS
		if( $fields	= $card->getNameFields() )
			$lines[]	= self::renderLine( "n", $fields );

		//  ADDRESSES
		foreach( $card->getAddresses() as $address )
			$lines[]	= self::renderLine( "adr", $address, $address['types'] );

		//  FORMATED NAME
		if( $name	= $card->getFormatedName() )
			$lines[]	= self::renderLine( "fn", $name );

		//  NICKNAMES
		if( $nicknames = $card->getNicknames() )
			$lines[]	= self::renderLine( "nickname", $nicknames, NULL, TRUE, "," );

		//  ORGANISATION
		if( $fields	= $card->getOrganisationFields() )
			$lines[]	= self::renderLine( "org", $fields, NULL, TRUE );

		//  TITLE
		if( $title	= $card->getTitle() )
			$lines[]	= self::renderLine( "title", $title );

		//  ROLE
		if( $role	= $card->getRole() )
			$lines[]	= self::renderLine( "role", $role );

		//  EMAIL ADDRESSES
		foreach( $card->getEmails() as $address => $types )
			$lines[]	= self::renderLine( "email", $address, $types );

		//  URLS
		foreach( $card->getUrls() as $url => $types )
			$lines[]	= self::renderLine( "url", $url, $types, FALSE );

		//  PHONES
		foreach( $card->getPhones() as $number => $types )
			$lines[]	= self::renderLine( "tel", $number, $types );

		//  GEO TAGS
		foreach( $card->getGeoTags() as $geo )
			$lines[]	= self::renderLine( "geo", $geo, $geo['types'] );

		if( self::$prodId )
			array_unshift( $lines, "PRODID:".self::$prodId );
		if( self::$version )
			array_unshift( $lines, "VERSION:".self::$version );
		array_unshift( $lines, "BEGIN:VCARD" );
		array_push( $lines, "END:VCARD" );
		$lines	= implode( "\n", $lines );
		if( $charsetIn && $charsetOut )
		{
			$lines	= Alg_Text_EncodingConverter::convert( $lines, $charsetIn, $charsetOut );
		}
		return $lines;
	}

	protected static function escape( $value )
	{
		$value	= str_replace( ",", "\,", $value );
		$value	= str_replace( ";", "\;", $value );
		$value	= str_replace( ":", "\:", $value );
		return $value;
	}

	protected static function renderLine( $name, $values, $types = NULL, $escape = TRUE, $delimiter = ";" )
	{
		$type	= $types ? ";TYPE=".implode( ",", $types ) : "";
		$name	= strtoupper( $name );
		if( is_array( $values ) )
		{
			if( $escape )
			{
				$list	= array();
				foreach( $values as $key => $value )
					if( $key !== "types" )
						$list[]	= self::escape( $value );
				$values	= $list;
			}
			$values	= implode( $delimiter, $values );
		}
		else if( $escape )
			$values	= self::escape( $values );
		$line	= $name.$type.":".$values;
		return $line;
	}
}
