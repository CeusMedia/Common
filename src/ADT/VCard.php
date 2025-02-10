<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Data Object for vCard.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			https://www.ietf.org/rfc/rfc2426.txt
 */

namespace CeusMedia\Common\ADT;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\FS\File\VCard\Builder as VCardFileBuilder;
use CeusMedia\Common\FS\File\VCard\Parser as VCardFileParser;
use InvalidArgumentException;

/**
 *	Data Object for vCard.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			https://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 */
class VCard
{
	/**	@var		array		$types					Array of VCard Types (Entities) */
	private array $types;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->types	= [
			'adr'		=> [],
			'email'		=> [],
			'fn'		=> NULL,
			'geo'		=> [],
			'n'			=> [
				'familyName'		=> NULL,
				'givenName'			=> NULL,
				'additionalNames'	=> NULL,
				'honorificPrefixes'	=> NULL,
				'honorificSuffixes'	=> NULL,
			],
			'nickname'	=> [],
			'org'			=> [
				'name'		=> NULL,
				'unit'		=> NULL
			],
			'role'		=> NULL,
			'tel'			=> [],
			'title'		=> NULL,
			'url'			=> [],
		];
	}

	/**
	 *	@return		array
	 */
	public function __serialize(): array
	{
		return $this->types;
	}

	public function __unserialize( array $data ): void
	{
		$this->types = $data;
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
	 *	@param		string|NULL	$postOfficeBox			Post Office Box ID
	 *	@param		array		$types					List of Address Types
	 *	@return		self
	 */
	public function addAddress( string $streetAddress, string $extendedAddress, string $locality, string $region, string $postCode, string $countryName, ?string $postOfficeBox = NULL, array $types = [] ): self
	{
		$this->types['adr'][]	= [
			'postOfficeBox'		=> $postOfficeBox,
			'extendedAddress'	=> $extendedAddress,
			'streetAddress'		=> $streetAddress,
			'locality'			=> $locality,
			'region'			=> $region,
			'postCode'			=> $postCode,
			'countryName'		=> $countryName,
			'types'				=> $types,
		];
		return $this;
	}

	/**
	 *	Adds an Email Address.
	 *	@access		public
	 *	@param		string		$address				Email Address
	 *	@param		array		$types					List of Address Types
	 *	@return		self
	 */
	public function addEmail( string $address, array $types = [] ): self
	{
		$this->types['email'][$address]	= $types;
		return $this;
	}

	/**
	 *	Adds Geo Tags.
	 *	@access		public
	 *	@param		string		$latitude				Latitude
	 *	@param		string		$longitude				Longitude
	 *	@param		array		$types					List of Address Types
	 *	@return		self
	 */
	public function addGeoTag( string $latitude, string $longitude, array $types = [] ): self
	{
		$this->types['geo'][]	= [
			'latitude'	=> $latitude,
			'longitude'	=> $longitude,
			'types'		=> $types,
		];
		return $this;
	}

	/**
	 *	Adds a Nickname.
	 *	@access		public
	 *	@param		string		$name					Nickname
	 *	@return		self
	 */
	public function addNickname( string $name ): self
	{
		$this->types['nickname'][]	= $name;
		return $this;
	}

	/**
	 *	Adds a Phone Number.
	 *	@access		public
	 *	@param		string		$number					Phone Number
	 *	@param		array		$types					List of Address Types
	 *	@return		self
	 */
	public function addPhone( string $number, array $types = [] ): self
	{
		$this->types['tel'][$number]	= $types;
		return $this;
	}

	/**
	 *	Adds a URL of a Website.
	 *	@access		public
	 *	@param		string		$url					Website URL
	 *	@param		array		$types					List of Address Types
	 *	@return		self
	 */
	public function addUrl( string $url, array $types = [] ): self
	{
		$this->types['url'][$url]	= $types;
		return $this;
	}

	/**
	 *	Creates and returns a new VCard from a Serial.
	 *	@access		public
	 *	@static
	 *	@param		string		$string					Serialized VCard String
	 *	@return		VCard
	 */
	public static function createFromString( string $string ): self
	{
		return VCardFileParser::parse( $string );
	}

	/**
	 *	Imports VCard from JSON String.
	 *	@access		public
	 *	@param		string		$json					JSON String
	 *	@return		void
	 */
	public function fromJson( string $json ): void
	{
		self::__construct();
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
	public function fromString( string $string ): void
	{
		self::__construct();
		VCardFileParser::parseInto( $string, $this );
	}

	/**
	 *	Returns a List of stored Addresses.
	 *	@access		public
	 *	@return		array
	 */
	public function getAddresses(): array
	{
		return $this->types['adr'];
	}

	/**
	 *	Returns a List of stored Email Addresses.
	 *	@access		public
	 *	@return		array
	 */
	public function getEmails(): array
	{
		return $this->types['email'];
	}

	/**
	 *	Returns stored formatted Name Fields as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getFormattedName(): array
	{
		return $this->types['fn'];
	}

	/**
	 *	Returns a List of stored Geo Tags.
	 *	@access		public
	 *	@return		array
	 */
	public function getGeoTags(): array
	{
		return $this->types['geo'];
	}

	/**
	 *	Returns a specific Name Field by its Key.
	 *	@access		public
	 *	@param		string		$key					Field Key
	 *	@return		string
	 */
	public function getNameField( string $key ):string
	{
		if( !array_key_exists( $key, $this->types['n'] ) )
			throw new InvalidArgumentException( 'Name Key "'.$key.'" is invalid.' );
		return $this->types['n'][$key];
	}

	/**
	 *	Returns stored formatted Name Fields as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getNameFields(): array
	{
		return $this->types['n'];
	}

	/**
	 *	Returns a List of stored Nicknames.
	 *	@access		public
	 *	@return		array
	 */
	public function getNicknames(): array
	{
		return $this->types['nickname'];
	}

	/**
	 *	Returns a specific Organisation Field by its Key.
	 *	@access		public
	 *	@param		string		$key					Field Key
	 *	@return		string
	 */
	public function getOrganisationField( string $key ): string
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
	public function getOrganisationFields(): array
	{
		return $this->types['org'];
	}

	/**
	 *	Returns stored Phone Numbers as Array of Number and Types.
	 *	@access		public
	 *	@return		array
	 */
	public function getPhones(): array
	{
		return $this->types['tel'];
	}

	/**
	 *	Returns the stored Person's Role.
	 *	@access		public
	 *	@return		string
	 */
	public function getRole(): string
	{
		return $this->types['role'];
	}

	/**
	 *	Returns the stored Person's Title.
	 *	@access		public
	 *	@return		string
	 */
	public function getTitle(): string
	{
		return $this->types['title'];
	}

	/**
	 *	Returns a List of stored Website URLs.
	 *	@access		public
	 *	@return		array
	 */
	public function getUrls(): array
	{
		return $this->types['url'];
	}

	/**
	 *	Exports VCard to a Serial String.
	 *	Alias for toString().
	 *	@access		public
	 *	@return		string
	 */
	public function serialize(): string
	{
		return $this->toString();
	}


	/**
	 *	Sets Name a one formatted String.
	 *	@access		public
	 *	@param		string		$formattedName			Name String
	 *	@return		self
	 */
	public function setFormattedName( string $formattedName ): self
	{
		$this->types['fn']	= $formattedName;
		return $this;
	}

	/**
	 *	Sets Name with several Fields.
	 *	@access		public
	 *	@param		string			$familyName				Family Name
	 *	@param		string			$givenName				Given first Name
	 *	@param		string|NULL		$additionalNames		Further given Names
	 *	@param		string|NULL		$honorificPrefixes		Prefixes like Prof. Dr.
	 *	@param		string|NULL		$honorificSuffixes		Suffixes
	 *	@return		self
	 */
	public function setName( string $familyName, string $givenName, ?string $additionalNames = NULL, ?string $honorificPrefixes = NULL, ?string $honorificSuffixes = NULL ): self
	{
		$this->types['n']	= [
			'familyName'		=> $familyName,
			'givenName'			=> $givenName,
			'additionalNames'	=> $additionalNames,
			'honorificPrefixes'	=> $honorificPrefixes,
			'honorificSuffixes'	=> $honorificSuffixes,
		];
		return $this;
	}

	/**
	 *	Sets Organisation Name and Unit.
	 *	@access		public
	 *	@param		string			$name					Organisation Name
	 *	@param		string|NULL		$unit					Organisation Unit
	 *	@return		self
	 */
	public function setOrganisation( string $name, ?string $unit = NULL ): self
	{
		$this->types['org']	= [
			'name'		=> $name,
			'unit'		=> $unit,
		];
		return $this;
	}

	/**
	 *	Sets a Person's Role.
	 *	@access		public
	 *	@param		string		$role					Person's Role within Organisation
	 *	@return		self
	 */
	public function setRole( string $role ): self
	{
		$this->types['role']	= $role;
		return $this;
	}

	/**
	 *	Sets a Person's Title.
	 *	@access		public
	 *	@param		string		$title					Person's Title
	 *	@return		self
	 */
	public function setTitle( string $title ): self
	{
		$this->types['title']	= $title;
		return $this;
	}

	/**
	 *	Exports VCard to an Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		return [
			'address'		=> $this->types['adr'],
			'email'			=> $this->types['email'],
			'formattedName'	=> $this->types['fn'],
			'geo'			=> $this->types['geo'],
			'name'			=> $this->types['n'],
			'nickname'		=> $this->types['nickname'],
			'organisation'	=> $this->types['org'],
			'role'			=> $this->types['role'],
			'telephone'		=> $this->types['tel'],
			'title'			=> $this->types['title'],
			'url'			=> $this->types['url'],
		];
	}

	/**
	 *	Exports VCard to a JSON String.
	 *	@access		public
	 *	@return		string
	 */
	public function toJson(): string
	{
		return JsonEncoder::create()->encode( $this->types );
	}

	/**
	 *	Exports VCard to a String.
	 *	@access		public
	 *	@param		string|NULL		$charsetIn				Charset to convert from
	 *	@param		string|NULL		$charsetOut				Charset to convert to
	 *	@return		string
	 */
	public function toString( ?string $charsetIn = NULL, ?string $charsetOut = NULL ): string
	{
		return VCardFileBuilder::build( $this, $charsetIn, $charsetOut );
	}

	/**
	 *	Imports VCard from Serial String.
	 *	Alias for fromString().
	 *	@access		public
	 *	@param		string		$data					Serialized VCard String
	 *	@return		void
	 */
	public function unserialize( string $data ): void
	{
		$this->fromString( $data );
	}
}
