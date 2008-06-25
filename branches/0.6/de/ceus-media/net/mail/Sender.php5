<?php
/**
 *	Sends Mails of different Types.
 *	@package		net.mail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.2007
 *	@version		0.2
 */
/**
 *	Sends Mails of different Types.
 *	@package		net.mail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.2007
 *	@version		0.2
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
		if( eregi( "(\r|\n)", $value ) )
			throw new InvalidArgumentException( "Mail Injection detected." );
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
	 *	@param		mixed		$mail		Mail Object
	 *	@param		string		$mailer		Mailer
	 *	@return		void
	 *	@throws		RuntimeException|InvalidArgumentException
	 */
	public static function sendMail( $mail, $mailer = "PHP" )
	{
		$headers	= $mail->getHeaders();
		$receiver	= $mail->getReceiver();
		$body		= $mail->getBody();
		$subject	= $mail->getSubject();

	
		//  --  VALIDATION & SECURITY CHECK  --  //
		self::checkForInjection( $receiver );
		self::checkForInjection( $subject );
		if( !array_key_exists( "From", $headers ) )
			throw new InvalidArgumentException( "No mail sender defined." );
		if( !$receiver )
			throw new InvalidArgumentException( "No mail receiver defined." );
		if( !$subject )
			throw new InvalidArgumentException( "No mail subject defined." );
		if( !$body )
			throw new InvalidArgumentException( "No mail body defined." );
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
			throw new RuntimeException( "Mail could not been sent." );
	}
}
?>