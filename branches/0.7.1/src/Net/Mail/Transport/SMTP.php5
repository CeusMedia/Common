<?php
/**
 *	Sends Mail using a remote SMTP Server and a Socket Connection.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		Net.Mail.Transport
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Sends Mail using a remote SMTP Server and a Socket Connection.
 *
 *	@category		cmClasses
 *	@package		Net.Mail.Transport
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
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
		$this->host		= $host;
		$this->setPort( $port );
		$this->setAuthUsername( $username );
		$this->setAuthPassword( $password );
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
	 *	Constructor.
	 *	@access		public
	 *	@param		integer		$port		SMTP Server Port
	 *	@return		void
	 */
	public function setPort( $port )
	{
		$this->port		= $port;
	}

	/**
	 *	Sends Mail using a Socket Connection to a remote SMTP Server.
	 *	@access		public
	 *	@param		Net_Mail		$mail		Mail Object
	 *	@return		void
	 */
	public function send( Net_Mail $mail )
	{
		$tid = time();
		$date = date( "D, d M Y H:i:s O", $tid );
		$conn	= fsockopen( $this->host, $this->port, $errno, $errstr, 30 );
		if( !$conn )
			throw new RuntimeException( 'Connection to SMTP server "'.$this->host.':'.$this->port.'" failed' );
		fputs( $conn, "HELO ".$_SERVER['SERVER_NAME']."\r\n" );
		fgets( $conn, 1024 );
		fputs( $conn, "MAIL FROM: ".$mail->getSender()."\r\n" );
		fgets( $conn, 1024);
		fputs( $conn, "RCPT TO: ".$mail->getReceiver()."\r\n");
		fgets( $conn, 1024);
		fputs( $conn, "DATA\r\n");
		fgets( $conn, 1024);
		fputs( $conn, "Date: ".$date."\r\n");
		fputs( $conn, "Subject: ".$mail->getSubject()."\r\n");
		fputs( $conn, "To: ".$mail->getReceiver()."\r\n");
		fputs( $conn, $mail->getHeaders()->toString()."\r\n" );
		fputs( $conn, $mail->getBody()."\r\n");
		fputs( $conn, ".\r\nQUIT\r\n");
		fgets( $conn, 1024);
		fclose( $conn );
	}
}
?>