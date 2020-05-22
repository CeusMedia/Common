<?php
/**
 *	Resolves an address to geo codes using Google Maps API.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API_Google_Maps
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.5
 *	@version		$Id$
 */
/**
 *	Resolves an address to geo codes using Google Maps API.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API_Google_Maps
 *	@extends		Net_API_Google_Request
 *	@uses			XML_Element
 *	@uses			FS_File_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.5
 *	@version		$Id$
 */
class Net_API_Google_Maps_Geocoder extends Net_API_Google_Request
{
	/** @var		string		$apiUrl			Google Maps API URL */
	public $apiUrl				= "http://maps.googleapis.com/maps/api/geocode/xml";

	/**
	 *	Returns KML data for an address.
	 *	@access		public
	 *	@param		string		$address		Address to get data for
	 *	@param		bool		$force			Flag: do not use cache
	 *	@return		string
	 *	@throws		RuntimeException			if query limit is reached
	 *	@throws		InvalidArgumentException	if address could not been resolved
	 */
	public function getGeoCode( $address, $force = FALSE )
	{
		$address	= urlencode( $address );
		$query		= "?address=".$address."&sensor=false";
		if( $this->pathCache )
		{
			$cacheFile	= $this->pathCache.$address.".xml.cache";
			if( file_exists( $cacheFile ) && !$force )
				return File_Editor::load( $cacheFile );
		}
		$xml	= $this->sendQuery( $query );
		$doc	= new XML_Element( $xml );
		if( $doc->status->getValue() === "OVER_QUERY_LIMIT" )
			throw new RuntimeException( 'Query limit reached' );
		if( !@$doc->result->geometry->location )
			throw new InvalidArgumentException( 'Address not found' );
		if( $this->pathCache )
			File_Editor::save( $cacheFile, $xml );
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
	 */
	public function getGeoTags( $address, $force = FALSE )
	{
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XML_Element( $xml );
		$coordinates	= (string) $xml->result->geometry->location;
		$parts			= explode( ",", $coordinates );
		$data			= array(
			'longitude'	=> (string) $xml->result->geometry->location->lng,
			'latitude'	=> (string) $xml->result->geometry->location->lat,
			'accuracy'	=> NULL,
		);
		return $data;
	}

	public function getAddress( $address, $force = FALSE ){
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XML_Element( $xml );
		if( (string) $xml->status !== "OK" )
			throw new RuntimeException( 'Address not found: '.$address );
		return (string) $xml->result->formatted_address;
	}
}
?>
