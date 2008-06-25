<?php
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Net_IMAP_Box
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Net_IMAP_Connection	$connection		IMAP Connection Object
	 *	@return		void
	 */
	public function __construct( $connection )
	{
		$this->resource	= $connection;
		$this->stream	= $connection->getStream();
	}

	/**
	 *	Returns Information Array of Mail Box.
	 *	@access		public
	 *	@param		string		$folder			Folder within Mail Box
	 *	@return		array
	 */
	public function getBoxInfo( $folder = NULL )
	{
		$address	= $this->resource->getAddress( $folder );
		$info		= imap_mailboxmsginfo( $this->stream, $address, SA_ALL );
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
		$address	= $this->resource->getAddress();
		$folders	= imap_listmailbox( $this->stream, $address, $folder );
		foreach( $folders as $folder )
			$list[]	= str_replace( $address, "", imap_utf7_decode( $folder ) );
		return $list;
	}

	/**
	 *	Returns Array of Message Header Information.
	 *	@access		public
	 *	@param		int			$sort			Sort (SORTDATE | SORTARRIVAL | SORTFROM | SORTSUBJECT | SORTTO | SORTCC | SORTSIZE)
	 *	@return		array
	 */
	public function getHeaders( $sort = SORTDATE, $reverse = TRUE )
	{
		$messages	= array();
		remark( $this->resource->getAddress() );
		$sort		= imap_sort( $this->stream, $sort, (int) $reverse, 0 );
		$messages	= array();
		foreach( $sort as $id )
		{
			$message	= imap_header( $this->stream, $id );
			$from		= $message->from[0];

			$from_address	= isset( $from->host ) ? $from->mailbox."@".$from->host : $from->mailbox;
			$from_label		= isset( $from->personal ) ? $from->personal : "";
			$messages[$id]	= array(
				"subject"		=> $message->subject,
				"from_label"	=> $from_label,
				"from_address"	=> $from_address,
				"date"			=> strtotime( $message->date ),
				"message_id"	=> (int) $message->message_id,
				"size"			=> (int) $message->Size,
				"msgno"			=> $message->Msgno,
				"recent"		=> (bool) (int) $message->Recent,
				"flagged"		=> $message->Flagged,
				"date"			=> strtotime( $message->date ),
				"answered"		=> $message->Answered == "A",
				"deleted"		=> $message->Deleted == "D",
				"unseen"		=> $message->Unseen == "U",
				"draft"			=> $message->Draft == "X",
			);
		}
		return $messages;
	}

	/**
	 *	Returns Message Object.
	 *	@access		public
	 *	@param		int			$messageNumber		Number of Message in Folder
	 *	@return		Net_IMAP_Message
	 */
	public function getMessage( $messageNumber )
	{
		return new Net_IMAP_Message( $this->resource, $messageNumber );
	}

	/**
	 *	Returns Status Information of Mail Box.
	 *	@access		public
	 *	@param		string		$folder			Folder in Mail Box
	 *	@return		array
	 */
	public function getStatusInfo( $folder = NULL )
	{
		$address	= $this->resource->getAddress( $folder );
		$info		= imap_status( $this->stream, $address, SA_ALL );
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