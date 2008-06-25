<?php
import( 'de.ceus-media.net.mail.PlainMail' );
/**
 *	Sends Mails.
 *	@package		net.mail
 *	@extends		Net_Mail_PlainMail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2008
 *	@version		0.2
 */
/**
 *	Sends Mails.
 *	@package		net.mail
 *	@extends		Net_Mail_PlainMail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2008
 *	@version		0.2
 */
class Net_Mail_AttachmentMail extends Net_Mail_PlainMail
{
	/**	@var	array			$attachments	Lines of Attachments */
	protected $attachments		= array();
	/**	@var	string			$eol			End of line */
	public $eol;
	/**	@var	string			$mimeBoundary	MIME Boundary */
	public $mimeBoundary;

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string		$encoding		Character Set Encoding
	 *	@param		string		$mailer			Mailer
	 *	@return		void
	 */
	public function __construct( $encoding = "UTF-8" )
	{
		parent::__construct( $encoding );
		$this->eol			= "\r\n";
		$this->mimeBoundary	= md5( time() );	
	}

	/**
	 *	Returns Attachments.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttachments()
	{
		return $this->attachments;	
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
		$this->setHeader( "Content-Type", 'multipart/mixed; boundary="'.$this->mimeBoundary.'"' );
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'File "'.$fileName.'" is not existing.' );
		$content	= chunk_split( base64_encode( file_get_contents( $fileName ) ) );	
		$baseName	= basename( $fileName );
		
		$this->attachments[]	= "--".$this->mimeBoundary;
		$this->attachments[]	= "Content-Type: ".$mimeType."; name=\"".$baseName."\"";
		$this->attachments[]	= "Content-Transfer-Encoding: base64";
		$this->attachments[]	= "Content-Description: ".$baseName;
		$this->attachments[]	= "Content-Disposition: attachment; filename=\"".$baseName."\"".$this->eol;
		$this->attachments[]	= $content.$this->eol;
	}
}  
?>