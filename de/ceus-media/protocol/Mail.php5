<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	Mail Sender.
 *	@package		protocol
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	Mail Sender.
 *	@package		protocol
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class Mail extends OptionObject
{
	/**	@var	string		$body			Body of Mail */
	protected $body	= "";
	/**	@var	string		$subject		Subject of Mail */
	protected $subject	= "";
	/**	@var	string		$target			Receiver of Mail */
	protected $target	= "";
	/**	@var	array		$headers		Header Keys of Mail */
	protected $headers	= array(
		"from",
		"reply-to",
		"cc",
		"bcc",
		"date",
		"x-mailer",
		"mime-version",
		"content-type",
		);
		
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->setHeader( "MIME-Version", "1.0" );
		$this->setHeader( "Content", "text/html; charset=iso-8859-1" );
		$this->setHeader( "X-Mailer", "PHP" );
	}
	
	/**
	 *	Returns all set Headers as String.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getHeaders()
	{
		$headers	= array();
		foreach( $this->headers as $header )
			if( NULL !== ( $value = $this->getOption( $header ) ) )
				$headers[]	= $header.": ".$value;
		$headers	= implode( "\r\n", $headers );
		return $headers;
	}
	
	/**
	 *	Sends Mail.
	 *	@access		public
	 *	@param		bool			$set_date	Flag: set current Date
	 *	@param		bool			$verbose		Flag: show Warnings
	 *	@return		bool
	 */
	public function send( $set_date = true, $verbose = true )
	{
		if( $this->target )
		{
			if( $this->subject )
			{
				if( $this->body )
				{
					if( $this->getOption( "from" ) )
					{
						if( $set_date )
							$this->setOption( "date", date( "r", time() ) );
						$headers	= $this->getHeaders();
						if( mail( $this->target, $this->subject, $this->body, $headers ) )
							return true;
						else if( $verbose )
							trigger_error( "Mail[send]: Mail could not been sent", E_USER_WARNING );
					}
					else if( $verbose )
						trigger_error( "Mail[send]: No Sender eMail (Header 'From') set", E_USER_WARNING );
				}
				else if( $verbose )
					trigger_error( "Mail[send]: No Message Body set", E_USER_WARNING );
			}
			else if( $verbose )
				trigger_error( "Mail[send]: No Message Subject set", E_USER_WARNING );
		}
		else if( $verbose )
			trigger_error( "Mail[send]: No Receiver set", E_USER_WARNING );
		return false;
	}
	
	/**
	 *	Sets Body of Mail.
	 *	@access		public
	 *	@param		string		$body		Body of Mail
	 *	@return		void
	 */
	public function setBody( $body )
	{
		$this->body	= $body;
	}

	/**
	 *	Sets Header of Mail.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@param		string		$value		Value of Header
	 *	@return		bool
	 */
	public function setHeader( $key, $value )
	{
		if( in_array( strtolower( $key ), $this->headers ) )
		{
			$this->setOption( strtolower( $key ), $value );
			return true;
		}
		return false;
	}

	/**
	 *	Sets Receiver of Mail.
	 *	@access		public
	 *	@param		string		$subject		Subject of Mail
	 *	@return		void
	 */
	public function setSubject( $subject )
	{
		$this->subject	= $subject;
	}
	
	/**
	 *	Sets Receiver of Mail.
	 *	@access		public
	 *	@param		string		$email		eMail address of target (RFC2822)
	 *	@return		bool
	 *	@see		http://www.faqs.org/rfcs/rfc2822
	 */
	public function setTarget( $email )
	{
		$this->target	= $email;
	}
}
?>