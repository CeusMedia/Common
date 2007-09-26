<?php
/**
 *	Exception for SQL Errors.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
/**
 *	Exception for SQL Errors.
 *	@package		mv2.exception
 *	@extends		Exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_SQL extends Exception
{
	/**	@var	string		$error		Error Message from SQL */
	public $error;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		string		$error			SQL Error Message
	 *	@return		void
	 */
	public function __construct( $message, $error )
	{
		parent::__construct( $message );
		$this->error	= $error;
	}
	
	/**
	 *	Returns SQL Error Message.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		return $this->error;
	}
/*	
	public function __sleep()
	{
		get_object_vars( $this );
	}
	
	public function _sleep()
	{
		get_object_vars( $this );
	}
	

	private function check( $array )
	{
		foreach( $array as $element )
		{
			if ( $element instanceof PDO )
			{
			}
			if ( $element instanceof PDOException )
			{
				unset($element);
				break;
			}
			if ( is_array( $element ) )
			{
				$this->_check( $element );
			}
		}
	}*/
}
?>
