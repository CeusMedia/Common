<?php
import( 'de.ceus-media.framework.krypton.core.MailSender' );
/**
 *	Mail Sender for Mails with Attachments.
 *	@package		net.http
 *	@extends		Framework_Krypton_Core_MailSender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2008
 *	@version		0.1
 */
/**
 *	Mail Sender for Mails with Attachments.
 *	@package		net.http
 *	@extends		Framework_Krypton_Core_MailSender
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2008
 *	@version		0.1
 */
class Net_HTTP_AttachmentMailSender extends Framework_Krypton_Core_MailSender
{
	/**	@var array		$attachments		Lines of Attachments */
	protected $attachments;

	/**
	 *	Constructor
	 *	@access		public
	 */
	public function __construct()
	{
		$this->eol			= "\r\n";
		$this->mimeBoundary	= md5( time() );	
	}

	/**
	 *	Adds an Attachment File to Mail.
	 *	@access		public
	 *	@param		string		$fileName		File Name to add
	 *	@param		string		$mimeType		MIME Type of File
	 *	@return		void
	 */
	public function addAttachment( $fileName, $mimeType )
	{
		$this->addHeader( "Content-Type", 'multipart/mixed; boundary="'.$this->mimeBoundary.'"' );
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );
		$content	= chunk_split( base64_encode( file_get_contents( $fileName ) ) );	
		$baseName	= basename( $fileName );
		$fileType	= filetype( $fileName );
		
		$this->attachments[]	= "--".$this->mimeBoundary;
		$this->attachments[]	= "Content-Type: ".$mimeType."; name=\"".$baseName."\"";
		$this->attachments[]	= "Content-Transfer-Encoding: base64";
		$this->attachments[]	= "Content-Description: ".$baseName;
		$this->attachments[]	= "Content-Disposition: attachment; filename=\"".$baseName."\"".$this->eol;
		$this->attachments[]	= $content.$this->eol;
	}
	
	/**
	 *	Sends Mail.
	 *	@access		public
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function send( $test = 0 )
	{
		if( !array_key_exists( "From", $this->headers ) )
			throw new Exception( "No mail sender defined." );
		if( !$this->receiver )
			throw new Exception( "No mail receiver defined." );
		if( !$this->subject )
			throw new Exception( "No mail subject defined." );
		if( !$this->body )
			throw new Exception( "No mail body defined." );

		$headers	= array();
		foreach( $this->headers as $name => $value )
			$headers[]	= $name.":".$value;
		$headers	= implode( "\n", $headers );
		if( $test )
		{
			$message	= time()." <".$this->receiver."> ".$this->subject."\n".$this->body."\n";
			error_log( $message, 3, "logs/mails.log" );
			if( $test == 2 )
				return true;
		}
		
		$body	= $this->body;
		if( $this->attachments )
		{
			$body	= "--".$this->mimeBoundary.$this->eol;
			$body	.= $this->body.$this->eol.$this->eol;
			$body	.= implode( $this->eol, $this->attachments );
			$body	.= "--".$this->mimeBoundary."--".$this->eol.$this->eol;
		}
		
		if( !mail( $this->receiver, $this->subject, $body, $headers ) )
			throw new Exception( "Mail could not been sent." );
		return true;
	}
}
?>