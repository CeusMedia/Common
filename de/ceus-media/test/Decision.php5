<?php
import ("de.ceus-media.adt.set.Set");
/**
 *	@package	test
 *	@uses		Set
 *	@version		0.4
 *	@todo		Code Documentation
 */
/**
 *	@todo		Code Documentation
 */
class Decision
{
	var $_answer;
	var $_reasons;
	public function __construct ($answer, $reasons = false)
	{
		$this->_setAnswer ($answer);	
		$this->_setReasons ($reasons);
	}

	function getAnswer ()
	{
		return ((bool)$this->_answer)?1:0;
		//return $this->_answer;
	}	

	function getReasons ()
	{
		return $this->_reasons;
	}
	
	
	function _setAnswer ($answer)
	{
		$this->_answer = (bool) $answer;
	}

	function addReason ($reason)
	{
		$this->_reasons->add ($reason);
	}

	function _setReasons ($reasons = false)
	{
		if (!$reasons) $reasons = new Set ();
		else if (!(is_object ($reasons) && $reasons->getClass () == "Set"))
		{
		 	trigger_error( "reasons of a dicision must be in a set.", E_USER_WARNING );
		}
		$this->_reasons = $reasons;
	}
}
?>