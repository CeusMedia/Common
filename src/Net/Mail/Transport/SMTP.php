<?php
/**
 *	Sends Mail using a remote SMTP Server and a Socket Connection.
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_Mail_Transport
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Sends Mail using a remote SMTP Server and a Socket Connection.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Mail_Transport
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.der-webdesigner.net/tutorials/php/anwendungen/329-php-und-oop-mailversand-via-smtp.html
 *	@deprecated		Please use CeusMedia/Mail (https://packagist.org/packages/ceus-media/mail) instead
 *	@todo			remove in version 1.0
 */
class Net_Mail_Transport_SMTP
{
	/**	@var		string		$host		SMTP Server Host Name */
	protected $host;
	/**	@var		integer		$port		SMTP Server Port */
	protected $port;
	/**	@var		string		$username	SMTP Auth Username */
	protected $username;
	/**	@var		string		$password	SMTP Auth Password */
	protected $password;

	protected $isSecure			= FALSE;

	protected $verbose			= FALSE;

	/**	@var		string		$mailer		Mailer Agent */
	protected $mailer			= 'CeusMedia::Common/0.8.0';


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$host		SMTP Server Host Name
	 *	@param		integer		$port		SMTP Server Port
	 *	@param		string		$username	SMTP Auth Username
	 *	@param		string		$password	SMTP Auth Password
	 *	@return		void
	 */
	public function __construct( $host, $port = 25, $username = NULL, $password = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Mail"',
			 	'https://packagist.org/packages/ceus-media/mail'
			) );
		$this->host		= $host;
		$this->setPort( $port );
		$this->setAuthUsername( $username );
		$this->setAuthPassword( $password );
	}

	protected function checkResponse( $connection ){
		$response	= fgets( $connection, 1024 );
		if( $this->verbose )
			xmp( ' > '.$response );
		$matches	= array();
		preg_match( '/^([0-9]{3}) (.+)$/', trim( $response ), $matches );
		if( $matches )
			if( (int) $matches[1] >= 400 )
				throw new RuntimeException( 'SMTP error: '.$matches[2], (int) $matches[1] );
	}

	/**
	 *	Sends Mail using a Socket Connection to a remote SMTP Server.
	 *	@access		public
	 *	@param		Net_Mail		$mail		Mail Object
	 *	@return		void
	 */
	public function send( Net_Mail $mail )
	{
		$mail->setHeaderPair( 'X-Mailer', $this->mailer );
		$delim		= Net_Mail::$delimiter;
		$date		= date( "D, d M Y H:i:s O", time() );
		$subject	= "=?UTF-8?B?".base64_encode( $mail->getSubject() )."?=";
		$conn		= fsockopen( $this->host, $this->port, $errno, $errstr, 5 );
		if( !$conn )
			throw new RuntimeException( 'Connection to SMTP server "'.$this->host.':'.$this->port.'" failed' );
		if( !$mail->getSender() )
			throw new RuntimeException( 'No mail sender set' );
		if( !$mail->getReceiver() )
			throw new RuntimeException( 'No mail receiver set' );
		if( !$mail->getBody() )
			throw new RuntimeException( 'No mail body set' );
		try{
			$this->checkResponse( $conn );
			$this->sendChunk( $conn, "HELO ".$this->host );
			$this->checkResponse( $conn );
			if( $this->isSecure ){
				$this->sendChunk( $conn, "STARTTLS" );
				$this->checkResponse( $conn );
				stream_socket_enable_crypto( $conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT );
			}
			if( $this->username && $this->password ){
				$this->sendChunk( $conn, "AUTH LOGIN" );
				$this->checkResponse( $conn );
				$this->sendChunk( $conn, base64_encode( $this->username ) );
				$this->checkResponse( $conn );
				$this->sendChunk( $conn, base64_encode( $this->password ) );
				$this->checkResponse( $conn );
			}
			$this->sendChunk( $conn, "MAIL FROM: ".$mail->getSender() );
			$this->sendChunk( $conn, "RCPT TO: ".$mail->getReceiver() );
			$this->sendChunk( $conn, "DATA" );
			$this->checkResponse( $conn );
			$this->sendChunk( $conn, "Date: ".$date );
			$this->sendChunk( $conn, "Subject: ".$subject );
			$this->sendChunk( $conn, "To: <".$mail->getReceiver().">" );
			foreach( $mail->getHeaders()->getFields() as $header )
				$this->sendChunk( $conn, $header->toString() );
			$this->sendChunk( $conn, $delim.$mail->getBody() );
			$this->checkResponse( $conn );
			$this->sendChunk( $conn, '.' );
			$this->checkResponse( $conn );
			$this->checkResponse( $conn );
			$this->sendChunk( $conn, "QUIT" );
			$this->checkResponse( $conn );
			fclose( $conn );
		}
		catch( Exception $e ){
			fclose( $conn );
			throw new RuntimeException( $e->getMessage(), $e->getCode(), $e->getPrevious() );
		}
	}

	protected function sendChunk( $connection, $message ){
		if( $this->verbose )
			xmp( ' < '.$message );
		fputs( $connection, $message.Net_Mail::$delimiter );
	}

	/**
	 *	Sets Password for SMTP Auth.
	 *	@access		public
	 *	@param		string		$password	SMTP Auth Password
	 *	@return		void
	 */
	public function setAuthPassword( $password )
	{
		$this->password	= $password;
	}

	/**
	 *	Sets Username for SMTP Auth.
	 *	@access		public
	 *	@param		string		$username	SMTP Auth Username
	 *	@return		void
	 */
	public function setAuthUsername( $username )
	{
		$this->username	= $username;
	}

	/**
	 *	Sets Mail Agend for Mailer Header.
	 *	@access		public
	 *	@param		string		$mailer		Mailer Agent
	 *	@return		void
	 */
	public function setMailer( $mailer ){
		$this->mailer = $mailer;
	}

	public function setSecure( $secure ){
		$this->isSecure = (bool) $secure;
	}

	public function setVerbose( $verbose ){
		$this->verbose = (bool) $verbose;
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		integer		$port		SMTP Server Port
	 *	@return		void
	 */
	public function setPort( $port )
	{
		$this->port		= $port;
	}
}
