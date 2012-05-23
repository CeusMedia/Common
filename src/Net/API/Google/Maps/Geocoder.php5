<?php
/**
 *	Resolves an address to geo codes using Google Maps API.
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
 *	@package		Net.API.Google.Maps
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 */
/**
 *	Resolves an address to geo codes using Google Maps API.
 *	@category		cmClasses
 *	@package		Net.API.Google.Maps
 *	@extends		Net_API_Google_Request
 *	@uses			XML_Element
 *	@uses			File_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 */
class Net_API_Google_Maps_Geocoder extends Net_API_Google_Request
{
	/** @var		string		$apiUrl			Google Maps API URL */
	public $apiUrl				= "http://maps.google.com/maps/geo";

	/**
	 *	Returns KML data for an address.
	 *	@access		public
	 *	@param		string		$address		Address to get data for
	 *	@param		bool		$force			Flag: do not use cache
	 *	@return		string
	 */
	public function getGeoCode( $address, $force = FALSE )
	{
		$address	= urlencode( $address );
		$query		= "?q=".$address."&sensor=false&output=xml";
		if( $this->pathCache )
		{
			$cacheFile	= $this->pathCache.$address.".xml.cache";
			if( file_exists( $cacheFile ) && !$force )
				return File_Editor::load( $cacheFile );
		}
		$xml	= $this->sendQuery( $query );
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
	 */
	public function getGeoTags( $address, $force = FALSE )
	{
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XML_Element( $xml );
		if( !@$xml->Response->Placemark->Point->coordinates )
			throw new RuntimeException( 'Address not found.' );
		$coordinates	= (string) $xml->Response->Placemark->Point->coordinates;
		$parts			= explode( ",", $coordinates );
		$data			= array(
			'longitude'	=> $parts[0],
			'latitude'	=> $parts[1],
			'accuracy'	=> $parts[2],
		);
		return $data;
	}

	public function getAddress( $address, $force = FALSE ){
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XML_Element( $xml );
		if( !@$xml->Response->Placemark->Point->coordinates )
			throw new RuntimeException( 'Address not found.' );
		return (string) $xml->Response->Placemark->address;
	}
}
?>