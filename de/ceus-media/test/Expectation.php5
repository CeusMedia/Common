<?php
/**
 *	@package	test
 *	@uses		Decision
 *	@version		0.4
 */
import ("de.ceus-media.test.Decision");
class Expectation
{
	/**	@var	string	$_speculation		Adoption to be expected */
	var $_speculation = false;
	var $_reality = false;
	public function __construct ($speculation = false, $reality = false)
	{
		$this->expect ($speculation);
		$this->realize ($reality);
	}
	
	function answer ($reality)
	{
	 	$decision = $this->realize ($reality);
	 	return $decision->getAnswer ();
	}
	
	function expect ($speculation)
	{
		$this->_speculation = $speculation;
	}

	function getSpeculation ()
	{
		return $this->_speculation;
	}

	function realize ($reality)
	{
		return $this->decide($this->_speculation, $reality);
	}

	function getDecision ()
	{
		return decide ($this->_speculation, $this->_reality);
	}
	
	function decide ($speculation, $reality)
	{
		return new Decision ($speculation == $reality);
	}
}
?>