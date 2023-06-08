<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for HTTP Response containing Headers and Body.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Response;

use CeusMedia\Common\Net\HTTP\Response as Response;
use CeusMedia\Common\Net\HTTP\Response\Compressor as ResponseCompressor;
use CeusMedia\Common\Net\HTTP\Response\Sender as ResponseSender;

/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Sender
{
	/**	@var		string|NULL			$compression	Type of compression to use (gzip, deflate), default: NULL */
	protected $compression;

	/**	@var		Response|NULL		$response		Response object */
	protected $response;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Response|NULL		$response	Response Object
	 *	@return		void
	 */
	public function  __construct( ?Response $response = NULL )
	{
		if( $response !== NULL )
			$this->setResponse( $response );
	}

	/**
	 *	Send Response.
	 *	@access		public
	 *	@param		boolean		$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean		$andExit			Flag: after afterwards (default: no)
	 *	@return		integer		Number of sent Bytes or exits if wished so
	 *	@todo		remove compression parameter
	 */
	public function send( bool $sendLengthHeader = TRUE, bool $andExit = FALSE ): int
	{
		$response	= clone( $this->response );
		$body		= $response->getBody();
		$length		= strlen( $body );
		if( function_exists( 'mb_strlen' ) )
			$length	= mb_strlen( $body );

		/*  --  COMPRESSION  --  */
		if( $this->compression )
			ResponseCompressor::compressResponse(
				$response,
				$this->compression,
				$sendLengthHeader
			);
		else if( $sendLengthHeader )
			$response->addHeaderPair( 'Content-Length', (string) $length, TRUE );

		/*  --  HTTP BASIC INFORMATION  --  */
		$status	= $response->getStatus();
		header( vsprintf( '%s/%s %s', [
			$response->getProtocol(),
			$response->getVersion(),
			$status
		] ) );
		header( 'Status: '.$status );

		/*  --  HTTP HEADER FIELDS  --  */
		foreach( $response->getHeaders() as $header )
			header( $header->toString(), false );

		/*  --  SEND BODY  --  */
		print( $response->getBody() );
		flush();
		if( $andExit )
			exit;
		return strlen( $response->getBody() );
	}

	/**
	 *	Send Response statically.
	 *	@access		public
	 *	@param		Response		$response			Response Object
	 *	@param		string|NULL		$compression		Type of compression (gzip|deflate)
	 *	@param		boolean			$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean			$exit				Flag: after afterwards (default: no)
	 *	@return		integer			Number of sent Bytes
	 */
	public static function sendResponse( Response $response, ?string $compression = NULL, bool $sendLengthHeader = TRUE, bool $exit = FALSE ): int
	{
		$sender	= new ResponseSender( $response );
		$sender->setCompression( $compression );
		return $sender->send( $sendLengthHeader, $exit );
	}

	/**
	 *	Set compression to use.
	 *	@access		public
	 *	@param		string|NULL		$compression		Compression to use: gzip, deflate
	 *	@return		self
	 */
	public function setCompression( ?string $compression ): self
	{
		$this->compression	= $compression;
		return $this;
	}

	/**
	 *	Set response to send
	 *	@access		public
	 *	@param		Response		$response	Response Object
	 *	@return		self
	 */
	public function setResponse( Response $response ): self
	{
		$this->response	= $response;
		return $this;
	}
}
