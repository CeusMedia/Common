<?php
/**
 *	...
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
 *	@package		net.google.api
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
import( 'de.ceus-media.net.google.api.Request' );
import( 'de.ceus-media.xml.Element' );
/**
 *	...
 *	@package		net.google.api
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
class Net_Google_API_Geocoder extends Net_Google_API_Request
{
	public function __construct( $apiKey, $apiUrl = "http://maps.google.com/maps/geo" )
	{
		parent::__construct( $apiKey, $apiUrl );
		$this->pathCache	= $this->pathCache."geocodes/";
	}

	public function getGeoCode( $address, $force = FALSE )
	{
		$address	= urlencode( $address );
		$cacheFile	= $this->pathCache.$address.".xml.cache";
		if( file_exists( $cacheFile ) && !$force )
			return file_get_contents( $cacheFile );
		$query	= "?q=".$address."&sensor=false&output=xml";
		$xml	= $this->sendQuery( $query );
		file_put_contents( $cacheFile, $xml );
		return $xml;
	}
	
	public function getGeoTags( $address, $force = FALSE )
	{
		$xml	= $this->getGeoCode( $address, $force );
		$xml	= new XML_Element( $xml );
		if( !@$xml->Response->Placemark->Point->coordinates )
			throw new RuntimeException( 'Address not found.' );
		$coordinates	= (string) $xml->Response->Placemark->Point->coordinates;
		$parts			= explode( ",", $coordinates );
		$data			= array(
			'latitude'	=> $parts[1],
			'longitude'	=> $parts[0],
			'accuracy'	=> $parts[2],
		);
		return $parts;
	}
}
?>