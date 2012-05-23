<?php
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Net.IMAP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *	@category		cmClasses
 *	@package		Net.IMAP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Net_IMAP_Box
{
	protected $connection;
	protected $cache;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Net_IMAP_Connection	$connection		IMAP Connection Object
	 *	@return		void
	 */
	public function __construct( $connection, $cache )
	{
		$this->connection	= $connection;
		$this->cache		= $cache;
	}

	/**
	 *	Returns Information Array of Mail Box.
	 *	@access		public
	 *	@param		string		$folder			Folder within Mail Box
	 *	@return		array
	 */
	public function getBoxInfo( $folder = NULL )
	{
		$address	= $this->connection->getAddress( $folder );
		$info		= imap_mailboxmsginfo( $this->connection->getStream() );
		if( !$info )
			throw new Exception( "imap_mailboxmsginfo() failed: ". imap_lasterror() );
		return array(
			"date"		=> $info->Date,
			"driver"	=> $info->Driver,
			"mailbox"	=> $info->Mailbox,
			"messages"	=> $info->Nmsgs,
			"recent"	=> $info->Recent,
			"size"		=> $info->Size,
		);
	}

	/**
	 *	Returns Array of Folders within Mail Box.
	 *	@access		public
	 *	@param		string		$folder			Folder to index
	 *	@return		array
	 */
	public function getFolders( $folder = "*" )
	{
		$list		= array();
		$address	= $this->connection->getAddress();
		$folders	= imap_getmailboxes( $this->connection->getStream(), $address, "*" );
		foreach( $folders as $folder )
			$list[]	= str_replace( $address, "", imap_utf7_decode( $folder->name ) );
		return $list;
	}

	public function getNrFromUid( $uid ){
		$msgNo		= imap_msgno( $this->connection->getStream(), $uid );
		return $msgNo;
	}

	public function decode( $matches ){
		$string	= $matches[3];
		switch( strtoupper( $matches[2] ) ){
			case 'Q': $string	= quoted_printable_decode( $string ); break;
			default: break;
		}
		switch( strtoupper( $matches[1] ) ){
			case 'cp866':
			case 'cp1251':
			case 'cp1252':
			case 'ISO-8859-1':
			case 'ISO-8859-2':
			case 'ISO-8859-15':
			case 'KOI8-R':
			case 'BIG5':
			case 'GB2312':
			case 'Shift_JIS':
			case 'EUC-JP': $string	= iconv( $matches[1], 'UTF-8//TRANSLIT', $string ); break;
			default: $string	= utf8_encode( $string ); break;
		}
		return $string;
	}
	
	public function getHeadersByUid( $uid ){
		$cacheKey	= 'mail_header_'.$uid;
		if( $this->cache->has( $cacheKey ) )
			$message	= $this->cache->get( $cacheKey );
		else
		{
			$msgNo		= $this->getNrFromUid( $uid );
			$headers	= imap_header( $this->connection->getStream(), $msgNo );
#				print_m( $headers );
			$from			= $headers->from[0];
			$subject		= $headers->subject;
			$subject		= preg_replace_callback( "/=\?([a-z0-9-]+)\?([a-z])\?(.+)\?=/iU", array( $this, 'decode' ), $subject );
			if( !$subject )
				$subject	= $headers->subject;
			if( empty( $headers->bcc ) )
				$headers->bcc	= array();
			if( empty( $headers->cc ) )
				$headers->cc	= array();
			$message		= (object) array(
				"uid"			=> $uid,
				"number"		=> trim( $headers->Msgno ),
				"messageId"		=> trim( $headers->message_id ),
				"timestamp"		=> $headers->udate,
				"date"			=> date( "Y-m-d H:i:s", strtotime( $headers->date ) ),
				"size"			=> (int) $headers->Size,
				"subject"		=> $headers->subject,
				"uiSubject"		=> $subject,
				"sender"		=> $headers->sender,
				"from"			=> $headers->from,
				"to"			=> $headers->to,
				"bcc"			=> $headers->bcc,
				"cc"			=> $headers->cc,
				"replyTo"		=> $headers->reply_to,
				"answered"		=> (bool) trim( $headers->Answered ),
				"deleted"		=> (bool) trim( $headers->Deleted ),
				"draft"			=> (bool) trim( $headers->Draft ),
				"flagged"		=> (bool) trim( $headers->Flagged ),
				"recent"		=> (bool) trim( $headers->Recent ),
				"unseen"		=> (bool) trim( $headers->Unseen ),
			);
			$this->cache->set( $cacheKey, $message );
		}
		return $message;
		
	}
	
	/**
	 *	Returns Array of Message Header Information.
	 *	@access		public
	 *	@param		int			$sort			Sort (SORTDATE | SORTARRIVAL | SORTFROM | SORTSUBJECT | SORTTO | SORTCC | SORTSIZE)
	 *	@return		array
	 */
	public function listMessageUids( $sort = SORTDATE, $reverse = TRUE, $limit = 0, $offset = 0 )
	{
		$list	= array();
		$sort	= imap_sort( $this->connection->getStream(), $sort, (int) $reverse, SE_UID );
		$data['total']	= count( $sort );
		$data['items']	= array_slice( $sort, $offset, $limit );
		return $data;
	}	
	/**
	 *	Returns Array of Message Header Information.
	 *	@access		public
	 *	@param		int			$sort			Sort (SORTDATE | SORTARRIVAL | SORTFROM | SORTSUBJECT | SORTTO | SORTCC | SORTSIZE)
	 *	@return		array
	 */
	public function getHeaders( $sort = SORTDATE, $reverse = TRUE, $limit = 0, $offset = 0 )
	{
		$list	= array();
		$sort	= imap_sort( $this->connection->getStream(), $sort, (int) $reverse, SE_UID );
		$sort	= array_slice( $sort, $offset, $limit );
		foreach( $sort as $uid )
		{
			$list[$uid]	= $this->getHeadersByUid( $uid );
		}
		return $list;
	}

	/**
	 *	Returns Message Object.
	 *	@access		public
	 *	@param		int			$messageNumber		Number of Message in Folder
	 *	@return		Net_IMAP_Message
	 */
	public function getMessage( $messageNumber )
	{
		return new Net_IMAP_Message( $this->connection, $messageNumber );
	}

	/**
	 *	Returns Status Information of Mail Box.
	 *	@access		public
	 *	@param		string		$folder			Folder in Mail Box
	 *	@return		array
	 */
	public function getStatusInfo( $folder = NULL )
	{
		$address	= $this->connection->getAddress( $folder );
		$info		= imap_status( $this->connection->getStream(), $address, SA_ALL );
		if( !$info )
			throw new Exception( "imap_status() failed: ". imap_lasterror() );
		return array(
			"messages"	=> $info->messages,
			"recent"	=> $info->recent,
			"unseen"	=> $info->unseen,
		);
	}
}
?>