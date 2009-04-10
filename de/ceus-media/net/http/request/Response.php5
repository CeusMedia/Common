<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		net.http.request
 *	@uses			Net_HTTP_Compression
 *	@uses			Net_HTTP_EncodingSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Compression
 *	@uses			Net_HTTP_EncodingSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		0.6
 *	@todo			add Filter Support
 */
class Net_HTTP_Request_Response
{
	/** @var		string		$status				Status of Response */
	private $status				= "200 OK";
	/** @var		array		$headers			Array of Headers */
	private $headers			= array();
	/** @var		string		$body				Body of Response */
	private $body				= "";

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$name				Name of Response Header
	 *	@param		mixed		$value 				Value of Response Header
	 *	@return		void
	 */
	public function addHeader( $name, $value )
	{
		if( !isset( $this->headers[$name] ) )
			$this->headers[$name]	= array();
		$this->headers[$name][]	= $value;
	}

	/**
	 *	Sends Contents and returns Length of sent Response Content.
	 *	@access		public
	 *	@param		string		$compression		Compression Method (deflate|gzip)
	 *	@param		string		$compressionLogFile	File Name of Compression Log
	 *	@return		int
	 */
	public static function sendContent( $content, $compression = FALSE, $compressionLogFile = NULL  )
	{
		$response	= new Net_HTTP_Request_Response();
		$response->write( $content );
		return $response->send( $compression, $compressionLogFile );
	}
	
	/**
	 *	Sends complete Response and returns Length of sent Response Content.
	 *	@access		public
	 *	@param		string		$compressionMethod	Compression Method (deflate|gzip)
	 *	@param		string		$compressionLogFile	File Name of Compression Log
	 *	@return		int
	 */
	public function send( $useCompression = FALSE, $compressionLogFile = NULL )
	{
		header( "HTTP/1.0 ".$this->status );
		foreach( $this->headers as $name => $headers )
			foreach( $headers as $header )
				header( $name.": ".$header );
		if( $useCompression )
		{
			import( 'de.ceus-media.net.http.Compression' );			
			import( 'de.ceus-media.net.http.EncodingSniffer' );			
			$compressionMethods	= Net_HTTP_Compression::getMethods();
			$compressionMethod	= Net_HTTP_Compression::getMethod();
			$compressionMethod	= Net_HTTP_EncodingSniffer::getEncoding( $compressionMethods, $compressionMethod );
			Net_HTTP_Compression::setMethod( $compressionMethod, $compressionLogFile );
			$length	= Net_HTTP_Compression::sendContent( $this->body );
			$this->headers	= array();
			$this->body		= "";
			return $length;
		}
		flush();
		$length	= strlen( $this->body );
#		header( "Content-Length: ".$length );
		print( $this->body );
		flush();
		$this->headers	= array();
		$this->body		= "";
		return $length;
	}
	
	/**
	 *	Sets Status of Response.
	 *	@access		public
	 *	@param		string		$status				Status to be set
	 *	@return		void
	 */
	public function setStatus( $status )
	{
		$this->status	= $status;
	}
	
	/**
	 *	Writes Data to Response.
	 *	@access		public
	 *	@param		string		$data				Data to be responsed
	 *	@return		void
	 */
	public function write( $data )
	{
		$this->body	.= $data;
	}	
}
?>