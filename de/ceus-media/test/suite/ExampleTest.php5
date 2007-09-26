<?php
/**
 *	Example TestSuite Class for a Example Class.
 *	@package	test
 *	@subpackage	suite
 *	@extends	TestSuite
 *	@uses		Example
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Example TestSuite Class for a Example Class.
 *	@package	test
 *	@subpackage	suite
 *	@extends	TestSuite
 *	@uses		Example
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
import ("de.ceus-media.test.suite.TestSuite");
import ("de.ceus-media.test.suite.Example");
class ExampleTest extends TestSuite
{
	/**
	 *	Runs Test for Class Example.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ()
	{
		$Example = new Example();
		$this->addTestCase ($Example, "doSomething", false, "humbling around");		
		$this->addTestCase ($Example, "doSomething", false, "humbling around");		
		$this->addTestCase ($Example, "doSomething", false, "humbling around");		
		$this->addTestCase ($Example, "doSomething", false, "humbling around");		
		$this->addTestCase ($Example, "doNothing", false, "1");		
		$this->addTestCase ($Example, "doNothing", false, "0");		
		$this->addTestCase ($Example, "doNothing", false, 1);		
		$this->addTestCase ($Example, "doNothing", false, 0);		
		$this->addTestCase ($Example, "doNothing", false, false);		
		$this->addTestCase ($Example, "doNothing", false, NULL);		
		$this->run ();
		$this->show ();
	}
}
?>