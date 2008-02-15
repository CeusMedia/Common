<?php
/**
 *	Buffer for Standard Output Channel.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.09.2005
 *	@version		0.6
 */
/**
 *	Buffer for Standard Output Channel.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.09.2005
 *	@version		0.6
 */
class UI_OutputBuffer
{
	/**	@var		bool		$isOpen		Flag: Buffer opened */
	protected $isOpen = false;
	
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
	public function clear()
	{
		ob_clean();
	}
	
	/**
	 *	Closes Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function close()
	{
		ob_end_clean();
		$this->isOpen = false;
	}

	/**
	 *	Return Content and clear Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function flush()
	{
		ob_flush();
	}
	
	/**
	 *	Returns Content of Output Buffer.
	 *	@access		public
	 *	@param		bool		$clear		Flag: clear Output Buffer afterwards		
	 *	@return		string
	 */
	public function get( $clear = false )
	{
		if( !$this->isOpen() )
			throw new RuntimeException( 'Output Buffer is not open.' );
		return $clear ? ob_get_clean() : ob_get_contents();
	}

	/**
	 *	Indicates whether Output Buffer is open.
	 *	@access		public
	 *	@return		void
	 */
	public function isOpen()
	{
		return (bool) $this->isOpen;
	}
	
	/**
	 *	Opens Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function open()
	{
		if( $this->isOpen() )
			throw new RuntimeException( 'Output Buffer is already open.' );
		ob_start();
		$this->isOpen = true;
	}
}
?>