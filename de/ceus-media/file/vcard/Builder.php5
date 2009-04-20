<?php
/**
 *	Builds vCard String from vCard Data Object.
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
 *	@package		file.vcard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.09.2008
 *	@version		0.1
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
/**
 *	Builds vCard String from vCard Data Object.
 *
 *	@package		file.vcard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.09.2008
 *	@version		0.1
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
class File_VCard_Builder
{
	public static $version	= "3.0";
	public static $prodId	= "";

	/**
	 *	Builds vCard String from vCard Object and converts between Charsets.
	 *	@access		public
	 *	@param		ADT_VCard	$card
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public static function build( ADT_VCard $card, $charsetIn, $charsetOut )
	{
		$lines		= array();

		if( $fields	= $card->getNameFields() )											//  NAME FIELDS
			$lines[]	= self::renderLine( "n", $fields );

		foreach( $card->getAddresses() as $address )									//  ADDRESSES
			$lines[]	= self::renderLine( "adr", $address, $address['types'] );

		if( $name	= $card->getFormatedName() )										//  FORMATED NAME
			$lines[]	= self::renderLine( "fn", $name );

		if( $nicknames = $card->getNicknames() )										//  NICKNAMES
			$lines[]	= self::renderLine( "nickname", $nicknames, NULL, TRUE, "," );

		if( $fields	= $card->getOrganisationFields() )									//  ORGANISATION
			$lines[]	= self::renderLine( "org", $fields, NULL, TRUE );

		if( $title	= $card->getTitle() )												//  TITLE
			$lines[]	= self::renderLine( "title", $title );

		if( $role	= $card->getRole() )												//  ROLE
			$lines[]	= self::renderLine( "role", $role );

		foreach( $card->getEmails() as $address => $types )								//  EMAIL ADDRESSES
			$lines[]	= self::renderLine( "email", $address, $types );

		foreach( $card->getUrls() as $url => $types )									//  URLS
			$lines[]	= self::renderLine( "url", $url, $types, FALSE );

		foreach( $card->getPhones() as $number => $types )								//  PHONES
			$lines[]	= self::renderLine( "tel", $number, $types );
		
		foreach( $card->getGeoTags() as $geo )											//  GEO TAGS
			$lines[]	= self::renderLine( "geo", $geo, $geo['types'] );
		
		if( self::$prodId )
			array_unshift( $lines, "PRODID:".self::$prodId );
		if( self::$version )
			array_unshift( $lines, "VERSION:".self::$version );
		array_unshift( $lines, "BEGIN:VCARD" );
		array_push( $lines, "END:VCARD" );
		$lines	= implode( "\n", $lines );
		if( $charsetIn && $charsetOut && function_exists( 'iconv' ) )
			$lines	= iconv( $charsetIn, $charsetOut, $lines );
		return $lines;
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
	
	protected static function escape( $value )
	{
		$value	= str_replace( ",", "\,", $value );
		$value	= str_replace( ";", "\;", $value );
		$value	= str_replace( ":", "\:", $value );
		return $value;
	}
}
?>