<?php
import( 'de.ceus-media.net.google.api.Request' );
import( 'de.ceus-media.xml.Element' );
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