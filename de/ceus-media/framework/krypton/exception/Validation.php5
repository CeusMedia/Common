<?php
/**
 *	Exception for Input Validations.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Exception for Input Validations.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_Validation extends Exception
{
	/**	@var	array		$errors		List of Validation Errors */
	protected $errors	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$errors			List of Validation Errors
	 *	@return		void
	 */
	public function __construct( $message = null, $errors = array() )
	{
		parent::__construct( $message );
		$this->errors	= $errors;
	}
	
	/**
	 *	Returns List of Validation Errors.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}
?>