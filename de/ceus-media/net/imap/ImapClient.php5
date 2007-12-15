<?php
import ("de.ceus-media.net.imap.ImapConnection");
/**
 *	Client Implementation for Accessing a IMAP eMail Server.
 *	@package		net
 *	@subpackage		imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.4
 */
/**
 *	Client Implementation for Accessing a IMAP eMail Server.
 *	@package		net
 *	@subpackage		imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.4
 *	@todo			Code Documentation
 */
class ImapClient
{
	var $_host;
	var $_port;
	
	public function __construct( $host, $port = 143 )
	{
		$this->_host	= $host;
		$this->_port	= $port;
	}
	
	function openConnection( $username, $password, $folder = "" )
	{
		$imap	= new ImapConnection( $this->_host, $this->_port, $folder );
		$imap->open( $username, $password );
		return $imap;
	}
	
	function closeConnection( $box )
	{
		$box->close();
	}

	function getFolders( $username, $password )
	{
		$box	= $this->openConnection( $username, $password );
		$folder_list	= array();
		$address	= $this->_getAddress();
		$folders	= imap_getmailboxes( $box->getStream(), $address, "*" );
		if( $folders === false )
			trigger_error( "Call failed", E_USER_ERROR );
		else
			foreach( $folders as $folder )
				$folder_list[]	= str_replace( $address, "", $folder->name );
		$this->closeConnection( $box );
		return $folder_list;
	}

	function getHeaders( $username, $password, $folder )
	{
		$header_list	= array();
		$box	= $this->openConnection( $username, $password, $folder );
		$sort	= imap_sort( $box->getStream(), SORT_DATE, 1, 0 );
		$ids		= array();
		$messages	= array();
		foreach( $sort as $id )
		{
			$message	= imap_header( $box->getStream(), $id );
			$from		= $message->from[0];
			$from		= $from->mailbox."@".$from->host;
			$messages[] = array(
				"subject"		=> $message->subject,
				"from"		=> $from,
				"date"		=> strtotime( $message->Date ),
				"message_id"	=> (int)$message->message_id,
				"size"		=> (int)$message->Size,
				"msgno"		=> $message->Msgno,
				"recent"		=> (bool)(int)$message->Recent,
				"flagged"		=> $message->Flagged,
				"answered"	=> $message->Answered == "A" ? 1 : 0,
				"deleted"		=> $message->Deleted == "D" ? 1 : 0,
				"unseen"		=> $message->Unseen == "U" ? 1 : 0,
				"draft"		=> $message->Draft == "X" ? 1 : 0,
				);
			if( substr_count( $message->subject, "Ihr Pass" ) && 0)
			{
				print_m( $message );
				print_m( $messages );
				die;}
		}
		$this->closeConnection( $box );
		return $messages;
	}
	function _getAddress( $folder = "")
	{
		return "{".$this->_host.":".$this->_port."}".$folder;
	}
	
	function getBoxInfo( $username, $password, $folder )
	{
		$box	= $this->openConnection( $username, $password, $folder );
		$info	= imap_mailboxmsginfo( $box->getStream() );

		if ($info)
		{
			return array(
				"date"		=> $info->Date,
				"driver"		=> $info->Driver,
				"mailbox"		=> $info->Mailbox,
				"messages"	=> $info->Nmsgs,
				"recent"		=> $info->Recent,
				"size"		=> $info->Size,
				);
		}
		else
			trigger_error( "imap_mailboxmsginfo() failed: ". imap_lasterror(), E_USER_ERROR );
		$this->closeConnection( $box );
	}
	
	function getMessageStructure( $username, $password, $folder, $msg_no )
	{
		$box	= $this->openConnection( $username, $password, $folder );
		$structure	= imap_fetchstructure( $box->getStream(), $msg_no );
		
		$this->closeConnection( $box );
		return $structure;
	
	}
}
?>