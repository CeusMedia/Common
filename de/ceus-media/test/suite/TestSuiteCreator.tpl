<?php
require_once ("../../useClasses.php");
import ("de.ceus-media.DevOutput");
import ("de.ceus-media.test.suite.TestSuite");
import ("de.ceus-media.test.suite.#class#");
class #class#Test extends TestSuite
{
	function #class#Test ()
	{
		$#class# = new #class#();
		#testcases#		
		$this->run ();
		$this->show ();
	}
}
$test = new #class#Test;
?>