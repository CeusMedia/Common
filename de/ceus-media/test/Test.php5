<?php
/**
 *	@package	test
 *	@extends	Object
 *	@uses		Expectation
 *	@uses		Decision
 *	@version		0.4
 */
/**
 *	@todo		Code Documentation
 */
import ("de.ceus-media.test.Expectation");
import ("de.ceus-media.test.Decision");
class Test
{
	/**	@var		array		$_results	... */
	var $_results = array ();

	/**
	 *	@param		string		$name		Name of Test
	 *	@return		void
	 */
	public function __construct ($name = false)
	{
		if ($name) $this->note ($name);
	}

	/**
	 *	@param		string		$key		Key Name of Test Result
	 *	@param		string		$value		Test Result
	 *	@return		void
	 */
	function note ($key, $value = false)
	{
		$this->_results[$key] = $value;
	}

	/**
	 *	@param		string		$message	Error Message
	 *	@param		LogFile		$log		Error Log File
	 *	@return		void
	 */
	function _triggerError ($message, $log = false)
	{
		trigger_error ($message, E_USER_ERROR);
	}

	/**
	 *	@param		string		$message	Warning Message
	 *	@param		LogFile		$log		Warning Log File
	 *	@return		void
	 */
	function _triggerWarning ($message, $log = false)
	{
		trigger_error($message, E_USER_WARNING);
	}

	/**
	 *	@return		array
	 */
	function toArray ()
	{
		return $this->_results;
	}

	/**
	 *	@return		string
	 */
	function toString ()
	{
		$code = "<table class='filledframe' width=300>";
		foreach ($this->_results as $key => $value)
		{
			$code .= "<tr><td width=50%>".$key."</td><td width=50%>".$value."</td></tr>";
		}
		$code .= "</table>";
		return $code;
	}

	/**
	 *	@return		void
	 */
	function show ()
	{
		print ($this->toString ());
		$this->_results = array ();
	}
}
?>