<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Resolves an address to geocodes using Google Maps API.
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
 *	@package		CeusMedia_Common_Net_API_Google_Maps
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\API\Google\Maps;

use CeusMedia\Common\Net\API\Google\Request as GoogleRequest;
use CeusMedia\Common\FS\File\Editor as FileEditor;
use CeusMedia\Common\XML\Element as XmlElement;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Resolves an address to geocodes using Google Maps API.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API_Google_Maps
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Geocoder extends GoogleRequest
{
	/** @var		string		$apiUrl			Google Maps API URL */
	public string $apiUrl				= "https://maps.googleapis.com/maps/api/geocode/xml";

	/**
	 *	Returns KML data for an address.
	 *	@access		public
	 *	@param		string		$address		Address to get data for
	 *	@param		bool		$force			Flag: do not use cache
	 *	@return		string
	 *	@throws		RuntimeException			if query limit is reached
	 *	@throws		InvalidArgumentException	if address could not been resolved
	 *	@throws		Exception
	 */
	public function getGeoCode( string $address, bool $force = FALSE ): string
	{
		$address	= urlencode( $address );
		$query		= "?address=".$address."&sensor=false";
		$cacheFile	= NULL;
		if( $this->pathCache ){
			$cacheFile	= $this->pathCache.$address.".xml.cache";
			if( file_exists( $cacheFile ) && !$force )
				return FileEditor::load( $cacheFile );
		}
		$xml	= $this->sendQuery( $query );
		$doc	= new XmlElement( $xml );
		if( $doc->status->getValue() === "OVER_QUERY_LIMIT" )
			throw new RuntimeException( 'Query limit reached' );
		if( !@$doc->result->geometry->location )
			throw new InvalidArgumentException( 'Address not found' );
		if( $this->pathCache )
			FileEditor::save( $cacheFile, $xml );
		return $xml;
	}

	/**
	 *	Returns longitude, latitude and accuracy for an address.
	 *	@access		public
	 *	@param		string		$address		Address to get data for
	 *	@param		bool		$force			Flag: do not use cache
	 *	@return		array
	 *	@throws		RuntimeException			if query limit is reached
	 *	@throws		InvalidArgumentException	if address could not been resolved
	 *	@throws		Exception
	 */
	public function getGeoTags( string $address, bool $force = FALSE ): array
	{
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XmlElement( $xml );
//		$coordinates	= (string) $xml->result->geometry->location;
//		$parts			= explode( ",", $coordinates );
		return [
			'longitude'	=> (string) $xml->result->geometry->location->lng,
			'latitude'	=> (string) $xml->result->geometry->location->lat,
			'accuracy'	=> NULL,
		];
	}

	/**
	 *	...
	 *	@param		string		$address
	 *	@param		bool		$force
	 *	@return		string
	 *	@throws		RuntimeException
	 *	@throws		Exception
	 */
	public function getAddress( string $address, bool $force = FALSE ): string
	{
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XmlElement( $xml );
		if( (string) $xml->status !== "OK" )
			throw new RuntimeException( 'Address not found: '.$address );
		return (string) $xml->result->formatted_address;
	}
}
