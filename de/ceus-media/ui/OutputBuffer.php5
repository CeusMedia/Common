<?php
/**
 *	Buffer for Standard Output Channel.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.09.2005
 *	@version		0.1
 */
/**
 *	Buffer for Standard Output Channel.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.09.2005
 *	@version		0.1
 */
class OutputBuffer
{
	/**	@var		bool		$_open		Flag: Buffer opened */
	var $_open = false;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$open		Flag: open Buffer with Instance
	 *	@return		void
	 */
	public function __construct ( $open = true )
	{
		if( $open )
			$this->open();
	}
	
	/**
	 *	Clears Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	function clean()
	{
		ob_clean();
	}
	
	/**
	 *	Clears Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	function close()
	{
		ob_end_clean();
		$this->_open = false;
	}

	/**
	 *	Return Content and clear Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	function flush()
	{
		ob_flush();
	}
	
	/**
	 *	Returns Content of Output Buffer.
	 *	@access		public
	 *	@param		bool		$clean		Flag: clear Output Buffer afterwards		
	 *	@return		string
	 */
	function get( $clean = false )
	{
		$content = "";
		if( $this->isOpen() )
		{
			$content = ob_get_contents();	
			if( $clean )
				$this->clean();
		}
		return $content;
	}

	/**
	 *	Indicates whether Output Buffer is open.
	 *	@access		public
	 *	@return		void
	 */
	function isOpen()
	{
		return (bool) $this->_open;
	}
	
	/**
	 *	Opens Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	function open()
	{
		ob_start();
		$this->_open = true;
	}
}
?>