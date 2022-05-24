<?php
/**
 *	Mail Attachment Data Object.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_Mail
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Mail Attachment Data Object.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Mail
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://tools.ietf.org/html/rfc5322#section-3.3
 *	@deprecated		Please use CeusMedia/Mail (https://packagist.org/packages/ceus-media/mail) instead
 *	@todo			remove in version 1.0
 */
class Net_Mail_Attachment
{
	protected $fileName;
	protected $mimeType;
	protected $headers;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Pathname of File to attach
	 *	@param		string		$mimeType		MIME Type of File
	 *	@return		void
	 *	@throws		InvalidArgumentException	if File is not existing
	 */
	public function __construct( $fileName, $mimeType = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Mail"',
			 	'https://packagist.org/packages/ceus-media/mail'
			) );
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Attachment file "'.$fileName.'" is not existing' );
		$baseName		= basename( $fileName );
		$this->headers	= new Net_Mail_Header_Section();
		$this->headers->setFieldPair( 'Content-Transfer-Encoding', 'base64' );
		$this->headers->setFieldPair( 'Content-Description', $baseName );
		$this->headers->setFieldPair( 'Content-Disposition', 'attachment; filename="'.$baseName.'"' );
		$this->fileName	= $fileName;
		$this->content	= chunk_split( base64_encode( file_get_contents( $fileName ) ) );
		if( $mimeType )
			$this->setMimeType( $mimeType );
	}

	/**
	 *	Returns Content of set File Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 *	Returns set File Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 *	Returns set MIME Type.
	 *	@access		public
	 *	@return		string
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}

	/**
	 *	Returns rendered Mail Part of Attachment, containing Header Fields and File Content encoded with base64.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		return $this->headers->toString().Net_Mail::$delimiter.Net_Mail::$delimiter.$this->content.Net_Mail::$delimiter;
	}

	/**
	 *	Sets MIME Type of File.
	 *	@access		public
	 *	@param		string		$mimeType		MIME Type of File
	 *	@return		void
	 */
	public function setMimeType( $mimeType )
	{
		$this->headers->setFieldPair( 'Content-Type', $mimeType );
	}
}
