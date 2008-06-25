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
	public $sqlCode;
	/**	@var		string		$error				Error Message from SQL */
	public $sqlMessage;
	/**	@var		string		$error				Error Message from SQL */
	public $pdoCode;
	/**	@var		string		$exceptionMessage	Message of Exception with Placeholder */
	public static $exceptionMessage	= 'SQL Error';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$sqlCode		SQL Error Code
	 *	@param		string		$sqlMessage		SQL Error Message
	 *	@param		int			$pdoCode		PDO Error Code
	 *	@return		void
	 */
	public function __construct( $sqlCode, $sqlMessage, $pdoCode = 0 )
	{
		$this->sqlCode		= $sqlCode;
		$this->sqlMessage	= $sqlMessage;
		$this->pdoCode		= $pdoCode;
		parent::__construct( self::$exceptionMessage );
	}
	
	/**
	 *	Returns SQL Error Message.
	 *	@access		public
	 *	@return		string
	 */
	public function getErrorCode()
	{
		return $this->sqlCode;
	}

	/**
	 *	Returns SQL Error Message.
	 *	@access		public
	 *	@return		string
	 */
	public function getErrorMessage()
	{
		return $this->sqlMessage;
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
