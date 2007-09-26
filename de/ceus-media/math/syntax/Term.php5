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
 *	@todo		Code Documentation
 */
class Term
{
	public function __construct ($operant1, $operant2 = false, $operation = false)
	{
		$this->_o1 = $operant1; 
		$this->_o2 = $operant2; 
		$this->_op = $operation; 
	}
	
	function isTerm ($object)
	{
		if (is_object ($object) && get_class($object) == "term")
			return true;
		return false;
	}
	
	function isLiteral ($object)
	{
		if (is_object ($object) && get_class($object) == "literal")
			return true;
		return false;
	}
	
	function isVariable ($object)
	{
		if (is_object ($object) && get_class($object) == "variable")
			return true;
		return false;
	}
	
	function getValue ($values = array ())
	{
		$op1 = $this->_o1;
		if (is_object ($op1))
		{
			if ($this->isTerm ($op1) || $this->isLiteral ($op1))
				$op1 = $op1->getValue ($values);
			else if ($this->isVariable ($op1))
				$op1 = $op1->realize ($values[$op1->getVariable()]);
		}
		$op2 = $this->_o2;
		if (is_object ($op2))
		{
			if ($this->isTerm ($op2) || $this->isLiteral ($op2))
				$op2 = $op2->getValue ($values);
			else if ($this->isVariable ($op2))
				$op2 = $op2->realize ($values[$op2->getVariable()]);
		}
//		echo "<br>operate: $op1 ".$this->_op." $op2";
		$value = $this->_operate ($op1, $op2);
		return $value;
	}

	function diff ()
	{
		$op1 = $this->_o1;
		$op2 = $this->_o2;
		$op = $this->_op;
		if (!$op && !$op2)
			return $op1;
		if ($op == "*")
		{
			if ($this->isLiteral ($op1) && $this->isVariable ($op2))						//  c * x
				$term = new Term ($op1, $op2->diff(), $op);
			else if ($this->isLiteral ($op1) && $this->isTerm($op2))					//  c * ()
				$term = new Term ($op1, $op2->diff(),$op);
			else if ($this->isLiteral ($op2) && $this->isTerm($op1))					//  () * c
				$term = new Term ($op2, $op1->diff(), $op);
			else if ($this->isTerm ($op1) && $this->isTerm ($op2))						//  () * ()
				$term = new Term (new Term ($op1->diff(), $op2,$op), new Term ($op1, $op2->diff(), $op), "+");
			else
				die ("term: diff [*] not yet implemented for (".get_class($op1).", ".get_class($op2)." [".$op2->getExpression()."]).");
		}
		else if ($op == "+" || $op == "-")
		{
			$term = new Term ($op1->diff(), $op2->diff(), "+");
		}
		else if ($op == "/")
		{
			if ($op1->getValue ()<=0) die ("diff [/]: v > 0");
			$top = new Term (new Term ($op1->diff(), $op2, "*"), new Term ($op1, $op2->diff(), "*"), "-");
			$bot = new Term ($op2, new Literal(2), "^");
			$term = new Term ($top, $bot, "/");
		}
		else if ($op == "^")
		{
			if ($this->isVariable ($op1) && $this->isLiteral ($op2))					//   x ^ c
				$term = new Term ($op2, new Term ($op1, new Literal ($op2->getValue()-1), $op), "*");
			else
				die ("term: diff [^] not yet implemented for (".get_class($op1).", ".get_class($op2).").");
		}
		else
			die ("tem: diff: no known operation set");
		return $term->trim ();
	}

	function _operate ($operant1, $operant2)		
	{
		$op = $this->_op;
		if ($op == "*")
			$value = $operant1 * $operant2;
		else if ($op == "+")
			$value = $operant1 + $operant2;
		else if ($op == "-")
			$value = $operant1 - $operant2;
		else if ($op == "/")
			$value = $operant1 / $operant2;
		else if ($op == "^")
			$value = pow ($operant1, $operant2);
		else
			$value = $operant1;
//			die ("no known operation set");
		return $value;
	}
	
	function getExpression ()
	{
		$op1 = $this->_o1;
		if (is_object ($op1))
		{
			if ($this->isTerm ($op1))
				$op1 = $op1->getExpression ();
			else if ($this->isVariable ($op1))
				$op1 = $op1->getVariable();
			else if ($this->isLiteral ($op1))
				$op1 = $op1->getValue();
		}
		$op2 = $this->_o2;
		if (is_object ($op2))
		{
			if ($this->isTerm ($op2))
				$op2 = $op2->getExpression ();
			else if ($this->isVariable ($op2))
				$op2 = $op2->getVariable();
			else if ($this->isLiteral ($op2))
				$op2 = $op2->getValue();
		}
		if ($op2)
			$exp = "(".$op1.$this->_op.$op2.")";
		else
			$exp = $op1;
		return $exp;
	}
	
	function trim ()
	{
//		return $this;
		$op1 = $this->_o1;
		$op2 = $this->_o2;
		$op = $this->_op;
		if ($op == "*")
		{
			$f0	= $this->isLiteral($op1) && $op1->getValue() == 0;
			$f1	= $this->isLiteral($op1) && $op1->getValue() == 1;
			$s0	= $this->isLiteral($op2) && $op2->getValue() == 0;
			$s1	= $this->isLiteral($op2) && $op2->getValue() == 1;
			if ($f0 || $s0)
				return new Literal (0);
			if ($f1 || $s1)
				if ($f1)
					return $op2;
				else if ($s1)
					return $op1;
		}
		else if ($op == "+")
		{
			if ($this->isLiteral($op1) && $op1->getValue() == 0)
				return $op2;
			if ($this->isLiteral($op2) && $op2->getValue() == 0)
				return $op1;
		}
		else if ($op == "^")
		{
			if ($this->isLiteral($op2) && $op2->getValue() == 0)
				return new Literal (1);
			if ($this->isLiteral($op2) && $op2->getValue() == 1)
				return $op1;
		}
		return $this;
	}
	
	function toHTML ($deepth = 0)
	{
		$op1 = $this->_o1;
		$op2 = $this->_o2;
		$op	= $this->_op;
		
		if ($this->isLiteral ($op1))
			$op1 = $op1->getValue ();
		else if ($this->isVariable ($op1))
			$op1 = $op1->getVariable();
		else if ($this->isTerm ($op1))
			$op1 = $op1->toHTML ($deepth + 1);
		if ($this->isLiteral ($op2))
			$op2 = $op2->getValue ();
		else if ($this->isVariable ($op2))
			$op2 = $op2->getVariable();
		else if ($this->isTerm ($op2))
			$op2 = $op2->toHTML ($deepth + 1);
		$code = "
<table style='".(($deepth == 0)?"border: 1px solid grey'":"")."' cellpadding='1' cellspacing='2'>
  <tr><td colspan='2' align='center'><b>".$op."</b></td></tr>
  <tr><td style='border: 1px solid grey' valign='top'>".$op1."</td><td style='border: 1px solid grey' valign='top'>".$op2."</td></tr>
</table>";
		return $code;
	}
}
?>