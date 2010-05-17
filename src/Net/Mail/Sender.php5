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
 *	@package		net.mail
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
 *	@package		net.mail
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