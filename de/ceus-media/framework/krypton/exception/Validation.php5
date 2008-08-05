<?php
/**
 *	Exception for Input Validations.
 *	@package		framework.krypton.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Exception for Input Validations.
 *	@package		framework.krypton.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_Validation extends Exception
{
	/**	@var	array		$errors		List of Validation Errors */
	protected $errors	= array();
	/**	@var	string		$form		Name Form in Validation File */
	protected $form		= "";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$errors			List of Validation Errors
	 *	@return		void
	 */
	public function __construct( $message = null, $errors = array(), $form = "" )
	{
		parent::__construct( $message );
		$this->errors	= $errors;
		$this->form		= $form;
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
	
	/**
	 *	Returns Name of Form in Validation File.
	 *	@access		public
	 *	@return		string
	 */
	public function getForm()
	{
		return $this->form;
	}
}
?>