<?php
import ("de.ceus-media.ui.DevOutput");
import ("de.ceus-media.test.suite.TestCase");
import ("de.ceus-media.test.suite.TestRun");
/**
 *	TestSuite realising a Test by running TestCases and generating resulting Output.
 *	@package	test
 *	@subpackage	suite
 *	@uses		DevOuput
 *	@uses		TestCase
 *	@uses		TestRun
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	TestSuite realising a Test by running TestCases and generating resulting Output.
 *	@package	test
 *	@subpackage	suite
 *	@uses		DevOuput
 *	@uses		TestCase
 *	@uses		TestRun
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class TestSuite
{
	/**	@var	array	_testcases		Array of TestCase Object */
	var $_testcases = array ();

	/**
	 *	Creates a new TestCase and adds it.
	 *	@access		public
	 *	@param		mixed	object	Object with Functions to test
	 *	@param		mixed	function	Function Name to test
	 *	@param		mixed	input	Input for Function to test
	 *	@param		mixed	expect	expected Result of Function to test
	 *	@return		void
	 */
	function addTestCase ($class, $function, $input, $expect)
	{
		$this->_testcases[] =& new TestCase ($class, $function, $input, $expect);	
	}
	
	/**
	 *	Returns all TestCases.
	 *	@access		public
	 *	@return		array
	 */
	function & getTestCases ()
	{
		return $this->_testcases;
	}
	
	/**
	 *	Runs a Test by calling a TestRun .
	 *	@access		public
	 *	@return		void
	 */
	function run ()
	{
		$testrun = new TestRun ();
		$testrun->run($this);
	}
	
	/**
	 *	Catches  Output of a universal Data Structure.
	 *	@access		public
	 *	@param		mixed	mixed	universal Data Structure to be captured
	 *	@return		string
	 */
	function capture ($mixed)
	{
		ob_start ();
		show ($mixed);
		$code = ob_get_contents ();
		ob_end_clean ();
		return $code;
	}
	
	/**
	 *	Prints out Test results by using a Template.
	 *	@access		public
	 *	@return		void
	 */
	function show ()
	{
		$code = "";
		$counter = 0;
		$testcases = $this->getTestCases ();
		foreach ($testcases as $testcase)
		{
			$object	= $testcase->getObject ();
			$function = $testcase->getFunction ();
			$input	= $this->capture ($testcase->getInput());
			$expect	= $this->capture ($testcase->getExpect());
			$output	= $this->capture ($testcase->getOutput());
			$state	= $testcase->getState () ? "pos" : "neg";
			$counter += (int)$testcase->getState ();

			if (is_object ($object))
				$call = get_class ($object)." :: ".$function;
			else
				$call = $function;
			$code .= "<tr><th colspan='3'>".$call."</th></tr>";
			$code .= "<tr><td class='topic'>Input</td><td class='topic'>Output</td><td  class='".$state."'>Expect</td></tr>";
			$code .= "<tr><td>".$input."</td><td>".$output."</td><td>".$expect."</td></tr>";
		}
		$date	= date("d.m.y");
		$time	= date("H:i:s");
		$class	= get_class ($this);
		$result	= ($counter===count($testcases)) ? "passed" : "failed";
		$counter	= $counter." / ".count($testcases);
		$resultclass = ($counter===count($testcases)) ? "pos" : "neg";
		import ("de.ceus-media.test.suite.TestSuiteTemplate"); 
		echo $code;
	}
}
?>