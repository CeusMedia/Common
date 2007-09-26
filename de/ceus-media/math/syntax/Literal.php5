<?php
/**
 *	@package	math
 *	@subpackage	syntax
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	math
 *	@subpackage	syntax
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documetation
 */
class Literal
{
	public function __construct ($value)
	{
		$this->_value = $value;
	}

	function getValue ()
	{
		return $this->_value;
	}
	
	function diff ()
	{
		return new Literal (0);
	}
}
?>