<?php
/**
 *	@package	ui
 *	@subpackage	error
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	ui
 *	@subpackage	error
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Error
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return 		void
	 */
	public function __construct( $errstr, $errfile, $errline )
	{
		user_error( $errstr, E_USER_ERROR );
	}
}
?>