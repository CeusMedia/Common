<?php
/**
 *	Header for HTTP Requests.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Header for HTTP Requests.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class HTTP_Header
{
	/**	@var	string	$_key		Key of Header */

	var $_key;
	/**	@var	string	$_value		Value of Header */
	var $_value;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$key		Key of Header
	 *	@param		string	$value		Value of Header
	 *	@return		void
	 */
	function HTTP_Header( $key, $value )
	{
		$this->_key = $key;
		$this->_value = $value;	
	}
	
	/**
	 *	Returns a representative string of Header.
	 *	@access		public
	 *	@return		string
	 */
	function toString()
	{
		if( $this->_key )
			return $this->_key.": ".$this->_value."\r\n";
		else
			return "\r\n";
	}
}
?>