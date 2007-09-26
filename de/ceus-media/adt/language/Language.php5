<?php
/**
 *	Formal Language Implementation.
 *	@package		adt
 *	@subpackage		language
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Formal Language Implementation.
 *	@package		adt
 *	@subpackage		language
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Language
{
	/**	@var	Grammar		$_grammar		Grammar of Language */
	var $_grammar;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Grammar	$grammar		Grammar of Language
	 *	@return		void
	 */
	function Language ($grammar)
	{
		$this->_grammar	= $grammar;
	}
	
	/**
	 *	Returns the Chomsky-Type of Language.
	 *	@access		public
	 *	@return		int
	 */
	function getType ()
	{
		return $this->_grammar->getType ();
	}
	
	/**
	 *	Indicates whether Language is of a Chomsky-Type.
	 *	@access		public
	 *	@param		int		$type			Chomsky-Type (0-3)
	 *	@return		bool
	 */
	function isType ($type)
	{
		return $this->_grammar->isType ($type);
	}

	/**
	 *	Returns an array of rules used to evolute of a word to another word if possible.
	 *	@access		public
	 *	@param		string	$word_start		Start word to be evolved
	 *	@param		string	$word_end		End word to to evolved to
	 *	@param		int		$max_depth		Maximum depth of recursion
	 *	@param		array	$used_rules		Array of rules used before
	 *	@param		int		$depth			Current depth of recursion
	 *	@return		array
	 */
	function evolve ($word_start, $word_end, $max_depth = 4, $used_rules = array(), $depth = 0)
	{
		if (!$this->_grammar->isType (1))
		{
			trigger_error( "Grammar is not determinable and can not be evolved. Grammar must be at least Chomsky Type 1.", E_USER_WARNING );
			return array();
		}
		$depth++;
		if ($depth > $max_depth)
			return;
		$rules = $this->_grammar->getRules ();
		foreach ($rules as $rule)
		{
			$rule_var		= $rule->getKey ();
			$rule_term	= $rule->getValue ();
//			echo "<br/>d: ".$depth." | w: ".$word_start."  |  ".$rule_var." => ".$rule_term;
			$_rules	= $used_rules;
			$_rules[]	= array($rule_var => $rule_term);
			if (false === strpos ($word_start, $rule_var))
				continue;
			$first	= substr ($word_start, 0, strpos ($word_start, $rule_var));
			$last	= substr ($word_start, strpos ($word_start, $rule_var)+strlen($rule_var));
			$maybe = $first.$rule_term.$last;
			if ($maybe == $word_end)
				return $_rules;
			else
			{
				$way = $this->evolve ($maybe, $word_end, $max_depth, $_rules, $depth);
				if (count ($way))
					return $way;
			}
		}
	}

	/**
	 *	Indicates whether a word is evolvable to another word.
	 *	@access		public
	 *	@param		string	$word_start		Start word to be evolved
	 *	@param		string	$word_end		End word to to evolved to
	 *	@param		int		$max_depth		Maximum depth of recursion
	 *	@return		bool
	 */
	function isEvolvable ($word_start, $word_end, $max_depth = 4)
	{
		$way = $this->evolve($word_start, $word_end, $max_depth);
		$count = count($way);
		return $count > 0;
	}

}
?>