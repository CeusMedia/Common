<?php
/**
 *	Data Object for vCard.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 */
/**
 *	Data Object for vCard.
 *	@category		cmClasses
 *	@package		ADT
 *	@implements		Serializable
 *	@uses			File_VCard_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 */
class ADT_VCard implements Serializable
{
	/**	@var		array		$types					Array of VCard Types (Entities) */
	private $types	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->types	= array(
			'adr'		=> array(),
			'email'		=> array(),
			'fn'		=> NULL,
			'geo'		=> array(),
			'n'			=> array(
				'familyName'		=> NULL,
				'givenName'			=> NULL,
				'additionalNames'	=> NULL,
				'honorificPrefixes'	=> NULL,
				'honorificSuffixes'	=> NULL,
			),
			'nickname'	=> array(),
			'org'		=> array(
				'name'		=> NULL,
				'unit'		=> NULL
			),
			'role'		=> NULL,
			'tel'		=> array(),
			'title'		=> NULL,
			'url'		=> array(),
		);
	}

	/**
	 *	Adds an Address.
	 *	@access		public
	 *	@param		string		$streetAddress			Street and Number
	 *	@param		string		$extendedAddress		...
	 *	@param		string		$locality				City or Location
	 *	@param		string		$region					Region or State
	 *	@param		string		$postCode				Post Code
	 *	@param		string		$countryName			Country
	 *	@param		string		$postOfficeBox			Post Office Box ID
	 *	@param		array		$types					List of Address Types
	 *	@return		void
	 */
	public function addAddress( $streetAddress, $extendedAddress, $locality, $region, $postCode, $countryName, $postOfficeBox = NULL, $types = NULL )
	{
		if( is_string( $types ) )
			$types	= explode( ",", $types );
		$this->types['adr'][]	= array(
			'postOfficeBox'		=> $postOfficeBox,
			'extendedAddress'	=> $extendedAddress,
			'streetAddress'		=> $streetAddress,
			'locality'			=> $locality,
			'region'			=> $region,
			'postCode'			=> $postCode,
			'countryName'		=> $countryName,
			'types'				=> $types,
		);
	}

	/**
	 *	Adds an Email Address.
	 *	@access		public
	 *	@param		string		$address				Email Address
	 *	@param		array		$types					List of Address Types
	 *	@return		void
	 */
	public function addEmail( $address, $types = NULL )
	{
		if( is_string( $types ) )
			$types	= explode( ",", $types );
		$this->types['email'][$address]	= $types;
	}

	/**
	 *	Adds Geo Tags.
	 *	@access		public
	 *	@param		string		$latitude				Latitude
	 *	@param		string		$longitude				Longitude
	 *	@param		array		$types					List of Address Types
	 *	@return		void
	 */
	public function addGeoTag( $latitude, $longitude, $types = NULL )
	{
		if( is_string( $types ) )
			$types	= explode( ",", $types );
		$this->types['geo'][]	= array(
			'latitude'	=> $latitude,
			'longitude'	=> $longitude,
			'types'		=> $types,
		);
	}

	/**
	 *	Adds a Nickname.
	 *	@access		public
	 *	@param		string		$name					Nickname
	 *	@return		void
	 */
	public function addNickname( $name )
	{
		$this->types['nickname'][]	= $name;
	}
	
	/**
	 *	Adds a Phone Number.
	 *	@access		public
	 *	@param		string		$number					Phone Number
	 *	@param		array		$types					List of Address Types
	 *	@return		void
	 */
	public function addPhone( $number, $types = NULL )
	{
		if( is_string( $types ) )
			$types	= explode( ",", $types );
		$this->types['tel'][$number]	= $types;
	}

	/**
	 *	Adds an URL of a Website.
	 *	@access		public
	 *	@param		string		$url					Website URL
	 *	@param		array		$types					List of Address Types
	 *	@return		void
	 */
	public function addUrl( $url, $types = NULL )
	{
		if( is_string( $types ) )
			$types	= explode( ",", $types );
		$this->types['url'][$url]	= $types;
	}

	/**
	 *	Creates and returns a new VCard from a Serial.
	 *	@access		public
	 *	@static
	 *	@param		string		$string					Serialized VCard String
	 *	@return		ADT_VCard
	 */
	public static function createFromString( $string )
	{
		return File_VCard_Parser::parse( $string );
	}

	/**
	 *	Imports VCard from JSON String.
	 *	@access		public
	 *	@param		string		$json					JSON String
	 *	@return		void
	 */
	public function fromJson( $json )
	{
		$this->__construct();
		$data	= json_decode( $json, TRUE );
		foreach( $this->types as $key => $value )
			if( isset( $data[$key] ) )
				$this->types[$key]	= $data[$key];
	}

	/**
	 *	Imports VCard from Serial String.
	 *	@access		public
	 *	@param		string		$string			Serialized VCard String
	 *	@return		void
	 */
	public function fromString( $string )
	{
		$this->__construct();
		File_VCard_Parser::parseInto( $string, $this );
	}

	/**
	 *	Returns a List of stored Addresses.
	 *	@access		public
	 *	@return		array
	 */
	public function getAddresses()
	{
		return $this->types['adr'];
	}

	/**
	 *	Returns a List of stored Email Addresses.
	 *	@access		public
	 *	@return		array
	 */
	public function getEmails()
	{
		return $this->types['email'];
	}

	/**
	 *	Returns stored formated Name Fields as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getFormatedName()
	{
		return $this->types['fn'];
	}

	/**
	 *	Returns a List of stored Geo Tags.
	 *	@access		public
	 *	@return		array
	 */
	public function getGeoTags()
	{
		return $this->types['geo'];	
	}

	/**
	 *	Returns a specific Name Field by its Key.
	 *	@access		public
	 *	@param		string		$key					Field Key
	 *	@return		string
	 */
	public function getNameField( $key )
	{
		if( !array_key_exists( $key, $this->types['n'] ) )
			throw new InvalidArgumentException( 'Name Key "'.$key.'" is invalid.' );
		return $this->types['n'][$key];
	}

	/**
	 *	Returns stored formated Name Fields as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getNameFields()
	{
		return $this->types['n'];
	}

	/**
	 *	Returns a List of stored Nicknames.
	 *	@access		public
	 *	@return		array
	 */
	public function getNicknames()
	{
		return $this->types['nickname'];	
	}

	/**
	 *	Returns a specific Organisation Field by its Key.
	 *	@access		public
	 *	@param		string		$key					Field Key
	 *	@return		string
	 */
	public function getOrganisationField( $key )
	{
		if( !array_key_exists( $key, $this->types['org'] ) )
			throw new InvalidArgumentException( 'Organisation Key "'.$key.'" is invalid.' );
		return $this->types['org'][$key];
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		array
	 */
	public function getOrganisationFields()
	{
		return $this->types['org'];
	}

	/**
	 *	Returns stored Phone Numbers as Array of Number and Types.
	 *	@access		public
	 *	@return		array
	 */
	public function getPhones()
	{
		return $this->types['tel'];
	}

	/**
	 *	Returns the stored Person's Role.
	 *	@access		public
	 *	@return		string
	 */
	public function getRole()
	{
		return $this->types['role'];
	}

	/**
	 *	Returns the stored Person's Title.
	 *	@access		public
	 *	@return		string
	 */
	public function getTitle()
	{
		return $this->types['title'];
	}

	/**
	 *	Returns a List of stored Website URLs.
	 *	@access		public
	 *	@return		array
	 */
	public function getUrls()
	{
		return $this->types['url'];
	}

	/**
	 *	Exports VCard to a Serial String.
	 *	Alias for toString().
	 *	@access		public
	 *	@return		string
	 */
	public function serialize()
	{
		return $this->toString();
	}

	/**
	 *	Sets Name a one formated String.
	 *	@access		public
	 *	@param		string		$formatedName			Name String
	 *	@return		void
	 */
	public function setFormatedName( $formatedName )
	{
		$this->types['fn']	= $formatedName;
	}

	/**
	 *	Sets Name with several Fields.
	 *	@access		public
	 *	@param		string		$familyName				Family Name
	 *	@param		string		$givenName				Given first Name
	 *	@param		string		$additionalNames		Further given Names
	 *	@param		string		$honorificPrefixes		Prefixes like Prof. Dr.
	 *	@param		string		$honorificSuffixes		Suffixes
	 *	@return		void
	 */
	public function setName( $familyName, $givenName, $additionalNames = NULL, $honorificPrefixes = NULL, $honorificSuffixes = NULL )
	{
		$this->types['n']	= array(
			'familyName'		=> $familyName,
			'givenName'			=> $givenName,
			'additionalNames'	=> $additionalNames,
			'honorificPrefixes'	=> $honorificPrefixes,
			'honorificSuffixes'	=> $honorificSuffixes,
		);
	}
	
	/**
	 *	Sets Organisation Name and Unit.
	 *	@access		public
	 *	@param		string		$name					Organisation Name
	 *	@param		string		$unit					Organisation Unit
	 *	@return		void
	 */
	public function setOrganisation( $name, $unit = NULL )
	{
		$this->types['org']	= array(
			'name'		=> $name,
			'unit'		=> $unit,
		);
	}

	/**
	 *	Sets a Person's Role.
	 *	@access		public
	 *	@param		string		$role					Person's Role within Organisation
	 *	@return		void
	 */
	public function setRole( $role )
	{
		$this->types['role']	= $role;
	}

	/**
	 *	Sets a Person's Title.
	 *	@access		public
	 *	@param		string		$title					Person's Title
	 *	@return		void
	 */
	public function setTitle( $title )
	{
		$this->types['title']	= $title;
	}

	/**
	 *	Exports VCard to an Array.
	 *	@access		public
	 *	@return		string
	 */
	public function toArray()
	{
		return array(
			'address'		=> $this->types['adr'],
			'email'			=> $this->types['email'],
			'formatedName'	=> $this->types['fn'],
			'geo'			=> $this->types['geo'],
			'name'			=> $this->types['n'],
			'nickname'		=> $this->types['nickname'],
			'organisation'	=> $this->types['org'],
			'role'			=> $this->types['role'],
			'telephone'		=> $this->types['tel'],
			'title'			=> $this->types['title'],
			'url'			=> $this->types['url'],
		);
	}

	/**
	 *	Exports VCard to a JSON String.
	 *	@access		public
	 *	@return		string
	 */
	public function toJson()
	{
		return json_encode( $this->types );
	}

	/**
	 *	Exports VCard to a String.
	 *	@access		public
	 *	@param		string		$charsetIn				Charset to convert from
	 *	@param		string		$charsetOut				Charset to convert to
	 *	@return		string
	 */
	public function toString( $charsetIn = NULL, $charsetOut = NULL )
	{
		return File_VCard_Builder::build( $this, $charsetIn, $charsetOut );
	}

	/**
	 *	Imports VCard from Serial String.
	 *	Alias for fromString().
	 *	@access		public
	 *	@param		string		$string					Serialized VCard String
	 *	@return		void
	 */
	public function unserialize( $string )
	{
		$this->fromString( $string );
	}
}
?>