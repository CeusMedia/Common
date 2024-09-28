<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for HTTP Response containing Headers and Body.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Response;

use CeusMedia\Common\Exception\NotSupported as NotSupportedException;
use CeusMedia\Common\Net\HTTP\Request as Request;
use CeusMedia\Common\Net\HTTP\Response as Response;
use CeusMedia\Common\Net\HTTP\Response\Compressor as ResponseCompressor;

/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Sender
{
	public static array $supportedCompressions = ['gzip', 'deflate'];

	/**	@var		string|NULL			$compression	Type of compression to use (gzip, deflate), default: NULL */
	protected ?string $compression		= NULL;

	/**	@var		Response|NULL		$response		Response object */
	protected ?Response $response		= NULL;

	/**
	 *	Send Response statically.
	 *	@access		public
	 *	@param		Response		$response			Response Object
	 *	@param		string|NULL		$compression		Type of compression (gzip|deflate)
	 *	@param		boolean			$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean			$andExit			Flag: after afterwards (default: no)
	 *	@return		Response		Finally sent response object
	 *	@throws		NotSupportedException				if compression type is not supported
	 */
	public static function sendResponse( Response $response, ?string $compression = NULL, bool $sendLengthHeader = TRUE, bool $andExit = FALSE ): Response
	{
		$sender	= new self( $response );
		$sender->setCompression( $compression );
		return $sender->send( $sendLengthHeader, $andExit );
	}

	/**
	 *	Send Response statically.
	 *	@access		public
	 *	@param		Response		$response			Response Object
	 *	@param		Request			$request			Request Object
	 *	@param		boolean			$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean			$andExit			Flag: after afterwards (default: no)
	 *	@return		Response		Finally sent response object
	 */
	public static function sendResponseForRequest( Response $response, Request $request, bool $sendLengthHeader = TRUE, bool $andExit = FALSE ): Response
	{
		$sender	= new self( $response, $request );
		return $sender->send( $sendLengthHeader, $andExit );
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Response|NULL		$response	Response Object
	 *	@return		void
	 */
	public function __construct( ?Response $response = NULL, ?Request $request = NULL )
	{
		if( NULL !== $response )
			$this->setResponse( $response );
		if( NULL !== $request )
			$this->negotiateCompressionByRequestHeader( $request );
	}

	/**
	 *	Send Response.
	 *	@access		public
	 *	@param		boolean		$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean		$andExit			Flag: after afterwards (default: no)
	 *	@return		Response	Number of sent Bytes or exits if wished so
	 *	@throws		NotSupportedException			if compression type is not supported
	 */
	public function send( bool $sendLengthHeader = TRUE, bool $andExit = FALSE ): Response
	{
		$response	= clone( $this->response );

		/*  --  COMPRESSION  --  */
		try{
			$response	= ResponseCompressor::compressResponse( $response, $this->compression );
		}
		catch( NotSupportedException ){
			$this->compression = NULL;
			return $this->send( $sendLengthHeader, $andExit );
		}

		/*  --  HTTP BASIC INFORMATION  --  */
		$status	= $response->getStatus();
		header( vsprintf( '%s/%s %s', [
			$response->getProtocol(),
			$response->getVersion(),
			$status
		] ) );
		header( 'Status: '.$status );

		if( !$sendLengthHeader )
			$response->headers->removeByName( 'Content-Length' );

		/*  --  HTTP HEADER FIELDS  --  */
		foreach( $response->getHeaders() as $header )
			header( $header->toString(), FALSE );

		/*  --  SEND BODY  --  */
		print( $response->getBody() );
		flush();
		if( $andExit )
			exit;
		return $response;
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

	//  --  PROTECTED  --  //

	/**
	 *	Match accepted encodings of request with supported compressions.
	 *	Returns first matching compression type.
	 *	@param		Request		$request
	 *	@return		?string
	 */
	protected function negotiateCompressionByRequestHeader( Request $request ): ?string
	{
		$header	= $request->getHeader( 'Accept-Encoding', FALSE );
		if( NULL === $header )
			return NULL;

		/** @var array $acceptedEncodings */
		$acceptedEncodings	= $header->getValue( TRUE );
		$matchingEncodings	= array_intersect(
			self::$supportedCompressions,
			array_keys( $acceptedEncodings )
		);
		if( [] === $matchingEncodings )
			return NULL;

		$match	= current( $matchingEncodings );
		$this->setCompression( $match );
		return $match;
	}
}
