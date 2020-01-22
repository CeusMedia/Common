<?php
/**
 *	Mails container.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 */
/**
 *	Sends Mails.
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://tools.ietf.org/html/rfc5322#section-3.3
 *	@since			0.7.1
 */
class Net_Mail
{
	/**	@var	string					$delimiter		Line Separator, for some reasons only \n must be possible */
	public static $delimiter			= "\r\n";
	/**	@var	string					$parts			Mail Parts: Bodies and Attachments */
	protected $parts					= array();
	/**	@var	Net_Mail_Header_Section	$headers		Mail Header Section */
	protected $headers;
	/**	@var	string					$sender			Sender Mail Address */
	protected $sender;
	/**	@var	string					$receiver		Receiver Mail Address */
	protected $receiver;
	/**	@var	string					$subject		Mail Subject */
	protected $subject;

	protected $mimeBoundary;


	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Mail"',
			 	'https://packagist.org/packages/ceus-media/mail'
			) );
		$this->headers		= new Net_Mail_Header_Section();
		$this->mimeBoundary	= md5( microtime( TRUE ) );
		$server	= empty( $_SERVER['SERVER_NAME'] ) ? 'localhost' : $_SERVER['SERVER_NAME'];
		$type	= 'multipart/mixed; boundary='.$this->mimeBoundary.'';
		$this->headers->setFieldPair( 'MIME-Version', '1.0' );
		$this->headers->setFieldPair( 'Message-ID', "<".sha1( microtime() )."@".$server.">" );
		$this->headers->setFieldPair( 'Content-Type', $type, TRUE );
	}

	public function addAttachment( Net_Mail_Attachment $attachment )
	{
		$this->parts[]	= $attachment;
	}

	public function addAttachmentFile( $fileName, $mimeType )
	{
		$this->addAttachment( new Net_Mail_Attachment( $fileName, $mimeType ) );
	}

	/**
	 *	Sets Mail Body.
	 *	@access		public
	 *	@param		Net_Mail_Body			$body		Body of Mail
	 *	@return		void
	 */
	public function addBody( Net_Mail_Body $body )
	{
		$this->parts[]	= $body;
	}

	/**
	 *	Returns Mail Body.
	 *	@access		public
	 *	@return		string
	 */

	public function getBody()
	{
		$number		= count( $this->parts );
		if( !$number )
			return '';

		$innerBoundary	= $this->mimeBoundary.'-1';

		$contents	= array( 'This is a multi-part message in MIME format.');
		$contents[]	= '--'.$this->mimeBoundary;
		$contents[]	= 'Content-Type: multipart/alternative;';
		$contents[]	= ' boundary="'.$innerBoundary.'"'.Net_Mail::$delimiter;
		foreach( $this->parts as $part )
			if( $part instanceof Net_Mail_Body )
				$contents[]	= '--'.$innerBoundary.Net_Mail::$delimiter.$part->render();
		$contents[]	= '--'.$innerBoundary.'--'.Net_Mail::$delimiter;

		foreach( $this->parts as $part )
			if( $part instanceof Net_Mail_Attachment )
				$contents[]	= '--'.$this->mimeBoundary.Net_Mail::$delimiter.$part->render();
		$contents[]	= '--'.$this->mimeBoundary.'--'.Net_Mail::$delimiter;
		return join( Net_Mail::$delimiter, $contents );
	}

	/**
	 *	Returns set Headers.
	 *	@access		public
	 *	@return		array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	public function getParts()
	{
		return $this->parts;
	}

	/**
	 *	Returns Receiver Address.
	 *	@access		public
	 *	@return		string
	 */
	public function getReceiver()
	{
		return $this->receiver;
	}

	public function getSender()
	{
		return $this->sender;
	}

	/**
	 *	Returns Mail Subject.
	 *	@access		public
	 *	@return		string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		Net_Mail_Header_Field	$field		Mail Header Field Object
	 *	@return		void
	 */
	public function setHeader( Net_Mail_Header_Field $field )
	{
		$this->headers->setField( $field );
	}

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@param		string		$value		Value of Header
	 *	@return		void
	 */
	public function setHeaderPair( $key, $value )
	{
		$this->headers->setField( new Net_Mail_Header_Field( $key, $value ) );
	}

	/**
	 *	Sets Receiver Address.
	 *	@access		public
	 *	@param		string		$receiver	Receiver Address of Mail
	 *	@return		void
	 */
	public function setReceiver( $receiver )
	{
		$this->receiver	= $receiver;
	}

	/**
	 *	Sets Sender Address.
	 *	@access		public
	 *	@param		string		$body		Sender Address of Mail
	 *	@return		void
	 *	@throws		Exception
	 */
	public function setSender( $sender )
	{
		$this->sender	= $sender;
		$this->setHeaderPair( "From", $sender );
	}

	/**
	 *	Sets Mail Subject.
	 *	@access		public
	 *	@param		string		$subject	Subject of Mail
	 *	@return		void
	 */
	public function setSubject( $subject )
	{
		$this->subject	= $subject;
	}
}
