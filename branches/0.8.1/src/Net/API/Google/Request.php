<?php
/**
 *	Base class for request to Google APIs.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.API.Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 */
/**
 *	Base class for request to Google APIs.
 *	@category		cmClasses
 *	@package		Net.API.Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 *	@todo			finish implementation
 */
abstract class Net_API_Google_Request
{
	public $apiKey		= "";
	public $apiUrl		= "";
	public $pathCache	= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$apiKey			Google Maps API Key
	 *	@return		void
	 *	@todo		check if apiKey is still needed
	 */
	public function __construct( $apiKey )
	{
		$this->apiKey	= $apiKey;
	}
	
	protected function sendQuery( $query )
	{
		$query		.= "&key=".$this->apiKey;
		$url		= $this->apiUrl.$query;
		$response	= Net_Reader::readUrl( $url );
		$response	= utf8_encode( $response );
		return $response;
	}

	/**
	 *	Sets Cache Path.
	 *	@access		public
	 *	@param		string		$path		Path to Cache
	 *	@return		void
	 */
	public function setCachePath( $path )
	{
		$this->pathCache	= $path;
	}
}
?>