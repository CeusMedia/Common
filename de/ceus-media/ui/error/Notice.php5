<?php
/**
 *	@package	message
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	message
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Notice
{
	/**	@var		LogFile		LogFile for not displayed Notices */
	var $_log;
	/**	@var		string		Notice Prefix */
	var $_prefix;
	/**	@var		string		Notice Suffix */
	var $_suffix;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		message		Notice Message
	 *	@param		LogFile		log			Log file object for alternative output
	 *	@return 		void
	 */
	public function __construct ($message, $log = false)
	{
		$this->_prefix = "<b>Notice: </b>";
		$this->_suffix = "<br>";
		if ($message) $this->trigger ($message, $log);
		if ($log) $this->setLog ($log);
	}

	/**
	 *	Sets Message Prefix.
	 *	@access		public
	 *	@param		string		prefix		Prefix of Notice Message
	 *	@return 		void
	 */
	function setPrefix ($prefix)
	{
		$this->_prefix = $prefix;
	}
	
	/**
	 *	Sets LogFile.
	 *	@access		public
	 *	@param		LogFile		log			LogFile object for alternative output
	 *	@return 		void
	 */
	function setLog ($log)
	{
		$this->_log = $log;
	}
	
	/**
	 *	Prints out a Notice Message.
	 *	@access		public
	 *	@param		string		message		Notice  Message
	 *	@param		LogFile		log			Log file object for alternative output
	 *	@return 		void
	 */
	function trigger ($message, $log = false)
	{
		if (!$log && $this->_log) $log = $this->_log;
		if ($log && is_object ($log))
		{
			if (strtolower ($log->getClass ()) == strtolower ("File_Log_Writer")) $log->note ($message);
			else echo ($this->_prefix.$message.$this->_suffix);
		}
		else echo ($this->_prefix.$message.$this->_suffix);
	}
}
?>