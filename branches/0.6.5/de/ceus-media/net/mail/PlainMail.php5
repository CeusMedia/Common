<?php
/**
 *	Sends Mails.
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
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		0.1
 */
/**
 *	Sends Mails.
 *	@package		net.mail
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		0.1
 */
class Net_Mail_PlainMail
{
	/**	@var	string			$body			Body of Mail */
	protected $body;
	/**	@var	array			$headers		Headers of Mail */
	protected $headers			= array();
	/**	@var	string			$receiver		Receiver Address of Mail */
	protected $receiver;
	/**	@var	string			$subject		Subject of Mail */
	protected $subject;


	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string		$encoding		Character Set Encoding
	 *	@param		string		$mailer			Mailer
	 *	@return		void
	 */
	public function __construct( $encoding = "UTF-8" )
	{
		$this->setHeader( "MIME-Version", "1.0" );
		$this->setHeader( "Content-Type", "text/plain; charset=".$encoding );
	}

	/**
	 *	Returns Mail Body.
	 *	@access		public
	 *	@return		string
	 */
	public function getBody()
	{
		return $this->body;
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
	
	/**
	 *	Returns Receiver Address.
	 *	@access		public
	 *	@return		string
	 */
	public function getReceiver()
	{
		return $this->receiver;
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
	 *	Sets Mail Body.
	 *	@access		public
	 *	@param		string		$body		Body of Mail
	 *	@return		void
	 */
	public function setBody( $body )
	{
		$this->body	= $body;
	}

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@param		string		$value		Value of Header
	 *	@return		void
	 */
	public function setHeader( $key, $value )
	{
		$this->headers[$key]	= $value;
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
		$this->setHeader( "From", $sender );
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
?>