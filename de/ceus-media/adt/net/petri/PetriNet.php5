<?php
import ("de.ceus-media.math.algebra.Vector");
/**
 *	Petri Net Implementation.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
/**
 *	Petri Net Implementation.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
class PetriNet
{
	/**	@var	Set			_places		Set of Places */
	var $_places;
	/**	@var	Set			_transitions	Set of Transitions */
	var $_transitions;
	/**	@var	KeyMatrix	_forward		Matrix of forward connections */
	var $_forward;
	/**	@var	KeyMatrix	_backward	Matrix of backward connections */
	var $_backward;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Set			places		Set of Places
	 *	@param		Set			places		Set of Transitions
	 *	@param		KeyMatrix	forward		Matrix of forward connections
	 *	@param		KeyMatrix	backward		Matrix of backward connections
	 *	@return		void
	 */
	public function __construct ($places, $transitions, $forward, $backward)
	{
		$this->_places	= $places;
		$this->_transitions	= $transitions;
		$this->_forward	= $forward;
		$this->_backward	= $backward;
	}
	
	/**
	 *	Returns a typical Vector of all Tokens of all Places .
	 *	@access		public
	 *	@return		Vector
	 */
	function getTokenVector ()
	{
		$tokens = array ();
		$this->_places->rewind ();
		while ($place = $this->_places->getNext())
		$tokens[] = $place->getTokenSize ();
		$v = new Vector ($tokens);
		return $v;
	}

	/**
	 *	Returns HTML-Code for displaying the current state of the Petri Net.
	 *	@access		public
	 *	@return		string
	 */
	function toHTML ()
	{
		$places		= $this->_places->toString();
		$tokens		= $this->getTokenVector();
		$tokens		= $tokens->toString();
		$transitions	= $this->_transitions->toString();
		$forward		= $this->_forward->toTable();
		$backward	= $this->_backward->toTable();
		$code = "
<br>Places: ".$places."
<br>Tokens: ".$tokens."
<br>Transitions: ".$transitions."
<br>Forward: ".$forward."
Backward: ".$backward;
		return $code;
	}

	/**
	 *	Fires a transition by taking Tokens from all input Places and putting Tokens to all output Places.
	 *	@access		public
	 *	@param		Transition	transition		Transition to fire
	 *	@return		void
	 */
	function fireTransition ($transition)
	{
		$inputs = $this->getInputPlacesOfTransition ($transition);
		foreach ($inputs as $input => $tokens)
		{
			$this->_places->rewind ();
			while ($place =& $this->_places->getNext())
			{
//				echo "<br>checking :".$place->getName ()."?".$input;
				if ($place->getName () == $input)
				{
//					echo "<br>removing $tokens Tokens from Place ".$place->getName ();
					$place->removeTokens ($tokens);
				}
			}
		}
		$outputs = $this->getOutputPlacesOfTransition ($transition);
		foreach ($outputs as $output => $tokens)
		{
			$this->_places->rewind ();
			while ($place =& $this->_places->getNext())
			{
//				echo "<br>checking :".$place->getName ()."?".$input;
				if ($place->getName() == $output)
				{
//					echo "<br>add $tokens Tokens to Place ".$place->getName ();
					$place->addTokens ($tokens);
				}
			}	
		}
	}

	/**
	 *	Indicates wheter a Transition is ready to fire.
	 *	@access		public
	 *	@param		Transition	transition		Transition to check
	 *	@return		bool
	 */
	function checkTransition ($transition)
	{
		$inputs = $this->getInputPlacesOfTransition ($transition);
		foreach ($inputs as $input => $tokens)
		{
			$this->_places->rewind ();
			while ($place = $this->_places->getNext())
			{
				if ($place->getName () == $input)
				{
					if (!$place->hasTokens ($tokens))
						return false;
				}
			}
		}
		return true;
	}
	
	/**
	 *	Returns a list of all input Places of a Transition.
	 *	@access		public
	 *	@param		Transition	transition		Transition to get all input places for
	 *	@return		array
	 */
	function getInputPlacesOfTransition ($transition)
	{
		$keys	= $this->_backward->_keys_y;
		$row	= $this->_backward->getRow ($transition);
		$values	= $row->toArray();
		for($i = 0; $i < count($keys); $i++)
			if ($values[$keys[$i]] != 0)
				$input[$keys[$i]] = $values[$keys[$i]];
		return $input;
	}

	/**
	 *	Returns a list of all output Places of a Transition.
	 *	@access		public
	 *	@param		Transition	transition		Transition to get all output places for
	 *	@return		array
	 */
	function getOutputPlacesOfTransition ($transition)
	{
		$keys	= $this->_forward->_keys_x;
		$column	= $this->_forward->getColumn ($transition);
		$values	= $column->toArray();
		for($i = 0; $i < count($keys); $i++)
			if ($values[$keys[$i]] != 0)
				$output[$keys[$i]] = $values[$keys[$i]];
		return $output;
	}
}
?>