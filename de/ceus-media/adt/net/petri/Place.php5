<?php
/**
 *	Place of a Petri Net.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
/**
 *	Place of a Petri Net.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
class Place
{
	/**	@var	string		_name	Name of the Place */
	var $_name;
	/**	@var	int			_tokens	Amount of Tokens in the Place */
	var $_tokens = 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	name	Name of the Place
	 *	@return		void
	 */
	public function __construct ($name)
	{
		$this->_name = $name;
	}

	/**
	 *	Returns the name of the Place.
	 *	@access		public
	 *	@return		string
	 */
	function getName ()
	{
		return $this->_name;
	}

	/**
	 *	Returns the name of the Place.
	 *	@access		public
	 *	@param		int		amount		Amount of Tokens in the Place
	 *	@return		void
	 */
	function addTokens ($amount)
	{
		$this->_tokens += $amount;
	}

	/**
	 *	Removes an amount of Tokens from the Place.
	 *	@access		public
	 *	@param		int		amount		Amount of Tokens to be removed
	 *	@return		void
	 */
	function removeTokens ($amount)
	{
		if ($this->getTokenSize() < $amount)
			trigger_error( "To less Tokens to remove $amount" );
		$this->_tokens -= $amount;
	}

	/**
	 *	Returns the amount of Tokens in the Place.
	 *	@access		public
	 *	@return		int
	 */
	function getTokenSize ()
	{
		return $this->_tokens;
	}
	
	/**
	 *	Indicates wheter the Place has an amount of Tokens.
	 *	@access		public
	 *	@param		int		amount	Amount of Tokens in the Place
	 *	@return		bool
	 */
	function hasTokens ($amount)
	{
		return $this->getTokenSize() >= $amount;
	}

	/**
	 *	Returns Place as a reprasentative String.
	 *	@access		public
	 *	@return		string
	 */
	function toString ()
	{
		return "(".$this->getName().":".$this->getTokenSize().")";
	}
}
?>