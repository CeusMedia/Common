<?php
/**
 *	Parser for HTTP Response containing Headers and Body.
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
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Net_HTTP_Response_Sender
{

	/**	@var		string|NULL			$compression		Type of compression to use (gzip, deflate), default: NULL */
	protected $compression;

	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response	Response Object
	 *	@return		void
	 */
	public function  __construct( Net_HTTP_Response $response = NULL )
	{
		if( $response )
			$this->setResponse( Net_HTTP_Response $response );
	}

	/**
	 *	Send Response.
	 *	@access		public
	 *	@param		string		$compression		Type of compression (gzip|deflate)
	 *	@param		boolean		$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean		$exit				Flag: after afterwards (default: no)
	 *	@return		integer		Number of sent Bytes or exits if wished so
	 *	@todo		remove compression parameter
	 */
	public function send( $compression = NULL, $sendLengthHeader = TRUE, $exit = FALSE )
	{
		$response	= clone( $this->response );
		$body		= $response->getBody();
		$length		= method_exists( 'mb_strlen' ) ? mb_strlen( $body ) : strlen( $body );

		/*  --  COMPRESSION  --  */
		$compression	= $compression ? $compression : $this->compression;
		if( $compression )
		{
			$compressor	= new Net_HTTP_Response_Compressor;
			$compressor->compressResponse( $response, $compression, $sendLengthHeader );
			$lengthNow	= strlen( $response->getBody() );
			$ratio		= round( $lengthNow / $length * 100 );
		}
		else if( $sendLengthHeader )
			$response->addHeaderPair( 'Content-Length', $length, TRUE );

		/*  --  HTTP BASIC INFORMATION  --  */
		$status		= $response->getStatus();
		header( $response->getProtocol().'/'.$response->getVersion().' '.$status );
		header( 'Status: '.$status );

		/*  --  HTTP HEADER FIELDS  --  */
		foreach( $response->getHeaders() as $header )
			header( $header->toString(), false );

		/*  --  SEND BODY  --  */
		print( $response->getBody() );
		flush();
		if( $exit )
			exit;
		return strlen( $response->getBody() );
	}

	/**
	 *	Send Response statically.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response			Response Object
	 *	@param		string				$compression		Type of compression (gzip|deflate)
	 *	@param		boolean				$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean				$exit				Flag: after afterwards (default: no)
	 *	@return		integer				Number of sent Bytes
	 *	@todo		realize changed parameters of send method
	 */
	public static function sendResponse( Net_HTTP_Response $response, $compression = NULL, $sendLengthHeader = TRUE, $exit = FALSE )
	{
		$sender	= new Net_HTTP_Response_Sender( $response );
		$sender->setCompression( $compression );
//		return $sender->send( $sendLengthHeader, $exit );
		return $sender->send( $compression, $sendLengthHeader, $exit );
	}

	/**
	 *	Set compression to use.
	 *	@access		public
	 *	@param		string|NULL		Compression to use: gzip, deflate
	 *	@return		self
	 */
	public function setCompression( $compression )
	{
		$this->compression	= $compression;
		return $this;
	}

	/**
	 *	Set response to send
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response	Response Object
	 *	@return		self;
	 */
	public function setResponse( Net_HTTP_Response $response )
	{
		$this->response	= $response;
		return $this;
	}
}
