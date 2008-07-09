<?php
/**
 *	Exception for SQL Errors.
 *	@package		exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
/**
 *	Exception for SQL Errors.
 *	@package		exception
 *	@extends		RuntimeException
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.03.2007
 *	@version		0.1
 */
class Exception_SQL extends RuntimeException
{
	/**	@var		string		$error				Error Message from SQL */
	protected $pdoCode;
	/**	@var		string		$defaultMessage		Default Message if SQL Info Message is empty */
	public static $default		= "Unknown SQL Error.";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$sqlCode		SQL Error Code
	 *	@param		string		$sqlMessage		SQL Error Message
	 *	@param		int			$pdoCode		PDO Error Code
	 *	@return		void
	 */
	public function __construct( $message, $code, $pdoCode = 0 )
	{
		if( !$message )
			$message	= self::$default;
		parent::__construct( $message, $code );
		$this->pdoCode		= $pdoCode;
	}

	/**
	 *	Returns SQL Error Message.
	 *	@access		public
	 *	@return		string
	 */
	public function getPdoErrorCode()
	{
		return $this->pdoCode;
	}
}
?>
