<?php
/**
 *	Mail Body Data Object.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Mail Body Data Object.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Mail
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://tools.ietf.org/html/rfc5322#section-3.3
 *	@deprecated		Please use CeusMedia/Mail (https://packagist.org/packages/ceus-media/mail) instead
 *	@todo			remove in version 1.0
 */
class Net_Mail_Body
{
	protected $content;
	protected $headers;
	const TYPE_PLAIN	= 'text/plain';
	const TYPE_HTML		= 'text/html';
	const FORMAT_FIXED	= 'fixed';
	const FORMAT_FLOWED	= 'flowed';

	/**
	 *	Constructor. Sets Content Encoding to 8-bit.
	 *	@access		public
	 *	@param		string		$content		Body Content
	 *	@param		string		$mimeType		Content MIME Type
	 *	@param		string		$encoding		Content Transfer Encoding, default: 8bit
	 *	@return		void
	 */
	public function __construct( $content, $mimeType = self::TYPE_PLAIN, $encoding = "8bit" )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Mail"',
			 	'https://packagist.org/packages/ceus-media/mail'
			) );
		$this->headers	= new Net_Mail_Header_Section();
		$this->setContent( $content );
		if( $mimeType )
			$this->setContentType( $mimeType );
		$this->setContentEncoding( $encoding );
	}

	/**
	 *	Returns raw content.
	 *	@access		public
	 *	@return		string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 *	Returns mail part content encoding mechanism.
	 *	@access		public
	 *	@return		string
	 */
	public function getContentEncoding()
	{
		$field	= $this->headers->getField( 'Content-Transfer-Encoding' );
		return $field->getValue();
	}

	/**
	 *	Returns Mail Header Section Object.
	 *	@access		public
	 *	@return		Net_Mail_Header_Section
	 */
	public function getHeaders()
	{
		return $this->headers->getFields();
	}

	/**
	 *	Returns mail part MIME type.
	 *	@access		public
	 *	@return		string
	 */
	public function getMimeType()
	{
		$field	= $this->headers->getField( 'Content-Type' );
		$parts	= explode( ';', $field->getValue() );
		return $parts[0];
	}

	/**
	 *	Returns rendered Mail Part of Body, containing Header Fields and Body Content.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		return $this->headers->toString().Net_Mail::$delimiter.Net_Mail::$delimiter.$this->content/*.PHP_EOL*/;
	}

	/**
	 *	Sets Body Content. Wraps line which are longer than 998 characters.
	 *	@access		public
	 *	@param		string		$content		Body Content
	 *	@return		void
	 */
	public function setContent( $content )
	{
		$this->content	= wordwrap( $content, 998, Net_Mail::$delimiter, TRUE );
	}

	/**
	 *	Defined Content Transfer Encoding Mechanism.
	 *	@access		public
	 *	@param		string		$mechanism		Encoding Mechanism, default: 7bit, others: 8bit, base64, quoted-printable, binary
	 *	@return		void
	 *	@see		http://www.ietf.org/rfc/rfc2045.txt	RFC 2045 Section 6.1
	 */
	public function setContentEncoding( $mechanism )
	{
		$this->headers->setFieldPair( 'Content-Transfer-Encoding', trim( $mechanism ) );
	}

	/**
	 *	Sets Body Content Type: MIME Type, Character Set and Format.
	 *	@access		public
	 *	@param		string		$mimeType		Content MIME Type
	 *	@param		string		$charset		Content Character Set
	 *	@param		string		$format			Content Format (fixed, flowed)
	 *	@return		void
	 */
	public function setContentType( $mimeType, $charset = 'UTF-8', $format = self::FORMAT_FIXED )
	{
		$value	= trim( $mimeType ).'; charset='.trim( $charset ).'; format='.trim( $format );
		$this->headers->setFieldPair( 'Content-Type', $value );
	}

	/**
	 *	Wraps Content Lines (on whitespace) with exceed a given length.
	 *	@access		public
	 *	@param		integer		$maxLineLength	Maximum Length to force for each Line in Content.
	 *	@return		string
	 */
	public function wrapWords( $maxLineLength = 76 )
	{
		$this->content	= chunk_split( $this->content, $maxLineLength, Net_Mail::$delimiter );
	}
}
?>
