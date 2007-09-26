<?php
/**
 *	Message Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
/**
 *	Message Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 *	@todo		Code Documentation
 */
class ImapMessage
{
	var $_stream;
	var $_msg_no;
	var $_structure;
	var $_info;

	public function __construct( $stream, $msg_no )
	{
		$this->_stream	= $stream;	
		$this->_msg_no	= $msg_no;	
	}

	function getMessageStructure()
	{
		if( !isset( $this->_structure ) )
			$this->_structure	= imap_fetchstructure( $this->_stream, $this->_msg_no );
		return $this->_structure;
	}
	
	function getMessageInfo()
	{
		if( !isset( $this->_info ) )
			$this->_info	= imap_headerinfo( $this->_stream, $this->_msg_no );
		return $this->_info;
	}
	
	function getBody()
	{
		$body		= imap_body( $this->_stream, $this->_msg_no, FT_PEEK );
		return $body;
	}
	
	function getAttachments($mbox,$msgNum)
	{
		$structure	= $this->getMessageStructure();
		$contentParts	= count( $structure->parts );
		if($contentParts > 1)
		{
			for($i=1; $i<=$contentParts; $i++)
			{
				if($structure->parts[$i]->type > 0)
				{
					$attachments .= $structure->parts[$i]->description;
				}
			}
		}
		return $attachments;
	}
}
?>