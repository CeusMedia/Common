<?php
/**
 *	Sends Mails with Attachment.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		net.mail
 *	@extends		Net_Mail_PlainMail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.01.2008
 *	@version		0.2
 */
import( 'de.ceus-media.net.mail.PlainMail' );
/**
 *	Sends Mails with Attachment.
 *	@package		net.mail
 *	@extends		Net_Mail_PlainMail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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