<?php
/**
 *	Message Implementation for Accessing a IMAP eMail Server.
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
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	Message Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Net_IMAP_Message
{
	protected $stream;
	protected $messageNumber;
	protected $structure;
	protected $info;
	protected $body;
	
	const TYPE_PLAIN	= 'PLAIN';
	const TYPE_HTML		= 'HTML';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Net_IMAP_Connection	$connection		IMAP Connection Mail
	 *	@param		int					$messageNumber	Number of Message in Folder
	 *	@return		void
	 */
	public function __construct( $connection, $messageNumber )
	{
		$this->stream			= $connection->getStream();	
		$this->messageNumber	= $messageNumber;	
	}

	public function getAttachments()
	{
		$structure	= $this->getMessageStructure();
		$attachments	= "";
		foreach( $structure->parts as $part )
		{
			if( $part->type > 0 )
			{
				$attachments .= $part[$i]->description;
			}
		}
		return $attachments;
	}

	/**
	 *	Returns HTML Body if available.
	 *	@access		public
	 *	@return		string
	 */
	public function getHtmlBody()
	{
		return $this->getBody( self::TYPE_HTML );
	}

	/**
	 *	Returns plain Body if available.
	 *	@access		public
	 *	@return		string
	 */
	public function getPlainBody()
	{
		return $this->getBody( self::TYPE_PLAIN );
	}
	
	/**
	 *	Returns Body.
	 *	@access		public
	 *	@param		string		$type		Body Type (TYPE_PLAIN | TYPE_HTML)
	 *	@return		string
	 */
	protected function getBody( $type )
	{
		$count		= 0;
		$structure	= $this->getMessageStructure();
		foreach( $structure->parts as $part )
		{
			$count++;
			if( $part->type !== 0 )
				continue;
			if( $part->subtype == $type )
			{
				$body	= imap_fetchbody( $this->stream, $this->messageNumber, $count );
				$body	= $this->decodeBody( $body, $part->encoding );
				return $body;
			}
		}
	}

	/**
	 *	Decodes encoded Body.
	 *	@access		public
	 *	@param		string		$body		Body Content
	 *	@param		int			$encoding	Encoding Type
	 *	@return		string
	 */
	protected function decodeBody( $body, $encoding )
	{
		switch( $encoding )
		{
			case 3:
				$body	= base64_decode( $body );
				break;
			case 4:
				$body	= quoted_printable_decode( $body );
				break;
		}
		return $body;
	}

	/**
	 *	Returns Information Object of Message Header.
	 *	@access		public
	 *	@return		object
	 */
	public function getHeaderInfo()
	{
		if( !$this->info )
			$this->info	= imap_headerinfo( $this->stream, $this->messageNumber );
		return $this->info;
	}

	/**
	 *	Returns Structure Object of Message.
	 *	@access		public
	 *	@return		object
	 */
	public function getMessageStructure()
	{
		if( !$this->structure )
			$this->structure	= imap_fetchstructure( $this->stream, $this->messageNumber );
		return $this->structure;
	}

	/**
	 *	Returns Subject of Message.
	 *	@access		public
	 *	@return		string
	 */
	public function getSubject()
	{
		$this->getHeaderInfo();
		return $this->info->subject;
	}
}
?>