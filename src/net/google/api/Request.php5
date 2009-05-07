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
import( 'de.ceus-media.net.Reader' );
/**
 *	...
 *	@package		net.google.api
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
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