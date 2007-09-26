<?php
/**
 *	@package	adt
 *	@subpackage	language
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	adt
 *	@subpackage	language
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@deprecated	not used by Alphabet
 */
class Sign
{
	var $_value;	

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$value	Value of the Sign
	 *	@return		void
	 */
 	public function __construct ($value)
	{
		$this->_setSign ($value);
	}
	
	/**
	 *	Returns the value of the Sign.
	 *	@access		public
	 *	@return		string
	 */
	function getSign ()
	{
		return $this->_value;
	}
	
	/**
	 *	Sets the value of the Sign.
	 *	@access		private
	 *	@param		string	$value	Value of the Sign
	 *	@return		void
	 */
	function _setSign ($value)
	{
		$this->_value = $value;
	}
}
?>