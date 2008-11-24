<?php
import( 'de.ceus-media.net.Reader' );
class Net_Google_API_Request
{
	public $apiKey		= "";
	public $apiUrl		= "";
	public $pathCache	= "cache/";

	public function __construct( $apiKey, $apiUrl = "http://maps.google.com/maps/geo" )
	{
		$this->apiKey	= $apiKey;
		$this->apiUrl	= $apiUrl;
	}
	
	protected function sendQuery( $query )
	{
		$query		.= "&key=".$this->apiKey;
		$url		= $this->apiUrl.$query;
		$response	= Net_Reader::readUrl( $url );
		$response	= utf8_encode( $response );
		return $response;
	}
}
?>