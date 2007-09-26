<?php
import ("de.ceus-media.math.syntax.Term");
/**
 *	@package	math
 *	@subpackage	syntax
 *	@extends	Term
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	math
 *	@subpackage	syntax
 *	@extends	Term
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		check sense, finish
 *	@todo		Code Documentation
 */
class Variable extends Term
{
	public function __construct ($string)
	{
		$this->_var = $string;
		parent::__construct ($string, new Literal(1), "^");
	}
	
	function getVariable ()
	{
		return $this->_var;
	}
	function realize ($value)
	{
		return $value;
	}
	
	function diff ()
	{
		$op1 = $this->_o1;
		$op2 = $this->_o2;
		if ($this->isLiteral ($op2))
		{
			if ($op2->getValue() == 1)
				$variable = new Literal (1);
			else
				$variable = new Variable ($op1, new Literal ($op2->getValue () - 1), "^");
		}
		else
			die ("var: diff not yet implemented for (".get_class($op1).", ".get_class($op2).").");
		return $variable;
	}
}
?>