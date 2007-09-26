<?php
import ("de.ceus-media.adt.set.SetOperation");
/**
 *	Grammar for formal Languages
 *	@package		adt
 *	@subpackage		language
 *	@extends		Object
 *	@uses			Error
 *	@uses			SetOperation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Grammar for formal Languages
 *	@package		adt
 *	@subpackage		language
 *	@extends		Object
 *	@uses			Error
 *	@uses			SetOperation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Grammar
{
	/**	@var		Set			$variables		Alphabet of variable Symbols */
	var $_variables;
	/**	@var		Set			$terminals		Alphabet of terminal Symbols */
	var $_terminals;	
	/**	@var		array		$rules			Array of Pairs with production rules */
	var $_rules;	
	/**	@var		string		$start			Start symbol */
	var $_start;	
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Alphabet		$variables		Alphabet of variable Symbols
	 *	@param		Alphabet		$terminals		Alphabet of terminal Symbols
	 *	@param		array		$variables		Array of Pairs with production rules
	 *	@param		string		$start			Start symbol
	 *	@return		void
	 */
	public function __construct ($variables, $terminals, $rules, $start = false)
	{
		$this->_variables	= $variables;
		$this->_terminals	= $terminals;
		$this->_rules		= $rules;
		if ($start)
			$this->_start		= $start;
			
		$so = new SetOperation ();
		$inter = $so->intersect ($variables, $terminals);
		if (!$inter->isEmpty ())
			trigger_error( "Intersection between Variables and Terminals must be empty.", E_USER_WARNING );
	}
	
	/**
	 *	Returns an array of Pairs with production rules.
	 *	@access		public
	 *	@return		array
	 */
	function getRules ()
	{
		return $this->_rules;
	}
	
	/**
	 *	Returns the Chomsky-Type of Grammar.
	 *	@access		public
	 *	@return		int
	 */
	function getType ()
	{
		$types = array_reverse (array (0, 1, 2, 3));
		foreach ($types as $type)
			if ($this->isType ($type))
				return $type;
	}
	
	/**
	 *	Indicates whether Grammar is of a Chomsky-Type.
	 *	@access		public
	 *	@param		int		$type		Chomsky-Type (0-3)
	 *	@return		bool
	 */
	function isType ($type)
	{
		$return = false;
		switch ($type)
		{
			case 0:												//  Phasenstrukturgrammatik (aufzählbar, semi-entscheidbar mit Turing-Maschine)
				return true;
			case 1:												//  kontextsensitive Grammatik (endscheidbar
				if ($this->isType (0))
				{
					$return = true;
					foreach ($rules = $this->getRules() as $rule)
						if (strlen ($rule->getKey ()) > strlen($rule->getValue ()))
							$return = false;
				}
				break;
			case 2:												//  kontextfreie Grammatik
				if ($this->isType (0) && $this->isType (1))
				{
					$return = true;
					foreach ($this->getRules() as $rule)
						if (!$this->_variables->has ($rule->getKey ()))
							$return = false;
				}
				break;
			case 3:												//  reguläre Grammatik
				if ($this->isType (0) && $this->isType (1) && $this->isType (2))
				{
					$return = true;
					$so = new SetOperation ();
					$cross = $so->produceCross ($this->_terminals, $this->_variables);
					$rules	= $this->getRules();
					foreach ($rules as $rule)
						if (!$this->_terminals->has($rule->getValue ()))
							if (!$cross->has ($rule->getValue ()))
								$return = false;
				}
				break;
		}
		return $return;
	}
}
?>