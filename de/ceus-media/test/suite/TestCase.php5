<?php
/**
 *	TestCase keeping several Data.
 *	@package	test
 *	@subpackage	suite
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	TestCase keeping several Data.
 *	@package	test
 *	@subpackage	suite
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class TestCase
{
	/**	@var	_object		Object with Functions to test */
	var $_object;
	/**	@var	_function		Functions Name to test */
	var $_function;
	/**	@var	_input		Input for Function to test */
	var $_input;
	/**	@var	_expect		expected Result of Function to test */
	var $_expect;
	/**	@var	_output		resulting Output of tested Function */
	var $_output;
	/**	@var	_state		State of TestCase */
	var $_state;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed	object		Object with Functions to test
	 *	@param		string	function		Functions Name to test
	 *	@param		mixed	input		Input for Function to test
	 *	@param		mixed	expect		expected Result of Function to test
	 *	@return		void
	 */
	public function __construct ($object, $function, $input, $expect)
	{
		$this->setObject ($object);
		$this->setFunction ($function);
		$this->setInput ($input);
		$this->setExpect ($expect);
	}
	
	/**
	 *	Returns Object to test with this TestCase.
	 *	@access		public
	 *	@return		void
	 */
	function getObject ()
	{
		return $this->_object;
	}
	
	/**
	 *	Returns Function Name of TestCase.
	 *	@access		public
	 *	@return		string
	 */
	function getFunction ()
	{
		return $this->_function;
	}
	
	/**
	 *	Returns Input for Function to test.
	 *	@access		public
	 *	@return		mixed
	 */
	function getInput ()
	{
		return $this->_input;
	}
	
	/**
	 *	Returns expected Result of TestCase.
	 *	@access		public
	 *	@return		mixed
	 */
	function getExpect()
	{
		return $this->_expect;
	}
	
	/**
	 *	Returns Object with Functions to test.
	 *	@access		public
	 *	@return		mixed
	 */
	function getOutput ()
	{
		return $this->_output;
	}
	
	/**
	 *	Returns resulting State of TestCase.
	 *	@access		public
	 *	@return		bool
	 */
	function getState ()
	{
		return $this->_state;
	}
	

	/**
	 *	Sets a Object with Functions to test.
	 *	@access		public
	 *	@return		void
	 */
	function setObject (&$object)
	{
		$this->_object	=& $object;
	}
	
	/**
	 *	Sets the Name of the Function to test.
	 *	@access		public
	 *	@param		string	Function Name
	 *	@return		void
	 */
	function setFunction ($function)
	{
		$this->_function	= $function;
	}
	
	/**
	 *	Sets Input for Function to test.
	 *	@access		public
	 *	@param		mixed	Input for Function to test
	 *	@return		void
	 */
	function setInput ($input)
	{
		$this->_input		= $input;
	}
	
	/**
	 *	Sets expected Result of Functon to test.
	 *	@access		public
	 *	@param		mixed	expected Result to Function to test
	 *	@return		void
	 */
	function setExpect ($expect)
	{
		$this->_expect	= $expect;
	}
	
	/**
	 *	Sets resulting Output of tested Function.
	 *	@access		public
	 *	@param		mixed	resulting Output of tested Function
	 *	@return		void
	 */
	function setOutput ($output)
	{
		$this->_output	= $output;
	}
	
	/**
	 *	Sets State of TestCase.
	 *	@access		public
	 *	@param		bool		new State of TestCase, true if Exception is Reality
	 *	@return		void
	 */
	function setState ($state)
	{
		$this->_state	= $state;
	}
}
?>