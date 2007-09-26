<?php
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
/**
 *	MailBox Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 *	@todo		Code Documentation
 */
class ImapMailBox
{
	public function __construct( $stream )
	{
		$this->_stream	= $stream;	
	}

	function getBoxInfo()
	{
		$info = imap_mailboxmsginfo( $this->_stream );
		if( is_object( $info ) )
			return $info;
		else
			trigger_error( "imap_mailboxmsginfo() failed: ". imap_lasterror(), E_USER_ERROR );
	}
	
	function getHeaders()
	{
		$messages	= array();
		$sort		= imap_sort( $this->_stream, SORT_DATE, 1, 0 );
		$ids			= array();
		$messages	= array();
		foreach( $sort as $id )
		{
			$message			= imap_header( $this->_stream, $id );
			$message->Date		= strtotime( $message->Date );
			$message->Answered	= $message->Answered == "A" ? 1 : 0;
			$message->Deleted	= $message->Deleted == "D" ? 1 : 0;
			$message->Unseen	= $message->Unseen == "U" ? 1 : 0;
			$message->Draft		= $message->Draft == "X" ? 1 : 0;
			$messages[]			= $message;
		}
		return $messages;
	}
}
?>