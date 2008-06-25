<?php
/**
 *	Exception for Input/Output Errors.
 *	@package		mv2.exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
/**
 *	Exception for Input/Output Errors.
 *	@package		mv2.exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
class Exception_IO extends RuntimeException
{
	/**	@var		string		$sourceUri		Name of Source which was not fully accessible */
	private $sourceUri			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$sourceUri		Error Code
	 *	@return		void
	 */
	public function __construct( $message = null, $sourceUri = "" )
	{
		parent::__construct( $message );
		$this->sourceUri	= $sourceUri;
	}
	
	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getSourceUri()
	{
		return $this->sourceUri;	
	}
}
?>
