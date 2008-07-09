<?php
/**
 *	Exception for Input/Output Errors.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
/**
 *	Exception for Input/Output Errors.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_IO extends Exception
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$code			Error Code
	 *	@return		void
	 */
	public function __construct( $message = null, $code = 0 )
	{
		parent::__construct( $message, $code );
	}
}
?>
