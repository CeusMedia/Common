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
class TestRun
{
	/**
	 *	Runs all TestCases of a TestSuite.
	 *	@access		public
	 *	@param		TestSuite		testsuite		defined TestSuite
	 *	@return		void
	 */
	function run (&$testsuite)
	{
		$testcases =& $testsuite->getTestCases ();
		for ($i=0; $i<count ($testcases); $i++ )
		{
			$testcase =& $testcases[$i];
			$object = $testcase->getObject ();
			$function = $testcase->getFunction ();
			$input = $testcase->getInput();
			if (is_object ($object))
				$result  = $object->$function ($input);
			else
				$result  = $function ($input);
			$testcase->setOutput ($result);
			$testcase->setState ($result === $testcase->getExpect());
		}
	}
}
?>