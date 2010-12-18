<?php
/**
 *	Parser for HTTP Response containing Headers and Body.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		Net.HTTP.Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		cmClasses
 *	@package		Net.HTTP.Response
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class Net_HTTP_Response_Sender
{
	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response	Response Object
	 *	@return		void
	 */
	public function  __construct( Net_HTTP_Response $response )
	{
		$this->response	= $response;
	}

	/**
	 *	Send Response.
	 *	@access		public
	 *	@param		string		$compression		Type of compression (gzip|deflate)
	 *	@param		boolean		$sendLengthHeader	Send Content-Length Header
	 *	@return		integer		Number of sent Bytes
	 */
	public function send( $compression = NULL, $sendLengthHeader = TRUE )
	{
		$length		= strlen( $this->response->getBody() );
		if( $compression )
		{
			$compressor	= new Net_HTTP_Response_Compressor;
			$compressor->compressResponse( $this->response, $compression, $sendLengthHeader );
			$lengthNow	= strlen( $this->response->getBody() );
			$ratio		= round( $lengthNow / $length * 100 );
		}

		foreach( $this->response->getHeaders() as $header )
			header( $header->toString() );
		print( $this->response->getBody() );
		flush();
		return strlen( $this->response->getBody() );
	}

	/**
	 *	Send Response statically.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response			Response Object
	 *	@param		string				$compression		Type of compression (gzip|deflate)
	 *	@param		boolean				$sendLengthHeader	Send Content-Length Header
	 *	@return		integer				Number of sent Bytes
	 */
	public static function sendResponse( Net_HTTP_Response $response, $compression = NULL, $sendLengthHeader = NULL )
	{
		$sender	= new Net_HTTP_Response_Sender( $response );
		return $sender->send( $compression, $sendLengthHeader );
	}
}
?>
