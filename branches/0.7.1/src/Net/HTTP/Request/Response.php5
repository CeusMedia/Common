<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
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
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id$
 */
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@category		cmClasses
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id$
 *	@todo			add Filter Support
 *	@deprecated		use Net_HTTP_Response_Sender and Net_HTTP_Response instead
 *	@todo			to be removed in 0.7.2
 */
class Net_HTTP_Request_Response
{
	public function __construct()
	{
		$this->response	= new Net_HTTP_Response();
	}

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$name				Name of Response Header
	 *	@param		mixed		$value 				Value of Response Header
	 *	@return		void
	 */
	public function addHeader( $name, $value )
	{
		$this->response->addHeader( new Net_HTTP_Header_Field( $name, $value ) );
	}

	/**
	 *	Sends Contents and returns Length of sent Response Content.
	 *	@access		public
	 *	@static
	 *	@param		string		$useCompression		Flag: use HTTP compression
	 *	@param		string		$compressionLogFile	File Name of Compression Log
	 *	@return		int
	 */
	public static function sendContent( $content, $useCompression = FALSE, $compressionLogFile = NULL  )
	{
		$response	= new Net_HTTP_Response();
		$response->setBody( $content );
		$sender		= new Net_HTTP_Response_Sender( $response );
		return $sender->send( $useCompression/*, $compressionLogFile*/ );
	}
	
	/**
	 *	Sends complete Response and returns Length of sent Response Content.
	 *	@access		public
	 *	@param		string		$useCompression		Flag: use HTTP compression
	 *	@param		string		$compressionLogFile	File Name of Compression Log
	 *	@return		int			Number of sent bytes
	 */
	public function send( $useCompression = FALSE, $compressionLogFile = NULL )
	{
		$sender	= new Net_HTTP_Response_Sender( $this->response );
		return $sender->send( $useCompression/*, $compressionLogFile*/ );
	}
	
	/**
	 *	Sets Status of Response.
	 *	@access		public
	 *	@param		string		$status				Status to be set
	 *	@return		void
	 */
	public function setStatus( $status )
	{
		$this->response->setStatus(	$status );
	}

	public function setContentType( $mimeType )
	{
		$this->response->addHeaderPair( 'Content-type', $mimeType );
	}
	
	/**
	 *	Writes Data to Response.
	 *	@access		public
	 *	@param		string		$body				Body string to be responsed
	 *	@return		void
	 */
	public function write( $body )
	{
		$this->response->setBody( $this->response->getBody().$body );								//  append body string
	}	
}
?>