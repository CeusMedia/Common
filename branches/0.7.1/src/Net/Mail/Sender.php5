<?php
/**
 *	Sends Mails of different Types.
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
 *	@package		Net.Mail
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		$Id$
 */
/**
 *	Sends Mails of different Types.
 *	@category		cmClasses
 *	@package		Net.Mail
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		$Id$
 */
class Net_Mail_Sender
{
	/**	@var		string		$mailer		Mailer Agent */
	protected $mailer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$mailer		Mailer Agent
	 *	@return		void
	 */
	public function __construct( $mailer = "PHP" )
	{
		$this->mailer	= $mailer;
	}

	/**
	 *	Checks a Header Value for possible Mail Injection and throws Exception.
	 *	@access		protected
	 *	@param		string		$value		Header Value
	 *	@return		void
	 *	@throws		InvalidArgumentException
	 */
	protected function checkForInjection( $value )
	{
		if( preg_match( '/(\r|\n)/', $value ) )
			throw new InvalidArgumentException( 'Mail injection attempt detected' );
	}

	/**
	 *	Formats Header Name.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@return		string
	 */
	protected function formatHeaderName( $key )
	{
		$parts	= explode( "-", $key );
		for( $i=0; $i<count( $parts ); $i++ )
			$parts[$i]	= ucfirst( strtolower( $parts[$i] ) );
		$key	= implode( "-", $parts );
		return $key;
	}

	/**
	 *	Sends Mail.
	 *	@access		public
	 *	@param		mixed		$mail		Mail Object
	 *	@return		void
	 *	@throws		RuntimeException|InvalidArgumentException
	 */
	public function send( $mail )
	{
		$this->sendMail( $mail, $this->mailer );
	}

	public function setSmtp( $host, $port = 25, $auth = NULL, $username = NULL, $password = NULL )
	{
		$this->smtpHost		= $host;
		$this->smtpPort		= $port;
		$this->smtpAuth		= $auth;
		$this->smtpUsername	= $username;
		$this->smtpPassword	= $password;
	}
	protected function sendToExternal( Net_Mail $mail )
	{
		$tid = time();
		$date = date("D, d M Y H:i:s O",$tid);
		print $dato;
		$conn	= fsockopen( $this->smtpHost, $this->smtpPort, $errno, $errstr, 30);
		if( !$conn )
			throw new RuntimeException( 'Connection to SMTP server "'.$this->smtpHost.':'.$this->smtpPort.'" failed' );
		fputs( $conn, "HELO ".$_SERVER['SERVER_NAME']."\r\n" );
		fgets( $conn, 1024 );
		fputs( $conn, "MAIL FROM: ".$mail->getSender()."\r\n" );
		fgets( $conn, 1024);
		fputs( $conn, "RCPT TO: ".$mail-getReceiver()."\r\n");
		fgets( $conn, 1024);
		fputs( $conn, "DATA\r\n");
		fgets( $conn, 1024);
		fputs( $conn, "Date: ".$date."\r\n");
		fputs( $conn, "From: ".$mail->getSender()."\r\n");
		fputs( $conn, "Subject: ".$mail->getSubject()."\r\n");
		fputs( $conn, "To: ".$mail-getReceiver()."\r\n");
		foreach( $mail->getHeaders() as $key => $value )
			fputs( $conn, $key.": ".$value."\r\n");
		fputs( $conn, $mail->getBody()."\r\n");
		fputs( $conn, ".\r\nQUIT\r\n");
		fgets( $conn, 1024);
		fclose( $conn );
	}

	/**
	 *	Sends Mail statically.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$mail		Mail Object
	 *	@param		string		$mailer		Mailer
	 *	@return		void
	 *	@throws		RuntimeException|InvalidArgumentException
	 */
	public static function sendMail( $mail, $mailer = 'PHP' )
	{
		$headers	= $mail->getHeaders();
		$receiver	= $mail->getReceiver();
		$body		= $mail->getBody();
		$subject	= $mail->getSubject();

	
		//  --  VALIDATION & SECURITY CHECK  --  //
		self::checkForInjection( $receiver );
		self::checkForInjection( $subject );
		if( !array_key_exists( "From", $headers ) )
			throw new InvalidArgumentException( 'No mail sender defined' );
		if( !$receiver )
			throw new InvalidArgumentException( 'No mail receiver defined' );
		if( !$subject )
			throw new InvalidArgumentException( 'No mail subject defined' );
		if( !$body )
			throw new InvalidArgumentException( 'No mail body defined' );
		foreach( $headers as $key => $value )
		{
			self::checkForInjection( $key );
			self::checkForInjection( $value );
		}

		//  --  HEADERS  --  //
		if( $mailer )
	 		$headers['X-Mailer']	= $mailer;
		$list	= array();
		foreach( $headers as $key => $value )
		{
			$key	= self::formatHeaderName( $key );
			$list[]	= $key.":".$value; 
		}
		$headers	= implode( "\n", $list );
		
		//  --  ATTACHMENTS  --  //
		if( is_a(  $mail, 'Net_Mail_AttachmentMail' ) && $mail->getAttachments() )
		{
			$body	= "--".$mail->mimeBoundary.$mail->eol.$mail->eol.$body.$mail->eol.$mail->eol;
			$body	.= implode( $mail->eol, $mail->getAttachments() );
			$body	.= "--".$mail->mimeBoundary."--".$mail->eol.$mail->eol;
		}

		if( !mail( $receiver, $subject, $body, $headers ) )
			throw new RuntimeException( 'Mail could not been sent' );
	}
}
?>